<?php

namespace App\Http\Controllers;
use App\Models\House;
use App\Models\MasterData;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
class PasslipAndCertificatesController extends Controller
{
    public function generatePasslip()
    {
        $houses = House::select('Number', 'House', 'House_AR')->get();

        return view('Certificates.generate-certificate', compact('houses'));
    }

    public function fetchSchoolRecords(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer',
            'category' => 'required|in:TH,ID',
            'school_number' => 'required|string'
        ]);

        $year = $validated['year'];
        $category = $validated['category'];
        $schoolNumber = $validated['school_number'];

        $pattern = $schoolNumber . '-' . $category . '-%';

        $classAllocations = \DB::table('class_allocation')
            ->where('Student_ID', 'LIKE', $pattern)
            ->where('Student_ID', 'LIKE', '%-' . $year)
            ->get();

        $groupedByStudent = $classAllocations->groupBy('Student_ID');

        return view('Certificates.fetched-records', [
            'houses' => House::all(),
            'groupedByStudent' => $groupedByStudent,
            'totalRecords' => $classAllocations->count(),
            'totalStudents' => $groupedByStudent->count(),
            'filters' => [
                'year' => $year,
                'category' => $category,
                'school_number' => $schoolNumber
            ]
        ]);
    }

    public function generateCertifications()
    {
        $template = view('template')->render();

        Browsershot::html($template)
            // General PDF options
            ->showBackground()                 // Render CSS backgrounds
            ->format('A4')                     // Paper size
            ->landscape()                      // Landscape orientation
            ->margins(10, 10, 10, 10)          // Top, Right, Bottom, Left margins in mm

            // Header / Footer
            ->displayHeaderFooter()            // Enable header/footer
            ->headerHtml('<h4 style="text-align:center;">My School Header</h4>')
            ->footerHtml('<p style="text-align:center;">Page <span class="pageNumber"></span> of <span class="totalPages"></span></p>')

            // Viewport / browser options
            ->windowSize(1280, 800)            // Viewport size
            ->deviceScaleFactor(2)             // For Retina quality
            ->waitUntilNetworkIdle()           // Wait for JS / images to load

            // Save to file
            ->save(storage_path('app/reports/example.pdf'));
    }

    // public function downloadIndividualPasslip(Request $request)
    // {
    //     $studentId = $request->student_id;

    //     $parts = explode('-', $studentId);
    //     $schoolId = $parts[0] . '-' . $parts[1];
    //     $studentCategory = $parts[2] . '-' . $parts[3];

    //     $year = $parts[4];

    //     $categories = [
    //         ['title_en' => 'ARABIC LANGUAGE', 'title_ar' => 'اللغة العربية', 'codes' => ['AR-002', 'AR-001', 'AR-004', 'AR-003']],
    //         ['title_en' => 'FAITH & CIVILIZATION', 'title_ar' => 'العقيدة والحضارة', 'codes' => ['FC-005', 'FC-007', 'FC-006']],
    //         ['title_en' => 'JURISPRUDENCE & ITS SOURCES', 'title_ar' => 'الفقه وأصوله', 'codes' => ['JS-009', 'JS-010', 'JS-008']],
    //         ['title_en' => 'PROPHETIC TRADITIONS', 'title_ar' => 'الحديث الشريف', 'codes' => ['PT-012', 'PT-013']],
    //         ['title_en' => 'QURAN & ITS SCIENCES', 'title_ar' => 'القرآن وعلومه', 'codes' => ['QS-014', 'QS-015', 'QS-016']],
    //     ];

    //     $subjects = MasterData::where('md_master_code_id', config('constants.options.ThanawiPapers'))
    //         ->get()
    //         ->keyBy('md_code');

    //     // Render Blade template
    //     $template = view('template', compact(
    //         'studentId',
    //         'schoolId',
    //         'studentCategory',
    //         'year',
    //         'categories',
    //         'subjects'
    //     ))->render();

    //     // Generate PDF in memory
    //     $pdfContent = Browsershot::html($template)
    //         ->showBackground()
    //         ->format('A4')
    //         ->landscape()
    //         ->margins(0, 0, 0, 0)
    //         ->windowSize(1280, 800)
    //         ->deviceScaleFactor(2)
    //         ->scale(1)
    //         ->waitUntilNetworkIdle()
    //         ->pdf(); // return PDF content instead of saving

    //     // Return response as file download
    //     return Response::make($pdfContent, 200, [
    //         'Content-Type' => 'application/pdf',
    //         'Content-Disposition' => 'attachment; filename="passlip_' . $studentId . '.pdf"',
    //     ]);
    // }
    // public function downloadIndividualCertificate(Request $request)
    // {
    //     $studentId = $request->student_id;

    //     $parts = explode('-', $studentId);
    //     $schoolId = $parts[0] . '-' . $parts[1];
    //     $studentCategory = $parts[2] . '-' . $parts[3];
    //     $year = $parts[4];

    //     // Split category
    //     $categoryParts = explode('-', $studentCategory);
    //     $firstLetters = $categoryParts[0];

    //     if ($firstLetters == 'ID') {
    //         $level = "O'LEVEL";
    //         $ArLevel = 'الإعدادية';
    //     } else if ($firstLetters == 'TH') {
    //         $level = "A'LEVEL";
    //         $ArLevel = 'الثانوية';
    //     }

    //     $template = view('Certificates.certificate', compact(
    //         'studentId',
    //         'schoolId',
    //         'studentCategory',
    //         'year',
    //         'level',
    //         'ArLevel'
    //     ))->render();

    //     // Generate PDF in memory
    //     $pdfContent = Browsershot::html($template)
    //         ->showBackground()
    //         ->format('A4')
    //         ->landscape()
    //         ->margins(0, 0, 0, 0)
    //         ->windowSize(1280, 800)
    //         ->deviceScaleFactor(2)
    //         ->scale(1)
    //         ->waitUntilNetworkIdle()
    //         ->pdf(); // return PDF content instead of saving

    //     // Return response as file download
    //     return Response::make($pdfContent, 200, [
    //         'Content-Type' => 'application/pdf',
    //         'Content-Disposition' => 'attachment; filename="certificate_' . $studentId . '.pdf"',
    //     ]);

    //     // return view('Certificates.certificate', compact(['','','studentId','studentCategory','year','schoolId']));
    // }


    public function viewPasslip($studentId)
    {
        $parts = explode('-', $studentId);
        $schoolId = $parts[0] . '-' . $parts[1];
        $studentCategory = $parts[2] . '-' . $parts[3];
        $year = $parts[4];

        $categories = [
            ['title_en' => 'ARABIC LANGUAGE', 'title_ar' => 'اللغة العربية', 'codes' => ['AR-002', 'AR-001', 'AR-004', 'AR-003']],
            ['title_en' => 'FAITH & CIVILIZATION', 'title_ar' => 'العقيدة والحضارة', 'codes' => ['FC-005', 'FC-007', 'FC-006']],
            ['title_en' => 'JURISPRUDENCE & ITS SOURCES', 'title_ar' => 'الفقه وأصوله', 'codes' => ['JS-009', 'JS-010', 'JS-008']],
            ['title_en' => 'PROPHETIC TRADITIONS', 'title_ar' => 'الحديث الشريف', 'codes' => ['PT-012', 'PT-013']],
            ['title_en' => 'QURAN & ITS SCIENCES', 'title_ar' => 'القرآن وعلومه', 'codes' => ['QS-014', 'QS-015', 'QS-016']],
        ];

        $subjects = MasterData::where('md_master_code_id', config('constants.options.ThanawiPapers'))
            ->get()
            ->keyBy('md_code');

        return view('template', compact(
            'studentId',
            'schoolId',
            'studentCategory',
            'year',
            'categories',
            'subjects'
        ));
    }

    public function viewCertificate($studentId)
    {
        $parts = explode('-', $studentId);
        $schoolId = $parts[0] . '-' . $parts[1];
        $studentCategory = $parts[2] . '-' . $parts[3];
        $year = $parts[4];

        $categoryParts = explode('-', $studentCategory);
        $firstLetters = $categoryParts[0];

        if ($firstLetters == 'ID') {
            $level = "O'LEVEL";
            $ArLevel = 'الإعدادية';
        } else {
            $level = "A'LEVEL";
            $ArLevel = 'الثانوية';
        }

        return view('Certificates.certificate', compact(
            'studentId',
            'schoolId',
            'studentCategory',
            'year',
            'level',
            'ArLevel'
        ));
    }
}
