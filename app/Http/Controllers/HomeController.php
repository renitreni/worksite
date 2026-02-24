<?php

namespace App\Http\Controllers;

use App\Models\JobPost;

class HomeController extends Controller
{
    public function index()
    {
        // Featured jobs
        $featuredJobs = JobPost::query()
            ->with([
                // employerProfile relation on JobPost should exist
                'employerProfile:id,user_id,company_name',
                // also load verification so you can show only approved employers if needed
                'employerProfile.verification:employer_profile_id,status',
            ])
            ->where('status', 'open')
            // ✅ only from approved employers (since status is now in verification table)
            ->whereHas('employerProfile.verification', function ($q) {
                $q->where('status', 'approved');
            })
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
