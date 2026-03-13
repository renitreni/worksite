<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;
use App\Services\Admin\AdminJobPostService;

class JobPostAdminController extends Controller
{
    public function __construct(
        private AdminJobPostService $jobPostService
    ) {}

    public function index(Request $request)
    {
        $data = $this->jobPostService->getJobPosts($request);

        return view(
            'adminpage.contents.job_posts.index',
            $data
        );
    }

    public function show(JobPost $jobPost)
    {
        $jobPost->load('employerProfile');

        return view(
            'adminpage.contents.job_posts.show',
            compact('jobPost')
        );
    }

    public function hold(Request $request, JobPost $jobPost)
    {
        $this->jobPostService->holdJobPost($request, $jobPost);

        return back()->with('success', 'Job post has been held.');
    }

    public function unhold(Request $request, JobPost $jobPost)
    {
        $this->jobPostService->unholdJobPost($request, $jobPost);

        return back()->with('success', 'Job post has been released (unheld).');
    }

    public function disable(Request $request, JobPost $jobPost)
    {
        $this->jobPostService->disableJobPost($request, $jobPost);

        return back()->with('success', 'Job post has been disabled.');
    }

    public function enable(Request $request, JobPost $jobPost)
    {
        $this->jobPostService->enableJobPost($request, $jobPost);

        return back()->with('success', 'Job post has been enabled.');
    }

    public function updateNotes(Request $request, JobPost $jobPost)
    {
        $this->jobPostService->updateNotes($request, $jobPost);

        return back()->with('success', 'Admin notes updated.');
    }
}