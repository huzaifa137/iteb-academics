<?php

namespace App\Http\Controllers;

use App\Models\ExamTimetable;
use App\Models\House;
use App\Models\StudentBasic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

/**
 * Marks Sheet (كشف أسماء الطلبة/الطالبات في الامتحان النهائي) — the sheet sent to each school
 * on which the markers record every student's mark per question plus the total.
 *
 * It can be generated for ONE school, or for ALL schools of a category (Idaad / Thanawi).
 */
class MarksSheetController extends Controller
{
    /** Filter / generate page. */
    public function index(Request $request)
    {
        $houses = House::orderBy('Number')->get();
        $years = Helper::academicYears();

        return view('student.marks-sheet', compact('houses', 'years'));
    }

    /**
     * Print-ready document. scope=school (house_id required) or scope=all (every school that
     * has students for the year/category).
     */
    public function print(Request $request)
    {
        $request->validate([
            'year'         => 'required|string',
            'type'         => 'required|in:idaad,thanawi',
            'scope'        => 'required|in:school,all',
            'house_id'     => 'required_if:scope,school|nullable|exists:houses,ID',
            'timetable_id' => 'nullable|integer',
            'questions'    => 'nullable|integer|min:1|max:20',
        ]);

        $back = fn ($message) => redirect()
            ->route('students.marks-sheet')
            ->withInput($request->all())
            ->with('error', $message);

        $type = $request->type;
        $year = $request->year;

        // Every student of this year/category in one query, then split per school.
        $query = StudentBasic::where('Student_ID', 'LIKE', '%-' . $year)
            ->where('Student_ID', 'LIKE', $type === 'idaad' ? '%-ID-%' : '%-TH-%');

        if ($request->scope === 'school') {
            $selected = House::findOrFail($request->house_id);
            $query->where('House', $selected->House);
        }

        $studentsByHouse = $query->orderBy('Student_ID', 'asc')->get()->groupBy('House');

        $housesQuery = House::orderBy('Number');
        if ($request->scope === 'school') {
            $housesQuery->where('ID', $request->house_id);
        }

        $houses = $housesQuery->get()
            ->filter(fn ($h) => $studentsByHouse->has($h->House))
            ->values();

        if ($houses->isEmpty()) {
            return $back('No students found for the selected school(s), year and category.');
        }

        $sheets = $houses->map(fn ($h) => [
            'house'    => $h,
            'students' => $studentsByHouse[$h->House]->values(),
        ])->all();

        // Exam details: typed values win; otherwise the chosen timetable entry; otherwise the
        // Quran recitation paper shown on the printed sample.
        $entry = $request->filled('timetable_id') ? ExamTimetable::find($request->timetable_id) : null;
        $hasExamDetails = $entry || $request->filled('subject');

        $subject = $request->filled('subject') ? $request->subject : ($entry->subject_ar ?? 'القرآن وعلومه');
        $paper = $hasExamDetails
            ? ($request->filled('paper') ? $request->paper : ($entry->paper_ar ?? ''))
            : 'التلاوة والتجويد';

        $rawDate = $request->filled('exam_date') ? $request->exam_date : ($entry ? $entry->exam_date->format('d/m/Y') : '');
        $startTime = $request->filled('start_time') ? $request->start_time : ($entry->start_hm ?? '');
        $endTime = $request->filled('end_time') ? $request->end_time : ($entry->end_hm ?? '');

        $logoPath = public_path('asset/images/logo.png');
        $logoData = File::exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(File::get($logoPath))
            : null;

        return view('student.marks-sheet-print', [
            'sheets'      => $sheets,
            'year'        => $year,
            'type'        => $type,
            'stage'       => $type === 'idaad' ? 'الإعدادية' : 'الثانوية',
            'subject'     => $subject,
            'paper'       => $paper,
            'examDate'    => $this->formatDate($rawDate),
            'startTime'   => $startTime,
            'endTime'     => $endTime,
            'questions'   => (int) ($request->input('questions') ?: 15),
            'logoData'    => $logoData,
            'schoolCount' => count($sheets),
        ]);
    }

    /** d/m/Y (or Y-m-d) -> Y-m-d, as shown on the printed sheet. Anything else is kept as typed. */
    private function formatDate(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        foreach (['d/m/Y', 'Y-m-d'] as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
                if ($date && $date->format($format) === $value) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // try the next format
            }
        }

        return $value;
    }
}
