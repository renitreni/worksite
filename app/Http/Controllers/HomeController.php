<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\EmployerProfile;

class HomeController extends Controller
{
    public function index()
    {
        // ✅ Featured jobs (open) + employer approved + employer user active
        $featuredJobs = JobPost::query()
            ->with([
                'employerProfile.user:id,account_status,archived_at',
                'employerProfile.verification:employer_profile_id,status',
            ])
            ->where('status', 'open')
            ->whereHas('employerProfile.verification', function ($q) {
                $q->where('status', 'approved');
            })
            ->whereHas('employerProfile.user', function ($q) {
                $q->where('account_status', 'active')
                    ->whereNull('archived_at'); // optional pero safe
            })
            ->orderByDesc('posted_at')
            ->orderByDesc('created_at')
            ->take(9)
            ->get();

        // ✅ Featured agencies (approved + user active) + open jobs count
        $featuredAgencies = EmployerProfile::query()
            ->with([
                'industries:id,name',
                'verification:employer_profile_id,status',
                'user:id,account_status,archived_at',
            ])
            ->whereHas('verification', function ($q) {
                $q->where('status', 'approved');
            })
            ->whereHas('user', function ($q) {
                $q->where('account_status', 'active')
                    ->whereNull('archived_at'); // optional pero safe
            })
            ->withCount([
                'jobPosts as open_jobs_count' => function ($q) {
                    $q->where('status', 'open');
                }
            ])
            ->orderByDesc('open_jobs_count')
            ->orderByDesc('total_profile_views')
            ->take(12)
            ->get();

        return view('main', compact('featuredJobs', 'featuredAgencies'));
    }
}
