<?php

namespace App\Services\Candidate;

use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;
use App\Models\SavedJob;
use App\Models\AgencyFollow;

class CandidateDashboardService
{
    public function getDashboard()
    {
        $userId = Auth::id();

        // Stats
        $appliedJobsCount = JobApplication::where('candidate_id', $userId)->count();

        $savedJobsCount = SavedJob::where('user_id', $userId)->count();

        $followingCount = AgencyFollow::where('user_id', $userId)->count();

        // Recently applied jobs
        $recentApplications = JobApplication::with('jobPost.employerProfile')
            ->where('candidate_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Shortlisted
        $shortlistedCount = JobApplication::where('candidate_id', $userId)
            ->where('status', 'shortlisted')
            ->count();

        // Saved jobs preview
        $savedPreview = SavedJob::with('jobPost')
            ->where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();

        // Followed agencies
        $followedAgencies = AgencyFollow::with('employerProfile')
            ->where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();

        // Activities
        $activities = JobApplication::with('jobPost')
            ->where('candidate_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('candidate.contents.dashboard', compact(
            'appliedJobsCount',
            'savedJobsCount',
            'followingCount',
            'recentApplications',
            'shortlistedCount',
            'savedPreview',
            'followedAgencies',
            'activities'
        ));
    }
}