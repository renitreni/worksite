<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\EmployerProfile;
use App\Models\Industry;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        /**
         * ✅ Featured Jobs
         */
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
                    ->whereNull('archived_at');
            })
            ->orderByDesc('posted_at')
            ->orderByDesc('created_at')
            ->take(9)
            ->get();


        /**
         * ✅ Featured Agencies
         */
        $featuredAgencies = EmployerProfile::query()
            ->with([
                'industries:id,name',
                'verification:employer_profile_id,status',
                'user:id,email,account_status,archived_at',
            ])
            ->whereHas('verification', function ($q) {
                $q->where('status', 'approved');
            })
            ->whereHas('user', function ($q) {
                $q->where('account_status', 'active')
                    ->whereNull('archived_at');
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


        /**
         * ✅ DYNAMIC STATS
         */

        // Active Jobs (cached for 10 minutes)
        $activeJobsCount = cache()->remember('home_active_jobs', 600, function () {
            return JobPost::where('status', 'open')->count();
        });

        // Agencies Count (also good to cache)
        $agenciesCount = cache()->remember('home_agencies_count', 600, function () {
            return EmployerProfile::query()
                ->whereHas('verification', function ($q) {
                    $q->where('status', 'approved');
                })
                ->whereHas('user', function ($q) {
                    $q->where('account_status', 'active')
                        ->whereNull('archived_at');
                })
                ->count();
        });


        /**
         * ✅ Industry Section (same as your code)
         */
        $industries = Industry::query()
            ->where('is_active', 1)
            ->with([
                'skills' => function ($q) {
                    $q->where('is_active', 1)
                        ->orderBy('sort_order')
                        ->orderBy('name');
                }
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $industryCards = $industries->map(function ($industry) {

            $openJobsCount = JobPost::query()
                ->where('status', 'open')
                ->whereHas('employerProfile.industries', function ($q) use ($industry) {
                    $q->where('industries.id', $industry->id);
                })
                ->whereHas('employerProfile.verification', function ($q) {
                    $q->where('status', 'approved');
                })
                ->whereHas('employerProfile.user', function ($q) {
                    $q->where('account_status', 'active')
                        ->whereNull('archived_at');
                })
                ->count();

            $skills = $industry->skills
                ->pluck('name')
                ->filter()
                ->take(3)
                ->values();

            return [
                'id' => $industry->id,
                'name' => $industry->name,
                'jobs' => (int) $openJobsCount,
                'image' => $industry->image,
                'skills' => $skills,
            ];
        });

        return view('mainpage.home', compact(
            'featuredJobs',
            'featuredAgencies',
            'industryCards',
            'activeJobsCount',
            'agenciesCount'
        ));
    }
}
