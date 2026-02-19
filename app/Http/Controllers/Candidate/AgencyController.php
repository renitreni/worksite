<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\EmployerProfile;
use App\Models\JobPost;

class AgencyController extends Controller
{
    public function jobs(EmployerProfile $employerProfile)
    {
        $jobs = $employerProfile->jobPosts()
            ->where('status', 'open')
            ->latest()
            ->paginate(9);
        $featuredJobs = JobPost::query()
            ->with(['employerProfile:id,company_name'])
            ->where('status', 'open')
            ->orderByDesc('posted_at')
            ->orderByDesc('created_at')
            ->take(9)
            ->get();

        return view('mainpage.job-details-page.partials.other-jobs', [
            'agency' => $employerProfile,
            'jobs' => $jobs,
            'featuredJobs' => $featuredJobs,
        ]);
    }
}
