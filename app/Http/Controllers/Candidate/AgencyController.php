<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\EmployerProfile;
use App\Services\Candidate\AgencyService;

class AgencyController extends Controller
{
    protected $agencyService;

    public function __construct(AgencyService $agencyService)
    {
        $this->agencyService = $agencyService;
    }

    public function jobs(EmployerProfile $employerProfile)
    {
        return $this->agencyService->agencyJobs($employerProfile);
    }

    public function show(EmployerProfile $employerProfile)
    {
        return $this->agencyService->showAgency($employerProfile);
    }
}