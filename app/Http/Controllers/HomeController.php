<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\EmployerProfile;

class HomeController extends Controller
{
    public function index()
    {
        // Featured jobs
        $featuredJobs = JobPost::query()
            ->with(['employerProfile:id,company_name'])
            ->where('status', 'open')
            ->orderByDesc('posted_at')
            ->orderByDesc('created_at')
            ->take(9)
            ->get();

        // Featured agencies (approved only) + count open jobs
        $featuredAgencies = EmployerProfile::query()
            ->with(['industry:id,name']) // if you want main industry name
            ->withCount([
                'jobPosts as open_jobs_count' => function ($q) {
                    $q->where('status', 'open');
                }
            ])
            // ->where('status', 'approved')
            ->orderByDesc('open_jobs_count')
            ->orderByDesc('total_profile_views')
            ->take(12)
            ->get();

        return view('main', compact('featuredJobs', 'featuredAgencies'));
    }
}