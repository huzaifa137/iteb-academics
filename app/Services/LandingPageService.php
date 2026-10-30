<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Live data for the public landing page.
 *
 * Only aggregate, non-personal information is exposed: headline counts, the current registration
 * window and the next few timetabled papers. The result is cached for a few minutes and the page
 * must never fail because of it: any error simply yields an empty payload and the page falls back
 * to its static content.
 */
class LandingPageService
{
    private const CACHE_KEY = 'landing_page:v2';
    private const CACHE_TTL = 300;

    public function build(): array
    {
        try {
            return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, fn() => $this->compute());
        } catch (\Throwable $e) {
            Log::warning('Landing page data unavailable: ' . $e->getMessage());

            return $this->empty();
        }
    }

    private function empty(): array
    {
        return ['stats' => [], 'window' => ['state' => 'none'], 'papers' => []];
    }

    private function compute(): array
    {
        return [
            'stats' => config('landing.show_stats') ? $this->stats() : [],
            'window' => $this->registrationWindow(),
            'papers' => config('landing.show_timetable') ? $this->upcomingPapers() : [],
        ];
    }

    private function stats(): array
    {
        $results = (int) DB::table('student_results')->count();

        $stats = [
            // Display-only placeholder figures (real counts are confidential). Shown as "200+", "20,000+", "200+".
            ['key' => 'schools', 'label' => 'Registered schools', 'value' => 200, 'suffix' => '+', 'icon' => 'school'],
            ['key' => 'students', 'label' => 'Students enrolled', 'value' => 20000, 'suffix' => '+', 'icon' => 'users'],
            ['key' => 'results', 'label' => 'Results processed', 'value' => $results, 'icon' => 'bar-chart'],
            ['key' => 'recognised', 'label' => 'Recognised schools', 'value' => 200, 'suffix' => '+', 'icon' => 'award'],
        ];

        // A zero would look odd on a public page: only show what has something to show.
        $stats = array_values(array_filter($stats, fn($s) => $s['value'] > 0));

        return count($stats) >= 2 ? $stats : [];
    }

    private function registrationWindow(): array
    {
        $period = DB::table('registration_periods')->where('status', 'active')->first();

        if (!$period) {
            return ['state' => 'none'];
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

        return [
            'state' => $state,
            'year' => (int) $period->admission_year,
            'opens' => $opens?->format('j M Y'),
            'closes' => $closes?->format('j M Y'),
            // Carbon 3: diffInDays() is a signed float.
            'days_left' => ($closes && $state === 'open') ? max(0, (int) ceil($now->diffInDays($closes, false))) : null,
            'progress' => $progress,
        ];
    }

    private function upcomingPapers(): array
    {
        $today = Carbon::today();

        return DB::table('exam_timetables')
            ->where('exam_date', '>=', $today->toDateString())
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->limit(4)
            ->get()
            ->map(function ($e) use ($today) {
                $date = Carbon::parse($e->exam_date);

                return [
                    'subject' => $e->subject_ar,
                    'paper' => $e->paper_ar,
                    'level' => $e->category === 'idaad' ? 'Idaad' : 'Thanawi',
                    'day' => $date->format('j'),
                    'month' => $date->format('M'),
                    'weekday' => $date->format('l'),
                    'time' => substr((string) $e->start_time, 0, 5),
                    'days_until' => (int) $today->diffInDays($date),
                ];
            })->all();
    }
}
