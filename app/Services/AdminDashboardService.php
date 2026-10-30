<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/**
 * Builds every number, chart series and list shown on the ITEB admin dashboard.
 *
 * All figures come from the live tables (registrations, students, slots, results,
 * certificates, timetable). Nothing here is hard-coded sample data.
 *
 * The dashboard can be filtered by admission year and by category
 * (ID = Idaad / O-Level, TH = Thanawi / A-Level). Results are cached for a short
 * time so refreshing the page does not hammer the database; the "Refresh" button
 * bypasses the cache.
 */
class AdminDashboardService
{
    /** Percentage a student must reach to count as "scoring well" on the results card. */
    public const PASS_MARK = 50;

    /** Seconds a computed payload is cached for. */
    private const CACHE_TTL = 60;

    /** Registration workflow statuses, in pipeline order. */
    public const STATUSES = [
        'Pending Photo Submission' => ['key' => 'no_photo', 'label' => 'Awaiting photo', 'color' => '#94A3B8'],
        'Attached Image, Pending Submission' => ['key' => 'unsubmitted', 'label' => 'Photo added, not submitted', 'color' => '#3D6FB6'],
        'Pending Admin Approval' => ['key' => 'pending', 'label' => 'Awaiting approval', 'color' => '#E0A526'],
        'Approved' => ['key' => 'approved', 'label' => 'Approved', 'color' => '#1E7A3D'],
        'Returned' => ['key' => 'returned', 'label' => 'Returned to school', 'color' => '#D64545'],
    ];

    // Student IDs look like IT-081-ID-001-2026  (school - category - serial - year)
    private const SB_YEAR = "SUBSTRING_INDEX(Student_ID, '-', -1)";
    private const SB_CATEGORY = "SUBSTRING_INDEX(SUBSTRING_INDEX(Student_ID, '-', 3), '-', -1)";
    private const SB_SCHOOL = "SUBSTRING_INDEX(Student_ID, '-', 2)";

    // ------------------------------------------------------------------
    //  Public API
    // ------------------------------------------------------------------

    /**
     * Resolve the requested filters against what actually exists in the database.
     */
    public function filters(?string $year, ?string $category): array
    {
        $years = $this->availableYears();
        $default = $this->defaultYear($years);

        $year = $year === 'all' ? 'all' : (in_array((string) $year, $years, true) ? (string) $year : $default);

        $category = strtoupper((string) $category);
        $category = in_array($category, ['ID', 'TH'], true) ? $category : 'all';

        return [
            'year' => $year,
            'category' => $category,
            'years' => $years,
            'default_year' => $default,
        ];
    }

    /**
     * Return the full dashboard payload (cached briefly).
     */
    public function build(?string $year = null, ?string $category = null, bool $fresh = false): array
    {
        $filters = $this->filters($year, $category);
        $key = "admin_dashboard:v1:{$filters['year']}:{$filters['category']}";

        try {
            if ($fresh) {
                Cache::forget($key);
            }

            return Cache::remember($key, self::CACHE_TTL, fn() => $this->compute($filters));
        } catch (\Throwable $e) {
            // A broken cache store must never take the dashboard down.
            Log::warning('Admin dashboard cache unavailable: ' . $e->getMessage());

            return $this->compute($filters);
        }
    }

    // ------------------------------------------------------------------
    //  Assembly
    // ------------------------------------------------------------------

