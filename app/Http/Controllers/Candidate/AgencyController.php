<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\EmployerProfile;
use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function show(EmployerProfile $employerProfile)
    {
        // ✅ Views count: authenticated only + once per day per user
        if (Auth::check()) {
            $userId = Auth::id();
            $today = now()->toDateString();

            $inserted = DB::table('employer_profile_views')->insertOrIgnore([
                'employer_profile_id' => $employerProfile->id,
                'user_id' => $userId,
                'viewed_on' => $today,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // insertOrIgnore returns number of inserted rows
            if ($inserted > 0) {
                $employerProfile->increment('total_profile_views');
            }
        }

        // jobs list (open only)
        $jobs = $employerProfile->jobPosts()
            ->where('status', 'open')
            ->orderByDesc('posted_at')
            ->orderByDesc('created_at')
            ->paginate(6);

        // count open jobs
        $openJobsCount = $employerProfile->jobPosts()
            ->where('status', 'open')
            ->count();

        // load relations
        $employerProfile->load([
            'user:id,name,email',
            'industry:id,name',
        ]);

        return view('mainpage.agency-details-page.agency.show', [
            'agency' => $employerProfile,
            'jobs' => $jobs,
            'openJobsCount' => $openJobsCount,
        ]);
    }
}