<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\EmployerProfile;

class HomeController extends Controller
{
    public function index()
    {
        // ✅ Featured jobs
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

        // ✅ Featured agencies (approved only) + count open jobs
        $featuredAgencies = EmployerProfile::query()
            ->with([
                // pivot industries (many-to-many)
                'industries:id,name',
                // verification status
                'verification:employer_profile_id,status',
            ])
            ->withCount([
                'jobPosts as open_jobs_count' => function ($q) {
                    $q->where('status', 'open');
                }
            ])
            // ✅ approved only (moved from employer_profiles.status)
            ->whereHas('verification', function ($q) {
                $q->where('status', 'approved');
            })
            // optional: only show agencies that actually have open jobs
            // ->having('open_jobs_count', '>', 0)
            ->orderByDesc('open_jobs_count')
            ->orderByDesc('total_profile_views')
            ->take(12)
            ->get();

        return view('main', compact('featuredJobs', 'featuredAgencies'));
    }
}