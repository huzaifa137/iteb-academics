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
        $firstCardNo = null;
        $lastCardNo = null;

        if ($request->filled('house_id') && $request->filled('year') && $request->filled('type')) {
            $selectedHouse = House::find($request->house_id);

            if ($selectedHouse) {
                $students = $this->studentsQuery($selectedHouse, $request->year, $request->type)
                    ->orderBy('Student_ID', 'asc')
                    ->get();

                if ($students->isNotEmpty()) {
                    $firstCardNo = $this->cardNumberFor(
                        $students->first()->Student_ID, $request->year, $request->type,
                        (int) $request->input('card_start', 1), max(1, (int) $request->input('card_step', 1))
                    );
                    $lastCardNo = $firstCardNo + (($students->count() - 1) * max(1, (int) $request->input('card_step', 1)));
                }
            }
        }

        return view('student.examination-cards', compact(
            'houses', 'years', 'students', 'selectedHouse', 'firstCardNo', 'lastCardNo'
        ));
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

        // Card numbers run continuously for the whole year + category (all schools together),
        // in Student_ID order: school IT-001's students first, then IT-002, and so on.
        // This school therefore starts right after the last card of the previous school.
        // card_start = number of the very first card of the year/category (default 1),
        // card_step  = gap between consecutive cards (default 1).
        $cardStart = (int) $request->input('card_start', 1);
        $cardStep = max(1, (int) $request->input('card_step', 1));
        $firstCardNo = $this->cardNumberFor($students->first()->Student_ID, $request->year, $type, $cardStart, $cardStep);

        // One resized copy of the default photo is shared by every student without a photo.
        $defaultPhoto = null;

        $cards = $students->values()->map(function ($student, $i) use ($firstCardNo, $cardStep, &$defaultPhoto) {
            $photo = $this->photoDataUri($student->Student_ID);
            if ($photo === null) {
                $defaultPhoto = $defaultPhoto ?? $this->defaultPhotoDataUri();
                $photo = $defaultPhoto;
            }

            return [
                'student'  => $student,
                'photo'    => $photo,
                'cardNo'   => $firstCardNo + ($i * $cardStep),
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

    /**
     * Card number of a school's first student: cardStart + (number of students of the same
     * year + category whose Student_ID sorts before it) * step.
     */
    private function cardNumberFor(string $firstStudentId, $year, string $type, int $cardStart, int $cardStep): int
    {
        $before = StudentBasic::where('Student_ID', 'LIKE', '%-' . $year)
            ->where('Student_ID', 'LIKE', $type === 'idaad' ? '%-ID-%' : '%-TH-%')
            ->where('Student_ID', '<', $firstStudentId)
            ->count();

        return $cardStart + ($before * $cardStep);
    }

    /**
     * Small, cached JPEG of the student's photo as a data URI. Raw camera photos are several MB each,
     * which is what made the browser run out of memory when building the PDF.
     * Returns null when the student has no photo.
     */
    private function photoDataUri(string $studentId): ?string
    {
        $photo = public_path('assets/student_photos/' . $studentId . '.jpg');

        return File::exists($photo) ? $this->smallJpegDataUri($photo, 'student-' . $studentId) : null;
    }

    private function defaultPhotoDataUri(): ?string
    {
        $photo = public_path('assets/images/default-user.jpg');

        return File::exists($photo) ? $this->smallJpegDataUri($photo, 'default-user') : null;
    }

    /** Max 300 x 300 px (card photo is 26 x 23 mm, so this is still ~290 dpi). */
    private function smallJpegDataUri(string $path, string $cacheKey): ?string
    {
        $cache = storage_path('app/exam-card-photos/' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $cacheKey) . '.jpg');

        if (File::exists($cache) && File::lastModified($cache) >= File::lastModified($path)) {
            return 'data:image/jpeg;base64,' . base64_encode(File::get($cache));
        }

        try {
            if (function_exists('imagecreatefromstring')) {
                $src = @imagecreatefromstring(File::get($path));

                if ($src !== false) {
                    $w = imagesx($src);
                    $h = imagesy($src);
                    $ratio = min(1, 300 / max($w, $h));
                    $nw = max(1, (int) round($w * $ratio));
                    $nh = max(1, (int) round($h * $ratio));

                    $dst = imagecreatetruecolor($nw, $nh);
                    imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

                    File::ensureDirectoryExists(dirname($cache));
                    imagejpeg($dst, $cache, 85);
                    imagedestroy($src);
                    imagedestroy($dst);

                    return 'data:image/jpeg;base64,' . base64_encode(File::get($cache));
                }
            }
        } catch (\Throwable $e) {
            // fall through to the original file
        }

        return $this->imageDataUri($path);
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
