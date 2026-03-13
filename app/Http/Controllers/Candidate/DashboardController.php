<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Services\Candidate\CandidateDashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(CandidateDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        return $this->dashboardService->getDashboard();
    }
}