<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        $employerProfileId = Auth::user()->employerProfile->id;

        // ----------------------------
        // Summary cards
        // ----------------------------
        $activeJobs = JobPost::where('employer_profile_id', $employerProfileId)
                             ->where('status', 'open')
                             ->count();

        $totalApplications = JobApplication::whereHas('jobPost', fn($q) => $q->where('employer_profile_id', $employerProfileId))
                                           ->count();

        $hiresThisMonth = JobApplication::whereHas('jobPost', fn($q) => $q->where('employer_profile_id', $employerProfileId))
                                        ->where('status', 'hired')
                                        ->whereMonth('updated_at', now()->month)
                                        ->count();

        // ----------------------------
        // Applications per Job (Bar Chart)
        // ----------------------------
        $applicationsPerJob = JobPost::where('employer_profile_id', $employerProfileId)
                                     ->withCount('applications')
                                     ->get();

        // ----------------------------
        // Hires per Month (Line Chart)
        // ----------------------------
        $hiresPerMonth = JobApplication::whereHas('jobPost', fn($q) => $q->where('employer_profile_id', $employerProfileId))
                                       ->where('status', 'hired')
                                       ->selectRaw('MONTH(updated_at) as month, COUNT(*) as total')
                                       ->groupBy('month')
                                       ->pluck('total', 'month')
                                       ->toArray();

        // Make sure all months exist
        $hiresPerMonthData = [];
        for ($m = 1; $m <= 12; $m++) {
            $hiresPerMonthData[] = $hiresPerMonth[$m] ?? 0;
        }

        // ----------------------------
        // Active vs Closed Jobs (Doughnut Chart)
        // ----------------------------
        $jobsStatus = JobPost::where('employer_profile_id', $employerProfileId)
                             ->selectRaw('status, COUNT(*) as total')
                             ->groupBy('status')
                             ->pluck('total', 'status')
                             ->toArray();

        return view('employer.contents.analytics', compact(
            'activeJobs',
            'totalApplications',
            'hiresThisMonth',
            'applicationsPerJob',
            'hiresPerMonthData',
            'jobsStatus'
        ));
    }
}