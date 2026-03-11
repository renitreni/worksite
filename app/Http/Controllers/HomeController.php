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
            ->where('is_disabled', false) // ✅ add this
            ->orderByDesc('created_at')
            ->take(9)
            ->get();

        /**
         * ✅ Featured Agencies with Priority System
         */

        $agencies = EmployerProfile::query()
            ->with([
                'industries:id,name',
                'verification',
                'user',
                'activeSubscription.plan.featureValues.definition'
            ])
            ->whereHas('verification', fn($q) => $q->where('status', 'approved'))
            ->whereHas('user', fn($q) => $q->where('account_status', 'active')->whereNull('archived_at'))
            ->withCount([
                'jobPosts as open_jobs_count' => fn($q) => $q->where('status', 'open')
            ])
            ->get();

        $featuredAgencies = $agencies->map(function ($agency) {

            $plan = optional($agency->activeSubscription?->plan);

            $visibility = $plan?->feature('search_visibility', 'normal');

            $planScore = match ($visibility) {
                'priority' => 100,
                'featured' => 70,
                'normal' => 30,
                default => 0,
            };

            $jobScore = $agency->open_jobs_count * 5;

            $viewScore = ($agency->total_profile_views ?? 0) * 0.05;

            $agency->ranking_score = $planScore + $jobScore + $viewScore;

            return $agency;

        })
            ->sortByDesc('ranking_score')
            ->take(20)
            ->values();

        /**
         * ✅ DYNAMIC STATS
         */

        // Active Jobs (cached for 10 minutes)
        $activeJobsCount = cache()->remember('home_active_jobs', 600, function () {
            return JobPost::where('status', 'open')
                ->where('is_disabled', false)
                ->count();
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
                ->where('is_disabled', false)
                ->where('industry', $industry->name)
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

    public function industryJobs(Industry $industry)
    {
        $jobs = JobPost::query()
            ->where('status', 'open')
            ->where('is_disabled', false)
            ->where('industry', $industry->name)
            ->latest('posted_at')
            ->paginate(9);

        // get industries ordered
        $industries = Industry::where('is_active', 1)
            ->orderBy('name')
            ->pluck('id')
            ->values();

        $index = $industries->search($industry->id);

        $prevIndustry = $industries[$index - 1] ?? null;
        $nextIndustry = $industries[$index + 1] ?? null;

        $otherIndustries = Industry::where('is_active', 1)
            ->where('id', '!=', $industry->id)
            ->with([
                'skills' => function ($q) {
                    $q->where('is_active', 1)
                        ->orderBy('sort_order')
                        ->orderBy('name');
                }
            ])
            ->get()
            ->map(function ($ind) {

                $jobsCount = JobPost::where('status', 'open')
                    ->where('is_disabled', false)
                    ->where('industry', $ind->name)
                    ->count();

                $skills = $ind->skills
                    ->pluck('name')
                    ->take(3)
                    ->values();

                return [
                    'id' => $ind->id,
                    'name' => $ind->name,
                    'image' => $ind->image,
                    'jobs' => $jobsCount,
                    'skills' => $skills,
                ];
            });

        return view('mainpage.industry-jobs', compact(
            'industry',
            'jobs',
            'otherIndustries'
        ));
    }
}
