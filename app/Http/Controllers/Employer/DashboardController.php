<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Services\Employer\EmployerDashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private EmployerDashboardService $dashboardService
    ) {}

    public function index()
    {
        $data = $this->dashboardService->getDashboardData();

        return view('employer.contents.dashboard', $data);
    }
}