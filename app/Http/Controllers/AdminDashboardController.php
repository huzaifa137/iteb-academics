<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AdminDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * The ITEB administrator landing page (/student/dashboard).
 *
 * index() renders the page with the first payload embedded, so there is no loading flash.
 * data()  returns the same payload as JSON for the filters, the Refresh button and auto-refresh.
 */
class AdminDashboardController extends Controller
{
    public function index(Request $request, AdminDashboardService $dashboard)
    {
        $year = $request->query('year');
        $category = $request->query('category');

        $filters = $dashboard->filters($year, $category);
        $payload = $dashboard->build($year, $category);

        $admin = User::find(session('LoggedStudent'));
        $firstName = $admin?->name ? Str::before(trim($admin->name), ' ') : 'Administrator';

        return view('admin.dashboard', [
            'payload' => $payload,
            'years' => $filters['years'],
            'firstName' => $firstName,
            'dataUrl' => route('admin.dashboard.data'),
        ]);
    }

    public function data(Request $request, AdminDashboardService $dashboard): JsonResponse
    {
        try {
            return response()->json(
                $dashboard->build(
                    $request->query('year'),
                    $request->query('category'),
                    $request->boolean('fresh')
                )
            );
        } catch (\Throwable $e) {
            Log::error('Admin dashboard data failed: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['message' => 'Unable to load dashboard data.'], 500);
        }
    }
}