    private function compute(array $f): array
    {
        $now = Carbon::now();
        $slotYear = $f['year'] === 'all' ? $f['default_year'] : $f['year'];

        $pipeline = $this->pipeline($f);
        $daily = $this->dailySeries($f, 30);
        $monthly = $this->monthlySeries($f, 12);
        $gender = $this->gender($f);
        $history = $this->enrolmentHistory();
        $slots = $this->slots($slotYear);
        $attention = $this->attention($f);
        $results = $this->results($f);
        $performance = $this->performanceTrend();
        $photos = $this->photos($f);
        $certs = $this->certificates();
        $window = $this->registrationWindow();
        $schoolsTotal = (int) DB::table('houses')->count();
        $schoolsRegistering = (int) $this->regQuery($f)->distinct()->count('school_id');
        $enrolled = (int) $this->studentsQuery($f)->count();
        $enrolledAllTime = (int) $this->studentsQuery(['year' => 'all', 'category' => 'all'])->count();

        $totals = $pipeline['totals'];
        $withSchools = $totals['no_photo'] + $totals['unsubmitted'];

        // Week-over-week movement of new registrations.
        $last7 = array_sum(array_slice($daily['registered'], -7));
        $prev7 = array_sum(array_slice($daily['registered'], -14, 7));

        $yearLabel = $f['year'] === 'all' ? 'all years' : $f['year'];

        $kpis = [
            [
                'key' => 'registrations',
                'label' => 'Registrations',
                'value' => $pipeline['total'],
                'sub' => number_format($totals['pending'] + $totals['approved'] + $totals['returned']) . ' submitted to ITEB',
                'icon' => 'fa-clipboard-list',
                'tone' => 'green',
                'url' => $this->url('admin.student.approvals'),
                'delta' => $this->delta($last7, $prev7),
                'delta_label' => 'vs previous 7 days',
                'spark' => array_slice($daily['registered'], -14),
            ],
            [
                'key' => 'pending',
                'label' => 'Awaiting your approval',
                'value' => $totals['pending'],
                'sub' => $attention['schools_with_pending'] . ' school' . ($attention['schools_with_pending'] === 1 ? '' : 's') . ' waiting',
                'icon' => 'fa-hourglass-half',
                'tone' => 'amber',
                'url' => $this->url('admin.student.approvals'),
                'pulse' => $totals['pending'] > 0,
            ],
            [
                'key' => 'enrolled',
                'label' => $f['year'] === 'all' ? 'Enrolled students' : 'Enrolled in ' . $f['year'],
                'value' => $enrolled,
                'sub' => number_format($gender['male']) . ' male · ' . number_format($gender['female']) . ' female',
                'icon' => 'fa-user-graduate',
                'tone' => 'teal',
                'url' => $this->url('students.all.students'),
                'spark' => array_values(array_map(fn($r) => $r['total'], array_slice($history['rows'], -8))),
            ],
            [
                'key' => 'schools',
                'label' => 'Schools',
                'value' => $schoolsTotal,
                'sub' => $schoolsRegistering . ' registering (' . $yearLabel . ')',
                'icon' => 'fa-school',
                'tone' => 'indigo',
                'url' => $this->url('school.allSchools'),
            ],
            [
                'key' => 'slots',
                'label' => 'Slots used (' . $slotYear . ')',
                'value' => $slots['percent'],
                'suffix' => '%',
                'sub' => number_format($slots['used']) . ' of ' . number_format($slots['allocated']) . ' slots',
                'icon' => 'fa-ticket',
                'tone' => 'gold',
                'url' => $this->url('admin.student.approvals'),
                'progress' => $slots['percent'],
            ],
            [
                'key' => 'with_schools',
                'label' => 'Still with schools',
                'value' => $withSchools,
                'sub' => number_format($totals['no_photo']) . ' still need a photo',
                'icon' => 'fa-camera',
                'tone' => 'slate',
                'url' => $this->url('admin.student.approvals'),
            ],
            [
                'key' => 'results',
                'label' => 'Average score',
                // null = "no results yet": the UI shows a dash instead of a misleading 0%.
                'value' => $results['count'] > 0 ? $results['average'] : null,
                'suffix' => '%',
                'decimals' => 1,
                'sub' => $results['count'] > 0
                    ? number_format($results['count']) . ' results · ' . $results['pass_rate'] . '% scored ' . self::PASS_MARK . '%+'
                    : 'No results published for ' . $yearLabel . ' yet',
                'icon' => 'fa-chart-line',
                'tone' => 'rose',
                'url' => $this->url('iteb.exam.statistics'),
                'spark' => $results['count'] > 0
                    ? array_values(array_filter(array_map(
                        fn($r) => $r['avg'],
                        array_slice($performance['rows'], -8)
                    ), fn($v) => $v !== null))
                    : [],
            ],
            [
                'key' => 'certificates',
                'label' => 'Recognised schools',
                'value' => $certs['covered'],
                'sub' => $certs['coverage'] . '% of schools hold an active certificate',
                'icon' => 'fa-certificate',
                'tone' => 'purple',
                'url' => $this->url('school.recognition.index'),
            ],
        ];

        return [
            'meta' => [
                'generated_at' => $now->toIso8601String(),
                'generated_label' => $now->format('H:i'),
                'year_label' => $yearLabel,
                'slot_year' => $slotYear,
                'pass_mark' => self::PASS_MARK,
            ],
            'filters' => [
                'year' => $f['year'],
                'category' => $f['category'],
            ],
            'window' => $window,
            'kpis' => $kpis,
            'alerts' => $this->alerts($totals, $attention, $slots, $photos, $certs, $window, $slotYear),
            'pipeline' => $pipeline,
            'trend' => ['daily' => $daily, 'monthly' => $monthly],
            'gender' => $gender,
            'enrolment_history' => $history,
            'top_schools' => $this->topSchools($f),
            'slots' => $slots,
            'attention' => $attention,
            'results' => $results,
            'performance' => $performance,
            'top_performers' => $this->topPerformers($f),
            'districts' => $this->districts($f),
            'photos' => $photos,
            'certificates' => $certs,
            'activity' => $this->activity(),
            'upcoming_exams' => $this->upcomingExams(),
            'enrolled_all_time' => $enrolledAllTime,
        ];
    }

