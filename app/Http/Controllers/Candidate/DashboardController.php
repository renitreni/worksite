<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;
use App\Models\SavedJob;
use App\Models\AgencyFollow;
use App\Models\JobPost;

class DashboardController extends Controller
{
    public function index()
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

        $savedPreview = SavedJob::with('jobPost')
            ->where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();

        $followedAgencies = AgencyFollow::with('employerProfile')
            ->where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();

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
            'activities',

        ));
    }
}