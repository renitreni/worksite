<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPost;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $employer = Auth::user()->employerProfile;

        // Fetch jobs
        $jobs = $employer
            ? JobPost::where('employer_profile_id', $employer->id)
                ->open()
                ->latest()
                ->withCount('applications')
                ->with(['applications' => fn($q) => $q->latest()->take(5)])
                ->get()
            : collect();

        // Stats
        $stats = [
            'postedJobs' => $jobs->count(),
            'applicants' => $jobs->sum('applications_count'),
            'interviews' => $jobs->sum(
                fn($job) =>
                $job->applications()->where('status', 'interview')->count()
            ),
            'shortlisted' => $jobs->sum(
                fn($job) =>
                $job->applications()->where('status', 'shortlisted')->count()
            ),
        ];

        /*
        |--------------------------------------------------------------------------
        | Jobs for Alpine.js
        |--------------------------------------------------------------------------
        */

        $jobsForJson = $jobs->map(function ($job) {

            return [
                'id' => $job->id,
                'title' => $job->title,
                'applicants' => $job->applications_count,
                'status' => ucfirst($job->status),

                'statusPill' => match ($job->status) {
                    'open' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                    'closed' => 'bg-red-50 text-red-700 border border-red-100',
                    default => 'bg-gray-50 text-gray-700 border border-gray-100'
                },

                'applicantsList' => $job->applications->map(fn($a) => [
                    'id' => $a->id,
                    'name' => $a->full_name ?? 'Candidate',
                    'email' => $a->email ?? '',
                    'appliedDate' => $a->created_at->format('M d, Y')
                ])->values()->all()
            ];
        });


        /*
        |--------------------------------------------------------------------------
        | Notifications
        |--------------------------------------------------------------------------
        */

        $notifications = $employer
            ? $employer->user->notifications()->latest()->take(5)->get()
            : collect();

        $notificationsArray = $notifications->map(fn($n) => [
            'id' => $n->id,
            'title' => $n->data['title'] ?? '',
            'body' => $n->data['body'] ?? '',
            'time' => $n->created_at->diffForHumans(),
            'icon' => $n->data['icon'] ?? 'bell',
            'iconWrap' => $n->data['iconWrap'] ?? 'bg-gray-50 border-gray-200',
            'iconColor' => $n->data['iconColor'] ?? 'text-gray-600',
        ])->values()->all();


        /*
        |--------------------------------------------------------------------------
        | Chart Data
        |--------------------------------------------------------------------------
        */

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

            $labels[] = Carbon::create(now()->year, now()->month, $day)->format('M d');

            $data[] = $dailyApplications[$day] ?? 0;
        }

        $chartData = [
            'labels' => $labels,
            'data' => $data
        ];

        return view('employer.contents.dashboard', compact(
            'jobs',
            'jobsForJson',
            'stats',
            'notificationsArray',
            'chartData'
        ));
    }
}