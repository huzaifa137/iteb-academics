<?php

namespace App\Http\Controllers;

use App\Models\ExamTimetable;
use Illuminate\Http\Request;

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
