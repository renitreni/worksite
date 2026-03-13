<?php

namespace App\Services\Employer;

use Illuminate\Support\Facades\Auth;
use App\Models\JobPost;
use Carbon\Carbon;

class EmployerDashboardService
{
    public function getDashboardData(): array
    {
        $employer = Auth::user()->employerProfile;

        $jobs = $this->getJobs($employer);

        $stats = $this->buildStats($jobs);

        $jobsForJson = $this->buildJobsForJson($jobs);

        $notificationsArray = $this->getNotifications($employer);

        $chartData = $this->buildChartData($jobs);

        return compact(
            'jobs',
            'jobsForJson',
            'stats',
            'notificationsArray',
            'chartData'
        );
    }

    private function getJobs($employer)
    {
        if (!$employer) {
            return collect();
        }

        return JobPost::where('employer_profile_id', $employer->id)
            ->open()
            ->latest()
            ->withCount('applications')
            ->with([
                'applications' => fn($q) => $q->latest()->take(5)
            ])
            ->get();
    }

    private function buildStats($jobs): array
    {
        return [
            'postedJobs' => $jobs->count(),

            'applicants' => $jobs->sum('applications_count'),

            'interviews' => $jobs->sum(
                fn($job) =>
                $job->applications()
                    ->where('status', 'interview')
                    ->count()
            ),

            'shortlisted' => $jobs->sum(
                fn($job) =>
                $job->applications()
                    ->where('status', 'shortlisted')
                    ->count()
            ),
        ];
    }

    private function buildJobsForJson($jobs)
    {
        return $jobs->map(function ($job) {

            return [
                'id' => $job->id,
                'title' => $job->title,
                'applicants' => $job->applications_count,
                'status' => ucfirst($job->status),

                'statusPill' => match ($job->status) {
                    'open' =>
                    'bg-emerald-50 text-emerald-700 border border-emerald-100',

                    'closed' =>
                    'bg-red-50 text-red-700 border border-red-100',

                    default =>
                    'bg-gray-50 text-gray-700 border border-gray-100'
                },

                'applicantsList' => $job->applications
                    ->map(fn($a) => [
                        'id' => $a->id,
                        'name' => $a->full_name ?? 'Candidate',
                        'email' => $a->email ?? '',
                        'appliedDate' => $a->created_at->format('M d, Y')
                    ])
                    ->values()
                    ->all()
            ];
        });
    }

    private function getNotifications($employer)
    {
        if (!$employer) {
            return [];
        }

        return $employer->user
            ->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'title' => $n->data['title'] ?? '',
                'body' => $n->data['body'] ?? '',
                'time' => $n->created_at->diffForHumans(),
                'icon' => $n->data['icon'] ?? 'bell',
                'iconWrap' => $n->data['iconWrap'] ?? 'bg-gray-50 border-gray-200',
                'iconColor' => $n->data['iconColor'] ?? 'text-gray-600',
            ])
            ->values()
            ->all();
    }

    private function buildChartData($jobs)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $applications = $jobs
            ->flatMap->applications
            ->filter(
                fn($app) =>
                $app->created_at->month == $currentMonth &&
                $app->created_at->year == $currentYear
            );

        $dailyApplications = $applications
            ->groupBy(fn($app) => $app->created_at->day)
            ->map->count();

        $daysInMonth = now()->daysInMonth;

        $labels = [];
        $data = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $labels[] = Carbon::create(
                now()->year,
                now()->month,
                $day
            )->format('M d');

            $data[] = $dailyApplications[$day] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}