<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

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
}