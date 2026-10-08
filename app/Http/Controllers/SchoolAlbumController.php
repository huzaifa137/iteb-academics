<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\StudentBasic;
use App\Models\StudentRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

/**
 * School Album: a printable photo album of a school's students, 15 per A4 landscape page
 * (3 columns x 5 rows). Used from two portals:
 *   - admin  : can generate it for ANY school  (StudentAuth routes)
 *   - school : can only generate it for ITS OWN school (SchoolAuth routes, school from session)
 */
class SchoolAlbumController extends Controller
{
    public const PER_PAGE = 15;

    /* ------------------------------------------------------------------ *
     |  ADMIN PORTAL
     * ------------------------------------------------------------------ */

    public function adminIndex(Request $request)
    {
        $house = $request->filled('house_id') ? House::find($request->house_id) : null;

        return $this->indexView('admin', $request, $house);
    }

    public function adminPrint(Request $request)
    {
        $request->validate([
            'house_id' => 'required|exists:houses,ID',
            'year'     => 'required|digits:4',
            'type'     => 'required|in:idaad,thanawi',
            'source'   => 'nullable|in:approved,registered',
        ]);

        return $this->printView($request, House::findOrFail($request->house_id));
    }

    /* ------------------------------------------------------------------ *
     |  SCHOOL PORTAL (school id always comes from the session)
     * ------------------------------------------------------------------ */

    public function schoolIndex(Request $request)
    {
        $house = House::find(session('LoggedSchool'));

        if (!$house) {
            return redirect()->route('School.login')->with('error', 'Please login first');
        }

        return $this->indexView('school', $request, $house);
    }

    public function schoolPrint(Request $request)
    {
        $house = House::find(session('LoggedSchool'));

        if (!$house) {
            return redirect()->route('School.login')->with('error', 'Please login first');
        }

        $request->validate([
            'year'   => 'required|digits:4',
            'type'   => 'required|in:idaad,thanawi',
            'source' => 'nullable|in:approved,registered',
        ]);

        return $this->printView($request, $house);
    }

    /* ------------------------------------------------------------------ *
     |  SHARED
     * ------------------------------------------------------------------ */

    private function indexView(string $portal, Request $request, ?House $house)
    {
        $records = collect();

        if ($house && $request->filled('year') && $request->filled('type')) {
            $records = $this->records($house, $request->year, $request->type, $request->input('source', 'approved'));
        }

        return view('student.school-album', [
            'portal'  => $portal,
            'houses'  => $portal === 'admin' ? House::orderBy('House')->get() : collect(),
            'years'   => Helper::academicYears(),
            'house'   => $house,
            'records' => $records,
            'formUrl'  => $portal === 'admin' ? route('students.school-album') : route('school.album'),
            'printUrl' => $portal === 'admin' ? route('students.school-album.print') : route('school.album.print'),
        ]);
    }

    private function printView(Request $request, House $house)
    {
        $type = $request->type;
        $year = $request->year;
        $source = $request->input('source', 'approved');

        $records = $this->records($house, $year, $type, $source);

        if ($records->isEmpty()) {
            return back()->with('error', 'No students found for the selected school, year and category.');
        }

        $records = $records->map(function ($r) {
            $r['photo'] = $this->photoDataUri($r['id']);
            return $r;
        });

        return view('student.school-album-print', [
            'house'    => $house,
            'records'  => $records,
            'year'     => $year,
            'stage'    => $type === 'idaad' ? 'الإعدادية' : 'الثانوية',
            'perPage'  => self::PER_PAGE,
            'logoData' => $this->imageDataUri(public_path('assets/images/brand/uplogolight.png')),
            'fileName' => 'school_album_' . $house->Number . '_' . $type . '_' . $year . '_'
                . str_replace(' ', '_', $house->House) . '.pdf',
        ]);
    }

    /**
     * Normalised student rows: id, name, dob, pob, nationality, district.
     *  - approved   : students already in students_basic (approved by the admin)
     *  - registered : everything the school has registered, including pending ones
     */
    private function records(House $house, $year, string $type, string $source)
    {
        if ($source === 'registered') {
            return StudentRegistration::where('school_id', $house->ID)
                ->where('category', $type === 'idaad' ? 'ID' : 'TH')
                ->where('admission_year', $year)
                ->orderBy('student_id')
                ->get()
                ->map(fn($s) => $this->row($s->student_id, $s->student_name, $s->date_of_birth, $s->birth_place, $s->student_nationality, $s->district));
        }

        return StudentBasic::where('House', $house->House)
            ->where('Student_ID', 'LIKE', '%-' . $year)
            ->where('Student_ID', 'LIKE', $type === 'idaad' ? '%-ID-%' : '%-TH-%')
            ->orderBy('Student_ID')
            ->get()
            ->map(fn($s) => $this->row($s->Student_ID, $s->Student_Name, $s->Date_of_Birth, $s->Birth_Place, $s->StudentsNationality, $s->District));
    }

    private function row($id, $name, $dob, $pob, $nationality, $district): array
    {
        try {
            if ($dob && preg_match('#^\d{1,2}/\d{1,2}/\d{4}$#', (string) $dob)) {
                $dobText = Carbon::createFromFormat('d/m/Y', $dob)->format('F d, Y');   // 27/03/2002
            } else {
                $dobText = $dob ? Carbon::parse($dob)->format('F d, Y') : '';
            }
        } catch (\Throwable $e) {
            $dobText = (string) $dob;
        }

        return [
            'id'          => $id,
            'name'        => mb_strtoupper((string) $name),
            'dob'         => $dobText,
            'pob'         => mb_strtoupper((string) $pob),
            'nationality' => mb_strtoupper((string) $nationality),
            'district'    => mb_strtoupper((string) $district),
        ];
    }

    /**
     * Student photo as a small, pre-cropped data URI (22:25 portrait box). Resizing keeps the
     * page light when a school has hundreds of students, and embedding keeps html2canvas happy.
     */
    private function photoDataUri(string $studentId): ?string
    {
        $path = public_path('assets/student_photos/' . $studentId . '.jpg');

        if (!File::exists($path)) {
            $path = public_path('assets/images/default-user.jpg');
        }

        if (!File::exists($path)) {
            return null;
        }

        if (function_exists('imagecreatefromstring')) {
            $src = @imagecreatefromstring(File::get($path));

            if ($src) {
                $w = imagesx($src);
                $h = imagesy($src);
                $ratio = 22 / 25;                      // target width / height

                if ($w / $h > $ratio) {                // too wide: crop the sides, keep centre
                    $cw = (int) round($h * $ratio);
                    $ch = $h;
                    $cx = (int) floor(($w - $cw) / 2);
                    $cy = 0;
                } else {                               // too tall: crop from the top (keeps the face)
                    $cw = $w;
                    $ch = (int) round($w / $ratio);
                    $cx = 0;
                    $cy = (int) floor(($h - $ch) * 0.1);
                }

                $dst = imagecreatetruecolor(264, 300);
                imagecopyresampled($dst, $src, 0, 0, $cx, $cy, 264, 300, $cw, $ch);

                ob_start();
                imagejpeg($dst, null, 85);
                $jpeg = ob_get_clean();

                imagedestroy($src);
                imagedestroy($dst);

                return 'data:image/jpeg;base64,' . base64_encode($jpeg);
            }
        }

        return $this->imageDataUri($path);
    }

    private function imageDataUri(string $path): ?string
    {
        if (!File::exists($path)) {
            return null;
        }

        return 'data:' . (File::mimeType($path) ?: 'image/png') . ';base64,' . base64_encode(File::get($path));
    }
}
