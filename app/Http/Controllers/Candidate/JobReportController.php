<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobReportController extends Controller
{
    public function create(JobPost $job)
    {
        if ($job->status !== 'open') {
            abort(404);
        }

        return view('candidate.jobs.report', compact('job'));
    }

    public function store(Request $request, JobPost $job)
    {
        if ($job->status !== 'open') {
            return back()->with('danger', 'This job is not available.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:100',
            'details' => 'nullable|string|max:2000',
        ]);

        JobReport::create([
            'job_post_id' => $job->id,
            'user_id' => Auth::id(), // candidate
            'reason' => $validated['reason'],
            'details' => $validated['details'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('mainpage.job-details-page.layout', $job->id)
            ->with('success', 'Report submitted. Thank you for helping keep the platform safe.');
    }
}
