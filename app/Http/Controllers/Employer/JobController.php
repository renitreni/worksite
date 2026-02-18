<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    // Show only active jobs
    public function index()
    {
        $profile = Auth::user()->employerProfile;

        if (!$profile || $profile->status !== 'approved') {
            abort(403, 'Employer not approved.');
        }

        $jobs = $profile->jobPosts()->where('status', 'open')->latest()->get();

        return view('employer.contents.job-postings.active', compact('jobs'));
    }
    
    // Show form to post a new job
    public function create()
    {
        return view('employer.contents.job-postings.create');
    }

    // Store a new job
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary' => 'nullable|numeric',
            'job_type' => 'required|string',
            'required_skills' => 'required|string',
        ]);

        $profile = Auth::user()->employerProfile;

        $profile->jobPosts()->create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'salary' => $request->salary,
            'job_type' => $request->job_type,
            'required_skills' => $request->required_skills,
            'status' => 'open', // always start as active
        ]);

        return redirect()->route('employer.job-postings.index')
                         ->with('success', 'Job posted successfully!');
    }

    public function show(JobPost $job)
    {
        if ($job->employerProfile->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('employer.contents.job-postings.show', compact('job'));
    }

    public function edit(JobPost $job)
    {
        if ($job->employerProfile->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('employer.contents.job-postings.edit', compact('job'));
    }

    public function update(Request $request, JobPost $job)
    {
        if ($job->employerProfile->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary' => 'nullable|numeric',
            'job_type' => 'required|string',
            'required_skills' => 'required|string',
            'status' => 'nullable|string',
        ]);

        $job->update($request->only(['title','description','location','salary','job_type','required_skills', 'status']));

        return redirect()->route('employer.job-postings.index')
                        ->with('success', 'Job updated successfully!');
    }

    public function destroy(JobPost $job)
    {
        if ($job->employerProfile->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $job->update(['status' => 'closed']); // soft-close instead of delete
        return redirect()->route('employer.job-postings.index')
                        ->with('success', 'Job closed successfully.');
    }

    public function closed()
    {
        $profile = Auth::user()->employerProfile;

        if (!$profile || $profile->status !== 'approved') {
            abort(403, 'Employer not approved.');
        }

        $jobs = $profile->jobPosts()->where('status', 'closed')->latest()->get();

        return view('employer.contents.job-postings.closed', compact('jobs'));
    }

    public function reopen(JobPost $job)
    {
        // Ensure only the owner can reopen
        if ($job->employerProfile->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only closed jobs can be reopened
        if ($job->status !== 'closed') {
            return redirect()->back()->with('error', 'Job is already open.');
        }

        $job->update(['status' => 'open']);

        return redirect()->route('employer.job-postings.index')
                        ->with('success', 'Job reopened successfully!');
    }
}