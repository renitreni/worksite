<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    // Toggle save/unsave
    public function toggle(JobPost $job)
    {
        $userId = Auth::id();

        // only allow open jobs to be saved (optional)
        if ($job->status !== 'open') {
            return back()->with('danger', 'This job is not available.');
        }

        $existing = SavedJob::where('user_id', $userId)
            ->where('job_post_id', $job->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Removed from saved jobs.');
        }

        SavedJob::create([
            'user_id' => $userId,
            'job_post_id' => $job->id,
        ]);

        return back()->with('success', 'Job saved successfully.');
    }

    // Optional: list saved jobs page later
    public function index()
    {
        $jobs = Auth::user()
            ->savedJobPosts()
            ->with('employerProfile:id,company_name,logo_path')
            ->latest('saved_jobs.created_at')
            ->get();

        return view('mainpage.job-details-page.layout', compact('jobs'));
    }
}
