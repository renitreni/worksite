<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Services\Candidate\AppliedJobsService;

class AppliedJobsController extends Controller
{
    protected $appliedJobsService;

    public function __construct(AppliedJobsService $appliedJobsService)
    {
        $this->appliedJobsService = $appliedJobsService;
    }

    public function index()
    {
        return $this->appliedJobsService->getAppliedJobs();
    }
}