    // ------------------------------------------------------------------
    //  Filters / base queries
    // ------------------------------------------------------------------

    private function availableYears(): array
    {
        $years = collect()
            ->merge(DB::table('academic_years')->pluck('year_en'))
            ->merge(DB::table('student_registrations')->distinct()->pluck('admission_year'))
            ->merge(DB::table('student_results')->distinct()->pluck('year'))
            ->map(fn($y) => (string) $y)
            ->filter(fn($y) => preg_match('/^\d{4}$/', $y))
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        return $years;
    }

    private function defaultYear(array $years): string
    {
        $active = DB::table('academic_years')->where('status', 'Active')->orderByDesc('year_en')->value('year_en');

        if ($active && in_array((string) $active, $years, true)) {
            return (string) $active;
        }

        return $years[0] ?? (string) date('Y');
    }

    private function regQuery(array $f)
    {
        return DB::table('student_registrations')
            ->when($f['year'] !== 'all', fn($q) => $q->where('admission_year', $f['year']))
            ->when($f['category'] !== 'all', fn($q) => $q->where('category', $f['category']));
    }

    private function studentsQuery(array $f)
    {
        return DB::table('students_basic')
            ->whereRaw("COALESCE(state, 'Active') = 'Active'")
            ->when($f['year'] !== 'all', fn($q) => $q->whereRaw(self::SB_YEAR . ' = ?', [$f['year']]))
            ->when($f['category'] !== 'all', fn($q) => $q->whereRaw(self::SB_CATEGORY . ' = ?', [$f['category']]));
    }

    private function resultsQuery(array $f)
    {
        return DB::table('student_results')
            ->when($f['year'] !== 'all', fn($q) => $q->where('year', $f['year']))
            ->when($f['category'] !== 'all', fn($q) => $q->where('category', $f['category']));
    }

    // ------------------------------------------------------------------
    //  Registration pipeline
    // ------------------------------------------------------------------

