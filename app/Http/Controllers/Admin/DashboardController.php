<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Services\Admin\DashboardService;
use App\Services\Admin\DashboardAnalyticsService;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
        private DashboardAnalyticsService $analyticsService
    ) {}

    public function index(): View
    {
        return view('adminpage.contents.dashboard', [
            'dashboard' => $this->dashboardService->overview(),
        ]);
    }

    public function metrics(): JsonResponse
    {
        return response()
            ->json($this->dashboardService->overview())
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function analytics(Request $request): JsonResponse
    {
        $range = $request->query('range', '7d'); // 7d|30d|monthly

        return response()
            ->json($this->analyticsService->analytics($range))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}