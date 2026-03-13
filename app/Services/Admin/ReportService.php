<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\EmployerProfile;
use App\Models\CandidateProfile;
use App\Models\JobPost;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\JobApplication;
class ReportService
{
    public function generate($type, $dateFrom, $dateTo)
    {
        $from = Carbon::parse($dateFrom)->startOfDay();
        $to   = Carbon::parse($dateTo)->endOfDay();

        return match ($type) {

            'user_activity' => $this->userActivity($from,$to),

            'job_postings' => $this->jobPostings($from,$to),

            'revenue' => $this->revenue($from,$to),

            'applications_hires' => $this->applicationsHires($from,$to),

            'applications_detailed' => $this->applicationsDetailed($from,$to),

        };
    }

    private function userActivity($from, $to): array
{
    // Registered users within date range
    $registeredUsers = User::whereBetween('created_at', [$from, $to])->count();

    // Total employers/candidates (overall)
    $totalEmployers = EmployerProfile::count();
    $totalCandidates = CandidateProfile::count();

    // Active employers = employers that posted at least 1 job in the date range
    $activeEmployers = EmployerProfile::whereHas('jobPosts', function ($q) use ($from, $to) {
        $q->whereBetween('created_at', [$from, $to]);
    })->count();

    // Optional: total jobs posted in range
    $jobsPosted = JobPost::whereBetween('created_at', [$from, $to])->count();

    return [
        'title' => 'User Activity Report',
        'filters' => ['from' => $from, 'to' => $to],

        'summary' => [
            ['label' => 'Registered Users', 'value' => $registeredUsers],
            ['label' => 'Active Employers', 'value' => $activeEmployers],
            ['label' => 'Total Employers', 'value' => $totalEmployers],
            ['label' => 'Total Candidates', 'value' => $totalCandidates],
        ],

        'columns' => ['Metric', 'Value'],
        'rows' => [
            ['Registered Users (within range)', $registeredUsers],
            ['Active Employers (posted jobs in range)', $activeEmployers],
            ['Total Employers', $totalEmployers],
            ['Total Candidates', $totalCandidates],
            ['Jobs Posted (within range)', $jobsPosted],
        ],
    ];
}

    private function jobPostings($from, $to): array
{
    // Counts within date range
    $activeCount = JobPost::where('status', 'open')
        ->where('is_held', false)
        ->where('is_disabled', false)
        ->whereBetween('created_at', [$from, $to])
        ->count();

    $pendingCount = JobPost::where('is_held', true)
        ->whereBetween('created_at', [$from, $to])
        ->count();

    $removedCount = JobPost::where('is_disabled', true)
        ->whereBetween('created_at', [$from, $to])
        ->count();

    // Rows table (list jobs)
    $jobs = JobPost::with(['employerProfile.user'])
        ->whereBetween('created_at', [$from, $to])
        ->latest()
        ->get()
        ->map(function ($job) {
            $employerName = optional(optional($job->employerProfile)->user)->name
                ?? optional($job->employerProfile)->company_name
                ?? '—';

            // Derived status based on mapping
            if ($job->is_disabled) {
                $derived = 'removed';
            } elseif ($job->is_held) {
                $derived = 'pending';
            } elseif ($job->status === 'open') {
                $derived = 'active';
            } else {
                $derived = $job->status ?? '—';
            }

            return [
                $job->title ?? '—',
                $employerName,
                $derived,
                optional($job->posted_at)->format('Y-m-d H:i') ?? optional($job->created_at)->format('Y-m-d H:i'),
            ];
        })->toArray();

    return [
        'title' => 'Job Postings Report',
        'filters' => ['from' => $from, 'to' => $to],
        'summary' => [
            ['label' => 'Active', 'value' => $activeCount],
            ['label' => 'Pending', 'value' => $pendingCount],
            ['label' => 'Removed', 'value' => $removedCount],
        ],
        'columns' => ['Job Title', 'Employer', 'Status', 'Posted At'],
        'rows' => $jobs,
    ];
}
    private function revenue($from, $to): array
{
    // Summary counts
    $pendingCount = Payment::where('status', Payment::STATUS_PENDING)
        ->whereBetween('created_at', [$from, $to])
        ->count();

    $completedCount = Payment::where('status', Payment::STATUS_COMPLETED)
        ->whereBetween('created_at', [$from, $to])
        ->count();

    $failedCount = Payment::where('status', Payment::STATUS_FAILED)
        ->whereBetween('created_at', [$from, $to])
        ->count();

    // Revenue (sum completed payments)
    $revenue = Payment::where('status', Payment::STATUS_COMPLETED)
        ->whereBetween('created_at', [$from, $to])
        ->sum('amount');

    // Table rows
    $rows = Payment::with(['employer', 'plan'])
        ->whereBetween('created_at', [$from, $to])
        ->latest()
        ->get()
        ->map(function ($p) {
            return [
                optional($p->employer)->name ?? '—',
                optional($p->plan)->name ?? ($p->plan_id ?? '—'),
                number_format((float)$p->amount, 2),
                $p->method ?? '—',
                $p->status ?? '—',
                optional($p->created_at)->format('Y-m-d H:i'),
            ];
        })->toArray();

    return [
        'title' => 'Subscription & Revenue Report',
        'filters' => ['from' => $from, 'to' => $to],
        'summary' => [
            ['label' => 'Revenue (Completed)', 'value' => number_format((float)$revenue, 2)],
            ['label' => 'Pending', 'value' => $pendingCount],
            ['label' => 'Completed', 'value' => $completedCount],
            ['label' => 'Failed', 'value' => $failedCount],
        ],
        'columns' => ['Employer', 'Plan', 'Amount', 'Method', 'Status', 'Date'],
        'rows' => $rows,
    ];
}

