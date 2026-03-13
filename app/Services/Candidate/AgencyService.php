<?php

namespace App\Services\Candidate;

use App\Models\EmployerProfile;
use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgencyService
{
    private function validateAgency(EmployerProfile $employerProfile)
    {
        $employerProfile->loadMissing(['verification', 'user']);

        if (
            !$employerProfile->verification ||
            $employerProfile->verification->status !== 'approved' ||
            !$employerProfile->user ||
            $employerProfile->user->account_status !== 'active' ||
            $employerProfile->user->archived_at
        ) {
            abort(404);
        }
    }

    public function agencyJobs(EmployerProfile $employerProfile)
    {
        $this->validateAgency($employerProfile);

        $jobs = $employerProfile->jobPosts()
            ->where('status', 'open')
            ->where('is_disabled', false)
            ->orderByDesc('posted_at')
            ->orderByDesc('created_at')
            ->paginate(9);

        $featuredJobs = JobPost::query()
            ->with(['employerProfile:id,company_name'])
            ->where('status', 'open')
            ->where('is_disabled', false)
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

    public function showAgency(EmployerProfile $employerProfile)
    {
        $this->validateAgency($employerProfile);

        if (Auth::check()) {

            $inserted = DB::table('employer_profile_views')->insertOrIgnore([
                'employer_profile_id' => $employerProfile->id,
                'user_id' => Auth::id(),
                'viewed_on' => now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($inserted > 0) {
                $employerProfile->increment('total_profile_views');
            }
        }

        $jobs = $employerProfile->jobPosts()
            ->where('status', 'open')
            ->where('is_disabled', false)
            ->orderByDesc('posted_at')
            ->orderByDesc('created_at')
            ->paginate(6);

        $openJobsCount = $employerProfile->jobPosts()
            ->where('status', 'open')
            ->where('is_disabled', false)
            ->count();

        $employerProfile->load([
            'user:id,name,email',
            'industries:id,name',
            'verification:id,employer_profile_id,status',
        ]);

        $isFollowing = false;

        if (Auth::check()) {
            $isFollowing = $employerProfile->followers()
                ->where('user_id', Auth::id())
                ->exists();
        }

        $followersCount = $employerProfile->followers()->count();

        return view('mainpage.agency-details-page.agency.show', [
            'agency' => $employerProfile,
            'jobs' => $jobs,
            'openJobsCount' => $openJobsCount,
            'isFollowing' => $isFollowing,
            'followersCount' => $followersCount,
        ]);
    }
}