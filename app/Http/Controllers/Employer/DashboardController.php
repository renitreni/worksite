<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPost;

class DashboardController extends Controller
{
    public function index()
    {
        $employer = Auth::user()->employerProfile;

        // Fetch jobs for this employer
        $jobs = $employer
            ? JobPost::where('employer_profile_id', $employer->id)
                ->open() 
                ->latest()
                ->withCount('applications')
                ->with(['applications' => fn($q) => $q->latest()->take(5)])
                ->get()
            : collect();

        // Calculate stats
        $stats = [
            'postedJobs' => $jobs->count(),
            'applicants' => $jobs->sum('applications_count'),
            'interviews' => $jobs->sum(fn($job) => $job->applications()->where('status','interview')->count()),
            'shortlisted' => $jobs->sum(fn($job) => $job->applications()->where('status','shortlisted')->count()),
        ];

        // Fetch notifications
        $notifications = $employer ? $employer->user->notifications()->latest()->take(20)->get() : collect();
        $notificationsArray = $notifications->map(fn($n) => [
            'id' => $n->id,
            'title' => $n->data['title'] ?? '',
            'body' => $n->data['body'] ?? '',
            'time' => $n->created_at->diffForHumans(),
            'icon' => $n->data['icon'] ?? 'bell',
            'iconWrap' => $n->data['iconWrap'] ?? 'bg-gray-50 border-gray-200',
            'iconColor' => $n->data['iconColor'] ?? 'text-gray-600',
        ])->values()->all();

        return view('employer.contents.dashboard', compact('jobs','stats','notificationsArray'));
    }
}