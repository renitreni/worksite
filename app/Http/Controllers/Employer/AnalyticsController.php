<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\CandidateProfile;
use Illuminate\Support\Facades\Auth;
use App\Services\EmployerAccessService;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyticsController extends Controller
{
    public function index(EmployerAccessService $accessService)
    {
        $profile = Auth::user()->employerProfile;
        $analyticsLevel = $accessService->analyticsLevelForProfile($profile);

        $employerProfileId = $profile->id;

        // ---------------------------------
        // Base Query (reuse everywhere)
        // ---------------------------------
        $applicationsQuery = JobApplication::whereHas(
            'jobPost',
            fn($q) => $q->where('employer_profile_id', $employerProfileId)
        );

        // ---------------------------------
        // Summary Cards
        // ---------------------------------
        $activeJobs = JobPost::where('employer_profile_id', $employerProfileId)
            ->where('status', 'open')
            ->count();

        $totalApplications = (clone $applicationsQuery)->count();

        $hiresThisMonth = (clone $applicationsQuery)
            ->where('status', 'hired')
            ->whereMonth('updated_at', now()->month)
            ->count();

        // ---------------------------------
        // BASIC ANALYTICS
        // ---------------------------------
        $applicationsPerJob = null;
        $statusDistribution = null;

        if (in_array($analyticsLevel, ['basic', 'advanced', 'enterprise'])) {

            $applicationsPerJob = JobPost::where('employer_profile_id', $employerProfileId)
                ->withCount('applications')
                ->get(['id', 'title']);

            // Application Status Distribution
            $statusDistribution = (clone $applicationsQuery)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();
        }

        // ---------------------------------
        // ADVANCED ANALYTICS
        // ---------------------------------
        $hiresPerMonthData = null;

        if (in_array($analyticsLevel, ['advanced', 'enterprise'])) {

            $hiresPerMonth = (clone $applicationsQuery)
                ->where('status', 'hired')
                ->selectRaw('MONTH(updated_at) as month, COUNT(*) as total')
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();

            $hiresPerMonthData = [];

            for ($m = 1; $m <= 12; $m++) {
                $hiresPerMonthData[] = $hiresPerMonth[$m] ?? 0;
            }
        }

        // ---------------------------------
        // ENTERPRISE ANALYTICS
        // ---------------------------------
        $jobsStatus = null;
        $funnel = null;
        $applicantsByCategory = null;
        $experienceLevels = null;

        if ($analyticsLevel === 'enterprise') {

            // Job Status
            $jobsStatus = JobPost::where('employer_profile_id', $employerProfileId)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            // Recruitment Funnel
            $funnel = [
                'Applied' => $statusDistribution['applied'] ?? 0,
                'Shortlisted' => $statusDistribution['shortlisted'] ?? 0,
                'Interview' => $statusDistribution['interview'] ?? 0,
                'Hired' => $statusDistribution['hired'] ?? 0,
            ];

            // Applicants by Industry
            $applicantsByCategory = (clone $applicationsQuery)
                ->join('job_posts', 'job_posts.id', '=', 'job_applications.job_post_id')
                ->selectRaw('job_posts.industry, COUNT(*) as total')
                ->groupBy('job_posts.industry')
                ->pluck('total', 'industry')
                ->toArray();

            // Candidate Experience Levels
            $experienceLevels = CandidateProfile::whereHas('applications.jobPost', function ($q) use ($employerProfileId) {
                $q->where('employer_profile_id', $employerProfileId);
            })
                ->selectRaw("
    CASE
        WHEN experience_years <= 2 THEN 'Entry'
        WHEN experience_years BETWEEN 3 AND 5 THEN 'Mid'
        ELSE 'Senior'
    END as level,
    COUNT(*) as total
")
                ->groupBy('level')
                ->pluck('total', 'level')
                ->toArray();
        }



        return view('employer.contents.analytics', compact(
            'analyticsLevel',
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
        ));
    }

    public function exportPdf()
    {
        $user = Auth::user();
        $profile = $user->employerProfile;

        $jobs = JobPost::where('employer_profile_id', $profile->id)
            ->withCount('applications')
            ->get();

        $totalApplications = $jobs->sum('applications_count');

        $hiresThisMonth = JobApplication::whereHas('jobPost', function ($q) use ($profile) {
            $q->where('employer_profile_id', $profile->id);
        })
            ->where('status', 'hired')
            ->whereMonth('updated_at', now()->month)
            ->count();

        $avgApplicants = $jobs->count() > 0
            ? round($totalApplications / $jobs->count(), 2)
            : 0;

        $pdf = Pdf::loadView('employer.contents.analytics.analytics_pdf', [
            'jobs' => $jobs,
            'user' => $user,
            'employer' => $profile,
            'totalApplications' => $totalApplications,
            'hiresThisMonth' => $hiresThisMonth,
            'avgApplicants' => $avgApplicants
        ]);

        return $pdf->download('recruitment-analytics-report.pdf');
    }

    public function exportCsv()
    {
        $profile = Auth::user()->employerProfile;

        $jobs = JobPost::where('employer_profile_id', $profile->id)
            ->withCount('applications')
            ->get();

        $filename = "analytics.csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($jobs) {

            $file = fopen('php://output', 'w');

            fputcsv($file, ['Job Title', 'Industry', 'Applications']);

            foreach ($jobs as $job) {
                fputcsv($file, [
                    $job->title,
                    $job->industry,
                    $job->applications_count
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}