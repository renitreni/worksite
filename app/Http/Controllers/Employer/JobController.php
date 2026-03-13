<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\Industry;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use App\Services\Employer\EmployerJobService;
use App\Services\Employer\EmployerTaxonomyService;


class JobController extends Controller
{
    public function __construct(
        private EmployerJobService $jobService
    ) {
    }

    public function index()
    {
        $jobs = $this->jobService->getActiveJobs();

        return view('employer.contents.job-postings.active', compact('jobs'));
    }

    public function closed()
    {
        $jobs = $this->jobService->getClosedJobs();

        return view('employer.contents.job-postings.closed', compact('jobs'));
    }

    public function create()
    {
        $data = $this->jobService->getCreatePageData();

        if ($data['limitReached'] ?? false) {

            return redirect()
                ->route('employer.job-postings.index')
                ->with('limit_modal', true)
                ->with('limit_data', $data['limitData']);
        }

        return view('employer.contents.job-postings.create', $data);
    }

    public function store(Request $request)
    {
        $this->jobService->storeJob($request);

        return redirect()
            ->route('employer.job-postings.index')
            ->with('success', 'Job posted successfully!');
    }

    public function show(JobPost $job)
    {
        $this->jobService->authorizeOwner($job);

        return view('employer.contents.job-postings.show', compact('job'));
    }

    public function edit(JobPost $job)
    {
        $data = $this->jobService->getEditPageData($job);

        return view('employer.contents.job-postings.edit', $data);
    }

    public function update(Request $request, JobPost $job)
    {
        $this->jobService->updateJob($request, $job);

        return redirect()
            ->route('employer.job-postings.show', $job->id)
            ->with('success', 'Job updated successfully!');
    }

    public function destroy(JobPost $job)
    {
        $this->jobService->closeJob($job);

        return redirect()
            ->route('employer.job-postings.index')
            ->with('info', 'Job closed successfully.');
    }

    public function reopen(JobPost $job)
    {
        $this->jobService->reopenJob($job);

        return redirect()
            ->route('employer.job-postings.index')
            ->with('success', 'Job reopened successfully.');
    }

    public function skillsByIndustry(Industry $industry)
    {
        $skills = $this->jobService->skillsByIndustry($industry);

        return response()->json($skills);
    }
}