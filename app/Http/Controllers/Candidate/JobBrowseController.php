<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Services\Candidate\JobBrowseService;

class JobBrowseController extends Controller
{
    protected $jobBrowseService;

    public function __construct(JobBrowseService $jobBrowseService)
    {
        $this->jobBrowseService = $jobBrowseService;
    }

    public function index(Request $request)
    {
        return $this->jobBrowseService->browseJobs($request);
    }

    public function show(JobPost $job)
    {
        return $this->jobBrowseService->showJob($job);
    }
}