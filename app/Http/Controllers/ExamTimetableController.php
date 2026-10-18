<?php

namespace App\Http\Controllers;

use App\Models\ExamTimetable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ExamTimetableController extends Controller
{
    /** Timetable management page (list + filters + add/edit/delete). */
    public function index(Request $request)
    {
        $years = Helper::academicYears();

        $query = ExamTimetable::query();

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $entries = $query->orderBy('exam_date')->orderBy('start_time')->orderBy('category')->get();

        return view('student.timetable', compact('years', 'entries'));
    }

    /** JSON: timetable entries for a year + category (used by the Attendance Sheet page). */
    public function options(Request $request)
    {
        $request->validate([
            'year'     => 'required|string',
            'category' => 'required|in:idaad,thanawi',
        ]);

        $entries = ExamTimetable::where('year', $request->year)
            ->where('category', $request->category)
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get()
            ->map(fn ($e) => $e->toPayload())
            ->values();

        return response()->json($entries);
    }

    public function store(Request $request)
    {
        $entry = ExamTimetable::create($this->validated($request));

        return response()->json([
            'message' => 'Timetable entry added successfully.',
            'entry'   => $entry->toPayload(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $entry = ExamTimetable::findOrFail($id);
        $entry->update($this->validated($request));

        return response()->json([
            'message' => 'Timetable entry updated successfully.',
            'entry'   => $entry->fresh()->toPayload(),
        ]);
    }

    public function destroy($id)
    {
        ExamTimetable::findOrFail($id)->delete();

        return response()->json(['message' => 'Timetable entry deleted successfully.']);
    }

    /**
     * One-click import of the printed timetable (16–20 Nov 2026) for a chosen academic year.
     * Skips rows that already exist so it is safe to click twice.
     */
    public function importSample(Request $request)
    {
        $request->validate(['year' => 'required|string|max:50']);

        // [date, start, end, subject, paper]  — [idaad (ع)], [thanawi (ث)]
        $rows = [
            ['2026-11-16', '09:00', '10:30', 'حفظ', null,        'حفظ', null],
            ['2026-11-16', '11:00', '12:30', 'تفسير', null,       'تفسير', null],
            ['2026-11-16', '14:00', '16:00', 'تجويد', 'تحريري',   'توحيد', null],
            ['2026-11-17', '09:00', '11:00', 'نحو وصرف', null,    'فقه الأحوال الشخصية', null],
            ['2026-11-17', '14:00', '16:00', 'توحيد', null,       'أصول التفسير', null],
            ['2026-11-18', '09:00', '11:00', 'فقه العبادات', null, 'نحو وصرف', null],
            ['2026-11-18', '14:00', '16:00', 'إملاء وخط', null,   'أصول الحديث', null],
            ['2026-11-19', '09:00', '11:00', 'تاريخ إسلامي', null, 'أديان وفرق', null],
            ['2026-11-19', '14:00', '16:00', 'أدب ونصوص', null,   'أصول الفقه', null],
            ['2026-11-20', '09:00', '11:00', 'حديث', null,        'فرائض', null],
            ['2026-11-20', '14:00', '16:00', 'إنشاء ومطالعة', null, 'أدب ونصوص', null],
        ];

        $added = 0;

        foreach ($rows as [$date, $start, $end, $idSubject, $idPaper, $thSubject, $thPaper]) {
            foreach ([['idaad', $idSubject, $idPaper], ['thanawi', $thSubject, $thPaper]] as [$cat, $subject, $paper]) {
                $entry = ExamTimetable::firstOrCreate(
                    [
                        'year'       => $request->year,
                        'category'   => $cat,
                        'exam_date'  => $date,
                        'start_time' => $start . ':00',
                        'subject_ar' => $subject,
                    ],
                    [
                        'class_level' => '3',
                        'paper_ar'    => $paper,
                        'end_time'    => $end . ':00',
                    ]
                );

                if ($entry->wasRecentlyCreated) {
                    $added++;
                }
            }
        }

        return response()->json(['message' => "{$added} timetable entries imported."]);
    }

    /** Printable timetable (grid like the paper one), turned into a PDF in the browser. */
    public function export(Request $request)
    {
        $request->validate([
            'year'     => 'required|string|max:50',
            'category' => 'nullable|in:idaad,thanawi',
        ]);

        $query = ExamTimetable::where('year', $request->year);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $entries = $query->orderBy('exam_date')->orderBy('start_time')->get();

        if ($entries->isEmpty()) {
            return redirect()
                ->route('students.timetable', $request->only(['year', 'category']))
                ->with('error', 'No timetable entries found for the selected year/category.');
        }

        $logoPath = public_path('asset/images/logo.png');
        $logoData = File::exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(File::get($logoPath))
            : null;

        $category = $request->category;
        $stage = $category === 'idaad' ? 'الإعدادية' : ($category === 'thanawi' ? 'الثانوية' : 'الإعدادية والثانوية');

        $fileName = 'exam_timetable_' . str_replace(' ', '_', $request->year) . ($category ? '_' . $category : '') . '.pdf';

        return view('student.timetable-print', [
            'year'     => $request->year,
            'stage'    => $stage,
            'dayRows'  => $this->buildGrid($entries),
            'logoData' => $logoData,
            'fileName' => $fileName,
        ]);
    }

    /**
     * Turn entries into one row per exam day with three time slots (morning 1, morning 2, afternoon).
     * A slot whose real times differ from the standard slot times gets its own time label,
     * like Monday on the paper timetable.
     */
    private function buildGrid($entries): array
    {
        $standard = ['A' => ['09:00', '11:00'], 'B' => ['11:00', '13:00'], 'C' => ['14:00', '16:00']];
        $fmt = fn ($hm) => Carbon::createFromFormat('H:i', $hm)->format('h:i a');
        $toAr = fn ($v) => Helper::toArabicNumberDate($v);

        $rows = [];

        foreach ($entries->groupBy(fn ($e) => $e->exam_date->format('Y-m-d')) as $dayEntries) {
            $first = $dayEntries->first();

            $slots = ['A' => [], 'B' => [], 'C' => []];

            foreach ($dayEntries as $e) {
                $mins = ((int) substr($e->start_hm, 0, 2)) * 60 + (int) substr($e->start_hm, 3, 2);
                $key = $mins < 660 ? 'A' : ($mins < 840 ? 'B' : 'C');
                $slots[$key][] = $e;
            }

            $grid = [];

            foreach ($slots as $key => $list) {
                $byRange = [];

                // Idaad (ع) first, then Thanawi (ث), like the paper timetable
                usort($list, fn ($a, $b) => [$a->category === 'idaad' ? 0 : 1, $a->id] <=> [$b->category === 'idaad' ? 0 : 1, $b->id]);

                foreach ($list as $e) {
                    $range = $e->start_hm . '|' . $e->end_hm;
                    $byRange[$range]['start'] = $e->start_hm;
                    $byRange[$range]['end'] = $e->end_hm;

                    $line = $e->subject_ar
                        . ($e->paper_ar ? ' (' . $e->paper_ar . ')' : '')
                        . ' (' . ($e->class_level ? $toAr($e->class_level) . '/' : '')
                        . ($e->category === 'idaad' ? 'ع' : 'ث') . ')';

                    $byRange[$range]['lines'][] = $line;
                }

                $groups = [];
                foreach ($byRange as $g) {
                    $isStandard = $g['start'] === $standard[$key][0] && $g['end'] === $standard[$key][1];
                    $groups[] = [
                        'time'  => $isStandard ? null : [$fmt($g['start']), $fmt($g['end'])],
                        'lines' => $g['lines'],
                    ];
                }

                $grid[$key] = $groups;
            }

            $rows[] = [
                'dayAr' => 'يوم ' . ExamTimetable::DAYS_AR[$first->exam_date->dayOfWeek],
                'date'  => Helper::toArabicNumberDateReversed($first->exam_date->format('d/m/Y')),
                'slots' => $grid,
            ];
        }

        return $rows;
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'year'        => 'required|string|max:50',
            'category'    => 'required|in:idaad,thanawi',
            'class_level' => 'nullable|string|max:20',
            'subject_ar'  => 'required|string|max:150',
            'paper_ar'    => 'nullable|string|max:150',
            'exam_date'   => 'required|date',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        $data['paper_ar'] = $data['paper_ar'] ?? null;
        $data['class_level'] = $data['class_level'] ?? null;

        return $data;
    }
}