    private function applicationsHires($from, $to): array
{
    // We’ll list jobs within the date range (created_at)
    $jobs = JobPost::with(['employerProfile.user'])
        ->whereBetween('created_at', [$from, $to])
        ->latest()
        ->get();

    $rows = $jobs->map(function ($job) use ($from, $to) {

        $applicationsCount = JobApplication::where('job_post_id', $job->id)
            ->whereBetween('created_at', [$from, $to])
            ->count();

        $hiresCount = JobApplication::where('job_post_id', $job->id)
            ->where('status', JobApplication::STATUS_HIRED) // 'hired'
            ->whereBetween('created_at', [$from, $to])
            ->count();

        $employerName = optional(optional($job->employerProfile)->user)->name
            ?? optional($job->employerProfile)->company_name
            ?? '—';

        $conversion = $applicationsCount > 0
            ? round(($hiresCount / $applicationsCount) * 100, 2) . '%'
            : '0%';

        return [
            $job->title ?? '—',
            $employerName,
            $applicationsCount,
            $hiresCount,
            $conversion,
        ];
    })->toArray();

    $totalApplications = array_sum(array_map(fn($r) => (int)$r[2], $rows));
    $totalHires = array_sum(array_map(fn($r) => (int)$r[3], $rows));

    return [
        'title' => 'Applications & Hires Per Job',
        'filters' => ['from' => $from, 'to' => $to],
        'summary' => [
            ['label' => 'Jobs in Range', 'value' => count($rows)],
            ['label' => 'Total Applications', 'value' => $totalApplications],
            ['label' => 'Total Hires', 'value' => $totalHires],
            ['label' => 'Overall Conversion', 'value' => $totalApplications > 0 ? round(($totalHires / $totalApplications) * 100, 2) . '%' : '0%'],
        ],
        'columns' => ['Job Title', 'Employer', 'Applications', 'Hires', 'Conversion'],
        'rows' => $rows,
    ];
}

    private function applicationsDetailed($from, $to): array
{
    $applications = JobApplication::with([
        'jobPost.employerProfile.user',
        'candidateProfile'
    ])
    ->whereBetween('created_at', [$from, $to])
    ->latest()
    ->get();

    $rows = $applications->map(function ($app) {

        $job = $app->jobPost;

        $agencyName = optional(optional(optional($job)->employerProfile)->user)->name
            ?? optional(optional($job)->employerProfile)->company_name
            ?? '—';

        $candidateName = $app->full_name
            ?? optional($app->candidateProfile)->full_name
            ?? '—';

        return [
            $candidateName,
            $app->email ?? '—',
            $app->phone ?? '—',
            optional($job)->title ?? '—',
            $agencyName,
            optional($app->created_at)->format('Y-m-d H:i'),
            $app->status ?? '—',
        ];
    })->toArray();

    $totalApplications = count($rows);

    $totalHired = $applications->where('status', JobApplication::STATUS_HIRED)->count();

    return [
        'title' => 'Applications Detailed Report',
        'filters' => ['from' => $from, 'to' => $to],

        'summary' => [
            ['label' => 'Total Applications', 'value' => $totalApplications],
            ['label' => 'Total Hired', 'value' => $totalHired],
        ],

        'columns' => [
            'Candidate',
            'Email',
            'Phone',
            'Applied Job',
            'Agency',
            'Applied Date',
            'Status'
        ],

        'rows' => $rows,
    ];
}
}