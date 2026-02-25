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
        // ✅ optional: block unapproved agencies
        $employerProfile->loadMissing('verification');
        if (!$employerProfile->verification || $employerProfile->verification->status !== 'approved') {
            abort(404);
        }

        $employerProfile->load(['user:id,email']);


        $jobs = $employerProfile->jobPosts()
            ->where('status', 'open')
            ->orderByDesc('posted_at')
            ->orderByDesc('created_at')
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
        // ✅ optional: block unapproved agencies
        $employerProfile->loadMissing('verification');
        if (!$employerProfile->verification || $employerProfile->verification->status !== 'approved') {
            abort(404);
        }

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

        $openJobsCount = $employerProfile->jobPosts()
            ->where('status', 'open')
            ->count();

        // ✅ load correct relations (pivot industries, not industry)
        $employerProfile->load([
            'user:id,name,email',
            'industries:id,name',     // ✅ MANY industries via pivot
            'verification:id,employer_profile_id,status',
        ]);

        return view('mainpage.agency-details-page.agency.show', [
            'agency' => $employerProfile,
            'jobs' => $jobs,
            'openJobsCount' => $openJobsCount,
        ]);
    }
}
