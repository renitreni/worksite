<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\DashboardAnalyticsService;
class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService): View
    {
        return view('adminpage.contents.dashboard', [
            'dashboard' => $dashboardService->overview(),
        ]);
    }

    public function metrics(DashboardService $dashboardService)
{
    return response()
        ->json($dashboardService->overview())
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
}
public function analytics(Request $request, DashboardAnalyticsService $svc)
{
    $range = $request->query('range', '7d'); // 7d|30d|monthly
    return response()->json($svc->analytics($range))
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
}
}