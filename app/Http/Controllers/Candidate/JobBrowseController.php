<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;

use function Symfony\Component\String\u;


class JobBrowseController extends Controller
{
    // List all OPEN jobs for candidates
    public function index(Request $request)
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

        // Simple pagination
        $jobs = $jobsQuery->paginate(10)->withQueryString();

        // Optional: build quick filters from existing jobs (since you store strings)
        $countries = JobPost::where('status', 'open')
            ->where('is_disabled', false) // ✅ add this
            ->whereNotNull('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        $industries = JobPost::where('status', 'open')
            ->where('is_disabled', false) // ✅ add this
            ->whereNotNull('industry')
            ->distinct()
            ->orderBy('industry')
            ->pluck('industry');

        return view('mainpage.job-details-page.layout', compact('jobs', 'q', 'country', 'industry', 'countries', 'industries'));
    }

    // View job details


    public function show(JobPost $job)
    {
        if ($job->status !== 'open' || $job->is_disabled) {
            abort(404);
        }
        $job->load('employerProfile');

        $isSaved = Auth::check()
            ? Auth::user()->savedJobPosts()
                ->where('job_posts.id', $job->id)
                ->exists()
            : false;

        // ✅ CHECK IF USER ALREADY APPLIED
        $alreadyApplied = false;
        $ep = $job->employerProfile;

        if (Auth::check()) {
            $alreadyApplied = JobApplication::where('job_post_id', $job->id)
                ->where('candidate_id', Auth::id())
                ->exists();
        }

        $agencyJobs = JobPost::where('employer_profile_id', $job->employer_profile_id)
            ->where('status', 'open')
            ->where('is_disabled', false) // ✅ add this
            ->where('id', '!=', $job->id)
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view(
            'mainpage.job-details-page.layout',
            compact('job', 'isSaved', 'agencyJobs', 'alreadyApplied', 'ep')
        );
    }
}
