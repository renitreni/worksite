<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Services\Candidate\JobApplicationService;

class JobApplicationController extends Controller
{
    protected $applicationService;

    public function __construct(JobApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function store(Request $request, JobPost $job)
    {
        return $this->applicationService->apply($request, $job);
    }
}