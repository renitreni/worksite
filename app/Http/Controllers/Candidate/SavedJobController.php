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

        // Optional: only allow open jobs
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

    public function index()
    {
        $savedJobs = SavedJob::with([
            'jobPost.employerProfile'
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('candidate.contents.saved-jobs', compact('savedJobs'));
    }


    public function destroy($id)
    {
        $saved = SavedJob::where('user_id', Auth::id())
            ->where('job_post_id', $id)
            ->first();

        if ($saved) {
            $saved->delete();
        }

        return back()->with('success', 'Job removed from saved.');
    }
}
