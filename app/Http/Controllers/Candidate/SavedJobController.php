<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Services\Candidate\SavedJobService;

class SavedJobController extends Controller
{
    protected $savedJobService;

    public function __construct(SavedJobService $savedJobService)
    {
        $this->savedJobService = $savedJobService;
    }

    public function toggle(JobPost $job)
    {
        return $this->savedJobService->toggle($job);
    }

    public function index()
    {
        return $this->savedJobService->listSavedJobs();
    }

    public function destroy($id)
    {
        return $this->savedJobService->removeSavedJob($id);
    }
}