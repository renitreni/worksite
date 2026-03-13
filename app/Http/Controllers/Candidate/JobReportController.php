<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;
use App\Services\Candidate\JobReportService;

class JobReportController extends Controller
{
    protected $jobReportService;

    public function __construct(JobReportService $jobReportService)
    {
        $this->jobReportService = $jobReportService;
    }

    public function create(JobPost $job)
    {
        return $this->jobReportService->showReportForm($job);
    }

    public function store(Request $request, JobPost $job)
    {
        return $this->jobReportService->submitReport($request, $job);
    }
}