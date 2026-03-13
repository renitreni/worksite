<?php

namespace App\Services\Candidate;

use App\Models\JobPost;
use App\Models\SavedJob;
use Illuminate\Support\Facades\Auth;

class SavedJobService
{
    public function toggle(JobPost $job)
    {
        $userId = Auth::id();

        if ($job->status !== 'open') {

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This job is not available.'
                ]);
            }

            return back()->with('danger', 'This job is not available.');
        }

        $existing = SavedJob::where('user_id', $userId)
            ->where('job_post_id', $job->id)
            ->first();

        if ($existing) {

            $existing->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'saved' => false
                ]);
            }

            return back()->with('success', 'Removed from saved jobs.');
        }

        SavedJob::create([
            'user_id' => $userId,
            'job_post_id' => $job->id,
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'saved' => true
            ]);
        }

        return back()->with('success', 'Job saved successfully.');
    }

    public function listSavedJobs()
    {
        $savedJobs = SavedJob::with([
                'jobPost.employerProfile'
            ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('candidate.contents.saved-jobs', compact('savedJobs'));
    }

    public function removeSavedJob($jobId)
    {
        $saved = SavedJob::where('user_id', Auth::id())
            ->where('job_post_id', $jobId)
            ->first();

        if ($saved) {
            $saved->delete();
        }

        return back()->with('success', 'Job removed from saved.');
    }
}