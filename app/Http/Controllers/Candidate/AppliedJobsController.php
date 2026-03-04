<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;

class AppliedJobsController extends Controller
{
    public function index()
    {
        $candidateId = Auth::id();

        $applications = JobApplication::with([
            'jobPost.employerProfile'
        ])
            ->where('candidate_id', $candidateId)
            ->latest()
            ->get()
            ->map(function ($app) {

                $job = $app->jobPost;
                $company = $job?->employerProfile;

                return [

                    'id' => $app->id,
                    'job_post_id' => $job?->id,

                    'title' => $job->title ?? 'Job',

                    'company' => $company->company_name ?? 'Company',

                    'industry' => $job->industry ?? 'Not specified',

                    'location' => collect([
                        $job->city,
                        $job->country
                    ])->filter()->implode(', '),

                    'salaryText' => ($job?->salary_min && $job?->salary_max)
                        ? $job->salary_currency . ' ' . number_format($job->salary_min) . ' - ' . number_format($job->salary_max)
                        : 'Salary not specified',

                    'badge' => strtoupper(substr($company->company_name ?? 'CO', 0, 2)),

                    'status' => ucfirst($app->status),

                    'statusPill' => match ($app->status) {
                        'submitted' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'shortlisted' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'interview' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        'hired' => 'bg-green-50 text-green-700 border-green-200',
                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                        default => 'bg-gray-50 text-gray-700 border-gray-200'
                    },

                    'appliedDate' => $app->created_at->format('M d, Y'),

                    'createdAt' => $app->created_at->timestamp * 1000
                ];
            })->values(); // ensures clean array

        return response()->json([
            'jobs' => $applications
        ]);
    }
}