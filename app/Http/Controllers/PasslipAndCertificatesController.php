<?php

namespace App\Http\Controllers;
use App\Models\House;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

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


    public function downloadIndividualPasslip(Request $request)
    {

        $studentId = $request->student_id;

        return view('template', compact('studentId'));
    }
}
