<?php

namespace App\Services\Employer;

use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\CandidateProfile;

class AnalyticsService
{
    public function getAnalytics($profile)
    {
        $employerProfileId = $profile->id;

        $applicationsQuery = JobApplication::whereHas(
            'jobPost',
            fn($q) => $q->where('employer_profile_id', $employerProfileId)
        );

        $activeJobs = JobPost::where('employer_profile_id', $employerProfileId)
            ->where('status', 'open')
            ->count();

        $totalApplications = (clone $applicationsQuery)->count();

        $hiresThisMonth = (clone $applicationsQuery)
            ->where('status', 'hired')
            ->whereMonth('updated_at', now()->month)
            ->count();

        $applicationsPerJob = JobPost::where('employer_profile_id', $employerProfileId)
            ->withCount('applications')
            ->get(['id','title']);

        $statusDistribution = (clone $applicationsQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total','status')
            ->toArray();

        $hiresPerMonth = (clone $applicationsQuery)
            ->where('status','hired')
            ->selectRaw('MONTH(updated_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total','month')
            ->toArray();

        $hiresPerMonthData = [];

        for ($m = 1; $m <= 12; $m++) {
            $hiresPerMonthData[] = $hiresPerMonth[$m] ?? 0;
        }

        $jobsStatus = JobPost::where('employer_profile_id',$employerProfileId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total','status')
            ->toArray();

        $funnel = [
            'Applied' => $statusDistribution['applied'] ?? 0,
            'Shortlisted' => $statusDistribution['shortlisted'] ?? 0,
            'Interview' => $statusDistribution['interview'] ?? 0,
            'Hired' => $statusDistribution['hired'] ?? 0,
        ];

        $applicantsByCategory = (clone $applicationsQuery)
            ->join('job_posts','job_posts.id','=','job_applications.job_post_id')
            ->selectRaw('job_posts.industry, COUNT(*) as total')
            ->groupBy('job_posts.industry')
            ->pluck('total','industry')
            ->toArray();

        $experienceLevels = CandidateProfile::whereHas(
            'applications.jobPost',
            fn($q) => $q->where('employer_profile_id',$employerProfileId)
        )
        ->selectRaw("
            CASE
                WHEN experience_years <= 2 THEN 'Entry'
                WHEN experience_years BETWEEN 3 AND 5 THEN 'Mid'
                ELSE 'Senior'
            END as level,
            COUNT(*) as total
        ")
        ->groupBy('level')
        ->pluck('total','level')
        ->toArray();

        return compact(
            'activeJobs',
            'totalApplications',
            'hiresThisMonth',
            'applicationsPerJob',
            'hiresPerMonthData',
            'jobsStatus',
            'statusDistribution',
            'funnel',
            'applicantsByCategory',
            'experienceLevels'
        );
    }
}