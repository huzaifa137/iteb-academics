<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\StudentBasic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ExaminationCardController extends Controller
{
    /** Cards printed on one A4 page (2 columns x 3 rows). */
    public const CARDS_PER_PAGE = 6;

    /**
     * Filter / preview page: pick a school, year and category.
     */
    public function index(Request $request)
    {
        $houses = House::orderBy('House')->get();
        $years = Helper::academicYears();

        $students = collect();
        $selectedHouse = null;

        if ($request->filled('house_id') && $request->filled('year') && $request->filled('type')) {
            $selectedHouse = House::find($request->house_id);

            if ($selectedHouse) {
                $students = $this->studentsQuery($selectedHouse, $request->year, $request->type)
                    ->orderBy('Student_ID', 'asc')
                    ->get();
            }
        }

        return view('student.examination-cards', compact('houses', 'years', 'students', 'selectedHouse'));
    }

    /**
     * Print view: renders the cards as HTML (6 per A4 landscape page, 2 x 3). The browser shapes the Arabic
     * and html2pdf turns it into a PDF, exactly like the pass slip and attendance sheet.
     */
    public function print(Request $request)
    {
        $request->validate([
            'house_id' => 'required|exists:houses,ID',
            'year'     => 'required',
            'type'     => 'required|in:idaad,thanawi',
            'card_start' => 'nullable|integer|min:0',
            'card_step'  => 'nullable|integer|min:1',
        ]);

        $house = House::findOrFail($request->house_id);
        $type = $request->type;

        $students = $this->studentsQuery($house, $request->year, $type)
            ->orderBy('Student_ID', 'asc')
            ->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'No students found for the selected school, year and category.');
        }

        // Running card number (printed as 29,426 etc). Starts at card_start and goes up by
        // card_step for every student, in Student_ID order.
        $cardStart = (int) $request->input('card_start', 1);
        $cardStep = max(1, (int) $request->input('card_step', 1));

        // Photos and stamp are embedded as data URIs so html2canvas can always draw them.
        $cards = $students->values()->map(function ($student, $i) use ($cardStart, $cardStep) {
            return [
                'student'  => $student,
                'photo'    => $this->photoDataUri($student->Student_ID),
                'cardNo'   => $cardStart + ($i * $cardStep),
            ];
        });

        $data = [
            'house'       => $house,
            'cards'       => $cards,
            'year'        => $request->year,
            'type'        => $type,
            'stage'       => $type === 'idaad' ? 'الإعدادية' : 'الثانوية',
            'stageEn'     => $type === 'idaad' ? "O'LEVEL" : "A'LEVEL",
            'perPage'     => self::CARDS_PER_PAGE,
            'stampData'   => $this->imageDataUri(public_path('assets/images/brand/exam-card-stamp.png')),
        ];

        $data['fileName'] = 'examination_cards_' . $house->Number . '_' . $type . '_' . $request->year . '_'
            . str_replace(' ', '_', $house->House) . '.pdf';

        return view('student.examination-cards-print', $data);
    }

    /** Same school/year/category rule as the attendance sheet. */
    private function studentsQuery(House $house, $year, $type)
    {
        return StudentBasic::where('House', $house->House)
            ->where('Student_ID', 'LIKE', '%-' . $year)
            ->where('Student_ID', 'LIKE', $type === 'idaad' ? '%-ID-%' : '%-TH-%');
    }

    private function photoDataUri(string $studentId): ?string
    {
        $photo = public_path('assets/student_photos/' . $studentId . '.jpg');

        if (!File::exists($photo)) {
            $photo = public_path('assets/images/default-user.jpg');
        }

        return $this->imageDataUri($photo);
    }

    private function imageDataUri(string $path): ?string
    {
        if (!File::exists($path)) {
            return null;
        }

        $mime = File::mimeType($path) ?: 'image/png';

        return 'data:' . $mime . ';base64,' . base64_encode(File::get($path));
    }
}
