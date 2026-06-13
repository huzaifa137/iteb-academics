<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\SchoolRecognitionCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolRecognitionCertificateController extends Controller
{
    // ─────────────────────────────────────────────
    //  ADMIN SIDE
    // ─────────────────────────────────────────────

    /**
     * List all issued recognition certificates (admin view).
     */
    public function index()
    {
        $certificates = SchoolRecognitionCertificate::with('house')
            ->orderByDesc('issued_date')
            ->get();

        $houses = House::orderBy('House')->get();

        return view('Certificates.school-recognition.index', compact('certificates', 'houses'));
    }

    /**
     * Show the form to issue a new certificate for a school.
     */
    public function create()
    {
        $houses = House::orderBy('House')->get();

        return view('Certificates.school-recognition.create', compact('houses'));
    }

    /**
     * Store (issue) a new recognition certificate.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'house_number' => 'required|string|exists:houses,Number',
            'issued_date'  => 'required|date',
            'issued_by'    => 'nullable|string|max:150',
            'notes'        => 'nullable|string',
        ]);

        // Check if the school already has an active certificate
        $existing = SchoolRecognitionCertificate::where('house_number', $validated['house_number'])
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return back()->withErrors([
                'house_number' => 'This school already has an active recognition certificate (No. ' . $existing->certificate_number . '). Revoke it first before issuing a new one.',
            ])->withInput();
        }

        // Auto-generate certificate number: ITEB-RC-{house_number}-{year}
        $year = date('Y', strtotime($validated['issued_date']));
        $certNumber = 'ITEB-RC-' . strtoupper($validated['house_number']) . '-' . $year;

        SchoolRecognitionCertificate::create([
            'house_number'       => $validated['house_number'],
            'certificate_number' => $certNumber,
            'issued_date'        => $validated['issued_date'],
            'issued_by'          => $validated['issued_by'] ?? 'Executive Secretary (ITEBU)',
            'status'             => 'active',
            'notes'              => $validated['notes'] ?? null,
        ]);

        return redirect()->route('school.recognition.index')
            ->with('success', 'Recognition certificate issued successfully for school ' . $validated['house_number'] . '.');
    }

    /**
     * Preview / view a single certificate (admin).
     */
    public function show($id)
    {
        $cert = SchoolRecognitionCertificate::with('house')->findOrFail($id);
        return $this->renderCertificate($cert);
    }

    /**
     * Revoke a certificate.
     */
    public function revoke($id)
    {
        $cert = SchoolRecognitionCertificate::findOrFail($id);
        $cert->update(['status' => 'revoked']);

        return redirect()->route('school.recognition.index')
            ->with('success', 'Certificate No. ' . $cert->certificate_number . ' has been revoked.');
    }

    /**
     * Delete a certificate record entirely.
     */
    public function destroy($id)
    {
        $cert = SchoolRecognitionCertificate::findOrFail($id);
        $cert->delete();

        return redirect()->route('school.recognition.index')
            ->with('success', 'Certificate record deleted.');
    }

    // ─────────────────────────────────────────────
    //  SCHOOL PORTAL SIDE
    // ─────────────────────────────────────────────

    /**
     * School portal: view & download their own recognition certificate.
     */
    public function schoolView()
    {
        $schoolNumber = session('LoggedSchoolCode');

        if (!$schoolNumber) {
            abort(403, 'Unauthorized');
        }

        $cert = SchoolRecognitionCertificate::with('house')
            ->where('house_number', $schoolNumber)
            ->where('status', 'active')
            ->latest('issued_date')
            ->first();

        if (!$cert) {
            return view('Certificates.school-recognition.school-not-issued', [
                'schoolNumber' => $schoolNumber,
                'schoolName'   => session('LoggedSchoolName'),
            ]);
        }

        return $this->renderCertificate($cert);
    }

    // ─────────────────────────────────────────────
    //  SHARED RENDERER
    // ─────────────────────────────────────────────

    /**
     * Build the images needed and return the certificate Blade view.
     */
    private function renderCertificate(SchoolRecognitionCertificate $cert)
    {
        $bismillahPath   = public_path('assets/basmallah.png');
        $bismillahBase64 = '';
        if (file_exists($bismillahPath)) {
            $bismillahBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($bismillahPath));
        }

        $house = $cert->house;

        // Arabic school name via Helper
        $schoolNameAr = $house->House_AR ?? $house->House;
        $schoolNameEn = $house->House;
        $schoolNumber = $house->Number;
        $location     = $house->Location ?? 'Uganda';

        return view('Certificates.school-recognition.certificate', compact(
            'cert',
            'house',
            'schoolNameAr',
            'schoolNameEn',
            'schoolNumber',
            'location',
            'bismillahBase64',
        ));
    }
}