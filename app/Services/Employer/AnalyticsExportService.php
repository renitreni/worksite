<?php

namespace App\Services\Employer;

use App\Models\JobPost;
use App\Models\JobApplication;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyticsExportService
{
    public function exportPdf($profile)
    {
        $jobs = JobPost::where('employer_profile_id',$profile->id)
            ->withCount('applications')
            ->get();

        $totalApplications = $jobs->sum('applications_count');

        $hiresThisMonth = JobApplication::whereHas('jobPost', function ($q) use ($profile) {
            $q->where('employer_profile_id',$profile->id);
        })
        ->where('status','hired')
        ->whereMonth('updated_at',now()->month)
        ->count();

        $avgApplicants = $jobs->count() > 0
            ? round($totalApplications / $jobs->count(),2)
            : 0;

        $pdf = Pdf::loadView('employer.contents.analytics.analytics_pdf',[
            'jobs'=>$jobs,
            'employer'=>$profile,
            'totalApplications'=>$totalApplications,
            'hiresThisMonth'=>$hiresThisMonth,
            'avgApplicants'=>$avgApplicants
        ]);

        return $pdf->download('recruitment-analytics-report.pdf');
    }

    public function exportCsv($profile)
    {
        $jobs = JobPost::where('employer_profile_id',$profile->id)
            ->withCount('applications')
            ->get();

        $headers = [
            "Content-Type"=>"text/csv",
            "Content-Disposition"=>"attachment; filename=analytics.csv"
        ];

        $callback = function() use ($jobs) {

            $file = fopen('php://output','w');

            fputcsv($file,['Job Title','Industry','Applications']);

            foreach ($jobs as $job) {
                fputcsv($file,[
                    $job->title,
                    $job->industry,
                    $job->applications_count
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback,200,$headers);
    }
}