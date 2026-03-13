<?php

namespace App\Services\Candidate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPost;
use App\Models\JobApplication;

class JobBrowseService
{
    public function browseJobs(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $country = trim((string) $request->get('country', ''));
        $industry = trim((string) $request->get('industry', ''));

        $jobsQuery = JobPost::query()
            ->with(['employerProfile:id,company_name'])
            ->where('status', 'open')
            ->where('is_disabled', false)
            ->orderByDesc('posted_at')
            ->orderByDesc('created_at');

        if ($q !== '') {
            $jobsQuery->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('industry', 'like', "%{$q}%")
                    ->orWhere('country', 'like', "%{$q}%");
            });
        }

        if ($country !== '') {
            $jobsQuery->where('country', $country);
        }

        if ($industry !== '') {
            $jobsQuery->where('industry', $industry);
        }

        $jobs = $jobsQuery->paginate(10)->withQueryString();

        $countries = JobPost::where('status', 'open')
            ->where('is_disabled', false)
            ->whereNotNull('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        $industries = JobPost::where('status', 'open')
            ->where('is_disabled', false)
            ->whereNotNull('industry')
            ->distinct()
            ->orderBy('industry')
            ->pluck('industry');

        return view(
            'mainpage.job-details-page.layout',
            compact('jobs', 'q', 'country', 'industry', 'countries', 'industries')
        );
    }

    public function showJob(JobPost $job)
    {
        if ($job->status !== 'open' || $job->is_disabled) {
            abort(404);
        }

        $job->load('employerProfile');

        $isSaved = Auth::check()
            ? Auth::user()
                ->savedJobPosts()
                ->where('job_posts.id', $job->id)
                ->exists()
            : false;

        $alreadyApplied = false;

        if (Auth::check()) {
            $alreadyApplied = JobApplication::where('job_post_id', $job->id)
                ->where('candidate_id', Auth::id())
                ->exists();
        }

        $agencyJobs = JobPost::where('employer_profile_id', $job->employer_profile_id)
            ->where('status', 'open')
            ->where('is_disabled', false)
            ->where('id', '!=', $job->id)
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $ep = $job->employerProfile;

        return view(
            'mainpage.job-details-page.layout',
            compact('job', 'isSaved', 'agencyJobs', 'alreadyApplied', 'ep')
        );
    }
}