    private function pipeline(array $f): array
    {
        $counts = $this->regQuery($f)
            ->selectRaw('status, COUNT(*) AS c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $totals = ['no_photo' => 0, 'unsubmitted' => 0, 'pending' => 0, 'approved' => 0, 'returned' => 0, 'other' => 0];
        $slices = [];
        $known = 0;

        foreach (self::STATUSES as $status => $meta) {
            $c = (int) ($counts[$status] ?? 0);
            $totals[$meta['key']] = $c;
            $known += $c;
            $slices[] = ['label' => $meta['label'], 'value' => $c, 'color' => $meta['color']];
        }

        $total = (int) $counts->sum();

        if ($total - $known > 0) {
            $totals['other'] = $total - $known;
            $slices[] = ['label' => 'Other', 'value' => $total - $known, 'color' => '#CBD5E1'];
        }

        $decided = $totals['approved'] + $totals['returned'];

        return [
            'total' => $total,
            'totals' => $totals,
            'slices' => $slices,
            'approval_rate' => $decided > 0 ? round($totals['approved'] / $decided * 100) : null,
        ];
    }

    // ------------------------------------------------------------------
    //  Time series
    // ------------------------------------------------------------------

    private function dailySeries(array $f, int $days): array
    {
        $start = Carbon::today()->subDays($days - 1);

        $registered = $this->regQuery($f)
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) AS d, COUNT(*) AS c')
            ->groupByRaw('DATE(created_at)')
            ->pluck('c', 'd');

        $submitted = $this->regQuery($f)
            ->whereNotNull('submitted_at')
            ->where('submitted_at', '>=', $start)
            ->selectRaw('DATE(submitted_at) AS d, COUNT(*) AS c')
            ->groupByRaw('DATE(submitted_at)')
            ->pluck('c', 'd');

        $labels = [];
        $reg = [];
        $sub = [];

        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i);
            $key = $day->toDateString();
            $labels[] = $day->format('j M');
            $reg[] = (int) ($registered[$key] ?? 0);
            $sub[] = (int) ($submitted[$key] ?? 0);
        }

        return ['labels' => $labels, 'registered' => $reg, 'submitted' => $sub];
    }

    private function monthlySeries(array $f, int $months): array
    {
        $start = Carbon::today()->startOfMonth()->subMonths($months - 1);

        $registered = $this->regQuery($f)
            ->where('created_at', '>=', $start)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') AS m, COUNT(*) AS c")
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->pluck('c', 'm');

        $submitted = $this->regQuery($f)
            ->whereNotNull('submitted_at')
            ->where('submitted_at', '>=', $start)
            ->selectRaw("DATE_FORMAT(submitted_at, '%Y-%m') AS m, COUNT(*) AS c")
            ->groupByRaw("DATE_FORMAT(submitted_at, '%Y-%m')")
            ->pluck('c', 'm');

        $labels = [];
        $reg = [];
        $sub = [];

        for ($i = 0; $i < $months; $i++) {
            $month = $start->copy()->addMonths($i);
            $key = $month->format('Y-m');
            $labels[] = $month->format('M Y');
            $reg[] = (int) ($registered[$key] ?? 0);
            $sub[] = (int) ($submitted[$key] ?? 0);
        }

        return ['labels' => $labels, 'registered' => $reg, 'submitted' => $sub];
    }

    // ------------------------------------------------------------------
    //  Students
    // ------------------------------------------------------------------

    private function gender(array $f): array
    {
        $rows = $this->studentsQuery($f)
            ->selectRaw('StudentSex AS sex, COUNT(*) AS c')
            ->groupBy('StudentSex')
            ->pluck('c', 'sex');

        $male = (int) ($rows['Male'] ?? 0);
        $female = (int) ($rows['Female'] ?? 0);
        $total = (int) $rows->sum();
        $other = max(0, $total - $male - $female);

        return [
            'male' => $male,
            'female' => $female,
            'unspecified' => $other,
            'total' => $total,
            'female_percent' => $total > 0 ? round($female / $total * 100) : 0,
        ];
    }

    /**
     * Enrolment by admission year, split by category. Ignores the filters on purpose:
     * it is the "big picture" view.
     */
    private function enrolmentHistory(): array
    {
        $rows = DB::table('students_basic')
            ->whereRaw("COALESCE(state, 'Active') = 'Active'")
            ->whereRaw(self::SB_YEAR . " REGEXP '^[0-9]{4}$'")
            ->selectRaw(self::SB_YEAR . ' AS y, ' . self::SB_CATEGORY . ' AS cat, COUNT(*) AS c')
            ->groupByRaw(self::SB_YEAR . ', ' . self::SB_CATEGORY)
            ->get();

        $byYear = [];

        foreach ($rows as $r) {
            $byYear[$r->y] ??= ['year' => (string) $r->y, 'ID' => 0, 'TH' => 0, 'total' => 0];

            if (in_array($r->cat, ['ID', 'TH'], true)) {
                $byYear[$r->y][$r->cat] += (int) $r->c;
            }

            $byYear[$r->y]['total'] += (int) $r->c;
        }

        ksort($byYear);

        return ['rows' => array_values(array_slice($byYear, -8, null, false))];
    }

    private function districts(array $f): array
    {
        $rows = $this->studentsQuery($f)
            ->whereNotNull('District')
            ->where('District', '<>', '')
            ->selectRaw('District AS name, COUNT(*) AS c')
            ->groupBy('District')
            ->orderByDesc('c')
            ->limit(8)
            ->get();

        return $rows->map(fn($r) => ['name' => $r->name, 'value' => (int) $r->c])->all();
    }

    private function photos(array $f): array
    {
        $row = $this->studentsQuery($f)
            ->selectRaw("COUNT(*) AS total, SUM(CASE WHEN Photo IS NOT NULL AND Photo <> '' THEN 1 ELSE 0 END) AS with_photo")
            ->first();

        $total = (int) ($row->total ?? 0);
        $with = (int) ($row->with_photo ?? 0);
        $missing = $total - $with;

        $bySchool = $this->studentsQuery($f)
            ->whereRaw("(Photo IS NULL OR Photo = '')")
            ->selectRaw(self::SB_SCHOOL . ' AS number, COUNT(*) AS c')
            ->groupByRaw(self::SB_SCHOOL)
            ->orderByDesc('c')
            ->limit(5)
            ->get();

        $names = DB::table('houses')->whereIn('Number', $bySchool->pluck('number'))->pluck('House', 'Number');

        return [
            'total' => $total,
            'with_photo' => $with,
            'missing' => $missing,
            'percent' => $total > 0 ? round($with / $total * 100) : 0,
            'worst_schools' => $bySchool->map(fn($r) => [
                'name' => $names[$r->number] ?? $r->number,
                'number' => $r->number,
                'missing' => (int) $r->c,
            ])->all(),
        ];
    }

    // ------------------------------------------------------------------
    //  Schools
    // ------------------------------------------------------------------

    private function topSchools(array $f): array
    {
        $rows = $this->regQuery($f)
            ->selectRaw(
                "school_id, COUNT(*) AS total, " .
                "SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) AS approved, " .
                "SUM(CASE WHEN status = 'Pending Admin Approval' THEN 1 ELSE 0 END) AS pending, " .
                "SUM(CASE WHEN status = 'Returned' THEN 1 ELSE 0 END) AS returned, " .
                "SUM(CASE WHEN status IN ('Pending Photo Submission','Attached Image, Pending Submission') THEN 1 ELSE 0 END) AS with_school"
            )
            ->groupBy('school_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $houses = DB::table('houses')->whereIn('ID', $rows->pluck('school_id'))->get(['ID', 'House', 'Number'])->keyBy('ID');

        return $rows->map(function ($r) use ($houses) {
            $h = $houses->get($r->school_id);
            $name = $h->House ?? ('School #' . $r->school_id);

            return [
                'name' => $name,
                'short' => $this->shorten($name, 26),
                'total' => (int) $r->total,
                'approved' => (int) $r->approved,
                'pending' => (int) $r->pending,
                'returned' => (int) $r->returned,
                'with_school' => (int) $r->with_school,
                'url' => $h ? $this->url('admin.student.approvals.detail', ['schoolPrefix' => $h->Number]) : null,
            ];
        })->all();
    }

    private function attention(array $f): array
    {
        $base = $this->regQuery($f)->where('status', 'Pending Admin Approval');

        $schoolsWithPending = (int) (clone $base)->distinct()->count('school_id');

        $rows = (clone $base)
            ->selectRaw('school_id, COUNT(*) AS pending, MIN(COALESCE(submitted_at, updated_at)) AS oldest')
            ->groupBy('school_id')
            ->orderByDesc('pending')
            ->limit(8)
            ->get();

        $houses = DB::table('houses')->whereIn('ID', $rows->pluck('school_id'))->get(['ID', 'House', 'Number'])->keyBy('ID');
        $today = Carbon::now();

        return [
            'schools_with_pending' => $schoolsWithPending,
            'rows' => $rows->map(function ($r) use ($houses, $today) {
                $h = $houses->get($r->school_id);
                $oldest = $r->oldest ? Carbon::parse($r->oldest) : null;

                return [
                    'name' => $h->House ?? ('School #' . $r->school_id),
                    'number' => $h->Number ?? '',
                    'pending' => (int) $r->pending,
                    'waiting_days' => $oldest ? (int) $oldest->diffInDays($today) : null,
                    'url' => $h ? $this->url('admin.student.approvals.detail', ['schoolPrefix' => $h->Number]) : null,
                ];
            })->all(),
        ];
    }

    private function slots(string $year): array
    {
        $rows = DB::table('school_registration_slots')->where('admission_year', $year)->get();

        $allocated = (int) $rows->sum('slots_allocated');
        $used = (int) $rows->sum('slots_used');
        $houses = DB::table('houses')->whereIn('ID', $rows->pluck('school_id'))->pluck('House', 'ID');

        $withSlots = $rows->filter(fn($r) => $r->slots_allocated > 0);

        $buckets = ['Not started' => 0, '1–49%' => 0, '50–89%' => 0, '90–100%' => 0];

        foreach ($withSlots as $r) {
            $pct = $r->slots_used / $r->slots_allocated * 100;

            if ($r->slots_used <= 0) {
                $buckets['Not started']++;
            } elseif ($pct < 50) {
                $buckets['1–49%']++;
            } elseif ($pct < 90) {
                $buckets['50–89%']++;
            } else {
                $buckets['90–100%']++;
            }
        }

        $nearlyFull = $withSlots
            ->filter(fn($r) => $r->slots_used / $r->slots_allocated >= 0.9)
            ->sortByDesc(fn($r) => $r->slots_used / $r->slots_allocated)
            ->take(5)
            ->map(fn($r) => [
                'name' => $houses[$r->school_id] ?? ('School #' . $r->school_id),
                'used' => (int) $r->slots_used,
                'allocated' => (int) $r->slots_allocated,
            ])->values()->all();

        $notStarted = $withSlots->filter(fn($r) => $r->slots_used <= 0);

        return [
            'year' => $year,
            'allocated' => $allocated,
            'used' => $used,
            'remaining' => max(0, $allocated - $used),
            'percent' => $allocated > 0 ? (int) round($used / $allocated * 100) : 0,
            'schools_with_slots' => $withSlots->count(),
            'open_schools' => (int) $withSlots->filter(fn($r) => (bool) $r->registration_open)->count(),
            'buckets' => $buckets,
            'nearly_full' => $nearlyFull,
            'not_started_count' => $notStarted->count(),
            'not_started' => $notStarted->sortByDesc('slots_allocated')->take(5)->map(fn($r) => [
                'name' => $houses[$r->school_id] ?? ('School #' . $r->school_id),
                'allocated' => (int) $r->slots_allocated,
            ])->values()->all(),
        ];
    }

    private function certificates(): array
    {
        $schools = (int) DB::table('houses')->count();

        $activeNumbers = DB::table('school_recognition_certificates')
            ->where('status', 'active')
            ->distinct()
            ->pluck('house_number');

        $covered = (int) DB::table('houses')->whereIn('Number', $activeNumbers)->count();

        $withoutCert = DB::table('houses')
            ->whereNotIn('Number', $activeNumbers)
            ->orderBy('House')
            ->limit(5)
            ->get(['House', 'Number'])
            ->map(fn($h) => ['name' => $h->House, 'number' => $h->Number])
            ->all();

        return [
            'active' => (int) DB::table('school_recognition_certificates')->where('status', 'active')->count(),
            'revoked' => (int) DB::table('school_recognition_certificates')->where('status', 'revoked')->count(),
            'covered' => $covered,
            'uncovered' => max(0, $schools - $covered),
            'uncovered_list' => $withoutCert,
            'coverage' => $schools > 0 ? (int) round($covered / $schools * 100) : 0,
        ];
    }

    // ------------------------------------------------------------------
    //  Results & performance
    // ------------------------------------------------------------------

    private function results(array $f): array
    {
        $agg = $this->resultsQuery($f)
            ->selectRaw(
                'COUNT(*) AS c, AVG(percentage) AS avg_pct, MAX(percentage) AS max_pct, ' .
                'SUM(CASE WHEN percentage >= ? THEN 1 ELSE 0 END) AS passed',
                [self::PASS_MARK]
            )
            ->first();

        $count = (int) ($agg->c ?? 0);

        return [
            'count' => $count,
            'average' => $count > 0 ? round((float) $agg->avg_pct, 1) : 0,
            'highest' => $count > 0 ? round((float) $agg->max_pct, 1) : 0,
            'pass_rate' => $count > 0 ? (int) round($agg->passed / $count * 100) : 0,
            'latest_year' => DB::table('student_results')->max('year'),
            'grades' => $this->distribution($f, 'grade', 'Marks'),
            'classifications' => $this->distribution($f, 'classification', 'Points'),
        ];
    }

    /**
     * Distribution of a graded column, ordered best-to-worst using the grading table's weights.
     */
    private function distribution(array $f, string $column, string $gradingType): array
    {
        $counts = $this->resultsQuery($f)
            ->selectRaw("$column AS label, COUNT(*) AS c")
            ->groupBy($column)
            ->pluck('c', 'label');

        $weights = DB::table('grading')->where('Type', $gradingType)->pluck('Weight', 'Grade');

        return $counts
            ->map(fn($c, $label) => [
                'label' => ($label === null || $label === '') ? 'Ungraded' : (string) $label,
                'value' => (int) $c,
                'weight' => $weights[$label] ?? 9999,
            ])
            ->sortBy([['weight', 'asc'], ['label', 'asc']])
            ->map(fn($r) => ['label' => $r['label'], 'value' => $r['value']])
            ->values()
            ->all();
    }

    /**
     * Average score per year and category. Ignores the filters: shows the long-term trend.
     */
    private function performanceTrend(): array
    {
        $rows = DB::table('student_results')
            ->selectRaw('year, category, AVG(percentage) AS avg_pct')
            ->groupBy('year', 'category')
            ->orderBy('year')
            ->get();

        $years = $rows->pluck('year')->unique()->sort()->values()->take(-8)->values();
        $out = [];

        foreach ($years as $y) {
            $id = $rows->first(fn($r) => $r->year == $y && $r->category === 'ID');
            $th = $rows->first(fn($r) => $r->year == $y && $r->category === 'TH');
            $vals = array_filter([$id->avg_pct ?? null, $th->avg_pct ?? null], fn($v) => $v !== null);

            $out[] = [
                'year' => (string) $y,
                'ID' => $id ? round((float) $id->avg_pct, 1) : null,
                'TH' => $th ? round((float) $th->avg_pct, 1) : null,
                'avg' => $vals ? round(array_sum($vals) / count($vals), 1) : null,
            ];
        }

        return ['rows' => $out];
    }

    private function topPerformers(array $f): array
    {
        $rows = $this->resultsQuery($f)
            ->selectRaw('school_number, COUNT(*) AS c, AVG(percentage) AS avg_pct')
            ->whereNotNull('school_number')
            ->groupBy('school_number')
            ->having('c', '>=', 5)
            ->orderByDesc('avg_pct')
            ->limit(5)
            ->get();

        $names = DB::table('houses')->whereIn('Number', $rows->pluck('school_number'))->pluck('House', 'Number');

        return $rows->map(fn($r) => [
            'name' => $names[$r->school_number] ?? $r->school_number,
            'average' => round((float) $r->avg_pct, 1),
            'students' => (int) $r->c,
        ])->all();
    }

    // ------------------------------------------------------------------
    //  Window, activity, exams, alerts
    // ------------------------------------------------------------------

    private function registrationWindow(): array
    {
        $period = DB::table('registration_periods')->where('status', 'active')->first();

        if (!$period) {
            return ['state' => 'none', 'label' => 'No registration period configured', 'name' => null];
        }

        $now = Carbon::now();
        $opens = $period->opens_at ? Carbon::parse($period->opens_at) : null;
        $closes = $period->closes_at ? Carbon::parse($period->closes_at) : null;

        $state = 'open';

        if ($opens && $now->lt($opens)) {
            $state = 'upcoming';
        } elseif ($closes && $now->gt($closes)) {
            $state = 'ended';
        }

        $progress = null;
        if ($opens && $closes && $closes->gt($opens)) {
            $progress = (int) max(0, min(100, round($opens->diffInSeconds($now, false) / $opens->diffInSeconds($closes) * 100)));
        }

        // Carbon 3: diffInDays() is a signed float (positive when $closes is in the future).
        $daysLeft = ($closes && $state === 'open') ? max(0, (int) ceil($now->diffInDays($closes, false))) : null;

        $label = match ($state) {
            'open' => $daysLeft !== null
                ? 'Registration open · closes in ' . $daysLeft . ' day' . ($daysLeft === 1 ? '' : 's')
                : 'Registration open',
            'upcoming' => 'Registration opens ' . $opens->format('j M Y'),
            default => 'Registration closed ' . ($closes ? $closes->format('j M Y') : ''),
        };

        return [
            'state' => $state,
            'label' => trim($label),
            'name' => $period->name,
            'year' => (int) $period->admission_year,
            'opens' => $opens?->format('j M Y'),
            'closes' => $closes?->format('j M Y'),
            'days_left' => $daysLeft,
            'progress' => $progress,
        ];
    }

    private function activity(): array
    {
        $registrations = DB::table('student_registrations as r')
            ->leftJoin('houses as h', 'h.ID', '=', 'r.school_id')
            ->orderByDesc('r.updated_at')
            ->limit(8)
            ->get(['r.student_name', 'r.student_id', 'r.status', 'r.updated_at', 'h.House as school']);

        $items = [];

        foreach ($registrations as $r) {
            [$verb, $tone, $icon] = match ($r->status) {
                'Approved' => ['was approved', 'green', 'fa-circle-check'],
                'Returned' => ['was returned to the school', 'red', 'fa-rotate-left'],
                'Pending Admin Approval' => ['was submitted for approval', 'amber', 'fa-paper-plane'],
                'Attached Image, Pending Submission' => ['had a photo attached', 'blue', 'fa-image'],
                default => ['was registered', 'slate', 'fa-user-plus'],
            };

            $items[] = [
                'ts' => Carbon::parse($r->updated_at)->timestamp,
                'iso' => Carbon::parse($r->updated_at)->toIso8601String(),
                'ago' => Carbon::parse($r->updated_at)->diffForHumans(),
                'title' => $r->student_name . ' ' . $verb,
                'meta' => trim(($r->school ?? 'Unknown school') . ' · ' . $r->student_id),
                'tone' => $tone,
                'icon' => $icon,
            ];
        }

        $slotHistory = DB::table('school_slot_history as s')
            ->leftJoin('houses as h', 'h.ID', '=', 's.school_id')
            ->orderByDesc('s.created_at')
            ->limit(4)
            ->get(['s.slots_added', 's.admission_year', 's.created_at', 'h.House as school']);

        foreach ($slotHistory as $s) {
            $items[] = [
                'ts' => Carbon::parse($s->created_at)->timestamp,
                'iso' => Carbon::parse($s->created_at)->toIso8601String(),
                'ago' => Carbon::parse($s->created_at)->diffForHumans(),
                'title' => ($s->slots_added >= 0 ? '+' : '') . $s->slots_added . ' slots for ' . ($s->school ?? 'a school'),
                'meta' => 'Admission year ' . $s->admission_year,
                'tone' => 'gold',
                'icon' => 'fa-ticket',
            ];
        }

        usort($items, fn($a, $b) => $b['ts'] <=> $a['ts']);

        return array_slice($items, 0, 10);
    }

    private function upcomingExams(): array
    {
        $today = Carbon::today();

        return DB::table('exam_timetables')
            ->where('exam_date', '>=', $today->toDateString())
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get()
            ->map(function ($e) use ($today) {
                $date = Carbon::parse($e->exam_date);

                return [
                    'subject' => $e->subject_ar,
                    'paper' => $e->paper_ar,
                    'category' => $e->category === 'idaad' ? 'Idaad' : 'Thanawi',
                    'date' => $date->format('D, j M'),
                    'day' => $date->format('j'),
                    'month' => $date->format('M'),
                    'time' => substr($e->start_time, 0, 5) . '–' . substr($e->end_time, 0, 5),
                    'days_until' => (int) $today->diffInDays($date),
                ];
            })->all();
    }

    private function alerts(array $totals, array $attention, array $slots, array $photos, array $certs, array $window, string $slotYear): array
    {
        $alerts = [];
        $approvalsUrl = $this->url('admin.student.approvals');

        if ($totals['pending'] > 0) {
            $alerts[] = [
                'tone' => 'amber',
                'icon' => 'fa-hourglass-half',
                'text' => number_format($totals['pending']) . ' registration' . ($totals['pending'] === 1 ? ' is' : 's are') .
                    ' waiting for approval across ' . $attention['schools_with_pending'] . ' school' . ($attention['schools_with_pending'] === 1 ? '' : 's') . '.',
                'url' => $approvalsUrl,
                'cta' => 'Review',
            ];
        }

        if ($totals['returned'] > 0) {
            $alerts[] = [
                'tone' => 'red',
                'icon' => 'fa-rotate-left',
                'text' => number_format($totals['returned']) . ' returned registration' . ($totals['returned'] === 1 ? '' : 's') . ' still need correcting by the school.',
                'url' => $approvalsUrl,
                'cta' => 'View',
            ];
        }

        if ($slots['not_started_count'] > 0) {
            $alerts[] = [
                'tone' => 'blue',
                'icon' => 'fa-ticket',
                'text' => $slots['not_started_count'] . ' school' . ($slots['not_started_count'] === 1 ? ' has' : 's have') .
                    " slots for $slotYear but haven't registered anyone yet.",
                'url' => $approvalsUrl,
                'cta' => 'See schools',
            ];
        }

        if ($photos['missing'] > 0) {
            $alerts[] = [
                'tone' => 'slate',
                'icon' => 'fa-camera',
                'text' => number_format($photos['missing']) . ' enrolled student' . ($photos['missing'] === 1 ? ' has' : 's have') . ' no photo on file.',
                'url' => $this->url('students.all.students'),
                'cta' => 'Open students',
            ];
        }

        if ($certs['uncovered'] > 0) {
            $alerts[] = [
                'tone' => 'purple',
                'icon' => 'fa-certificate',
                'text' => $certs['uncovered'] . ' school' . ($certs['uncovered'] === 1 ? ' has' : 's have') . ' no active recognition certificate.',
                'url' => $this->url('school.recognition.index'),
                'cta' => 'Certificates',
            ];
        }

        if ($window['state'] === 'open' && ($window['days_left'] ?? 99) <= 7) {
            array_unshift($alerts, [
                'tone' => 'red',
                'icon' => 'fa-clock',
                'text' => 'Registration closes in ' . $window['days_left'] . ' day' . ($window['days_left'] === 1 ? '' : 's') . '.',
                'url' => $approvalsUrl,
                'cta' => 'Manage period',
            ]);
        }

        if (!$alerts) {
            $alerts[] = [
                'tone' => 'green',
                'icon' => 'fa-circle-check',
                'text' => 'Everything is up to date. Nothing needs your attention right now.',
                'url' => null,
                'cta' => null,
            ];
        }

        return array_slice($alerts, 0, 5);
    }

    // ------------------------------------------------------------------
    //  Small helpers
    // ------------------------------------------------------------------

    private function delta(int|float $current, int|float $previous): ?array
    {
        if ($previous <= 0) {
            return $current > 0 ? ['direction' => 'up', 'percent' => null] : null;
        }

        $pct = round(($current - $previous) / $previous * 100);

        return [
            'direction' => $pct > 0 ? 'up' : ($pct < 0 ? 'down' : 'flat'),
            'percent' => abs((int) $pct),
        ];
    }

    private function shorten(string $text, int $max): string
    {
        return mb_strlen($text) > $max ? rtrim(mb_substr($text, 0, $max - 1)) . '…' : $text;
    }

    private function url(string $route, array $params = []): ?string
    {
        return Route::has($route) ? route($route, $params) : null;
    }
}
