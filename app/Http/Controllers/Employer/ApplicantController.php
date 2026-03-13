<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use App\Services\Employer\ApplicantService;
use App\Services\Employer\ApplicantStatusService;
use App\Services\Employer\ApplicantExportService;
use App\Services\Employer\EmployerCandidateViewService;
use Illuminate\Support\Facades\Auth;


class ApplicantController extends Controller
{
    public function __construct(
        private ApplicantService $service,
        private ApplicantStatusService $statusService,
        private ApplicantExportService $exportService,
        private EmployerCandidateViewService $viewService

    ) {
    }

    public function index(Request $request)
    {
        return $this->service->index($request);
    }

    public function show(JobApplication $application)
    {
        $profile = Auth::user()->employerProfile;

        $candidateProfileId = $application->candidate_profile_id
            ?? $application->candidateProfile?->id;

        if ($candidateProfileId) {
            $this->viewService->recordApplicationView(
                $profile,
                $application->id,
                $candidateProfileId
            );
        }

        return $this->service->show($application);
    }

    public function shortlist(JobApplication $application)
    {
        return $this->statusService->shortlist($application);
    }

    public function interview(JobApplication $application)
    {
        return $this->statusService->interview($application);
    }

    public function hire(JobApplication $application)
    {
        return $this->statusService->hire($application);
    }

    public function reject(JobApplication $application)
    {
        return $this->statusService->reject($application);
    }

    public function export(Request $request)
    {
        return $this->exportService->exportCsv($request);
    }
}