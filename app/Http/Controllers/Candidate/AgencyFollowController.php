<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\EmployerProfile;
use App\Services\Candidate\AgencyFollowService;

class AgencyFollowController extends Controller
{
    protected $agencyFollowService;

    public function __construct(AgencyFollowService $agencyFollowService)
    {
        $this->agencyFollowService = $agencyFollowService;
    }

    public function toggle(EmployerProfile $employerProfile)
    {
        return $this->agencyFollowService->toggleFollow($employerProfile);
    }
}