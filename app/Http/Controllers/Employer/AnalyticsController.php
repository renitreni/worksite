<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Services\Employer\AnalyticsService;
use App\Services\Employer\AnalyticsExportService;
use App\Services\Employer\EmployerAccessService;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    protected $analyticsService;
    protected $exportService;
    protected $accessService;

    public function __construct(
        AnalyticsService $analyticsService,
        AnalyticsExportService $exportService,
        EmployerAccessService $accessService
    ) {
        $this->analyticsService = $analyticsService;
        $this->exportService = $exportService;
        $this->accessService = $accessService;
    }

    public function index()
    {
        $profile = Auth::user()->employerProfile;

        $data = $this->analyticsService->getAnalytics($profile);

        $analyticsLevel = $this->accessService->analyticsLevelForProfile($profile);

        return view('employer.contents.analytics', array_merge(
            $data,
            ['analyticsLevel' => $analyticsLevel]
        ));
    }

    public function exportPdf()
    {
        $profile = Auth::user()->employerProfile;

        return $this->exportService->exportPdf($profile);
    }

    public function exportCsv()
    {
        $profile = Auth::user()->employerProfile;

        return $this->exportService->exportCsv($profile);
    }
}