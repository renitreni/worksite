<?php

namespace App\Services\Candidate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CandidateResume;
use App\Models\JobApplication;
use App\Models\JobPost;

class JobApplicationService
{
    public function apply(Request $request, JobPost $job)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'candidate') {
            return back()
                ->withErrors(['apply' => 'Only candidates can apply.'])
                ->withInput();
        }

        // Prevent duplicate application
        if (JobApplication::where('job_post_id', $job->id)
            ->where('candidate_id', $user->id)
            ->exists()) {
            return back()->with('success', 'You already applied for this job.');
        }

        $validated = $request->validate([
            'full_name' => ['required','string','max:150'],
            'email'     => ['required','email','max:190'],
            'phone'     => ['nullable','string','max:40'],
            'resume' => ['nullable','file','mimes:pdf,doc,docx','max:5120'],
            'cover_letter_text' => ['nullable','string','max:5000'],
            'cover_letter_file' => ['nullable','file','mimes:pdf,doc,docx','max:5120'],
        ]);

        // Get existing resume
        $resume = $user->candidateResume;

        // Replace resume if new uploaded
        if ($request->hasFile('resume')) {

            if ($resume && $resume->resume_path &&
                Storage::disk('public')->exists($resume->resume_path)) {

                Storage::disk('public')->delete($resume->resume_path);
            }

            $file = $request->file('resume');

            $path = $file->store('resumes', 'public');

            $resume = CandidateResume::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'resume_path'   => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'size_bytes'    => $file->getSize(),
                ]
            );
        }

        if (!$resume) {
            return back()
                ->withErrors(['resume' => 'Please upload your resume first.'])
                ->withInput();
        }

        // Upload cover letter file
        $coverLetterFilePath = null;

        if ($request->hasFile('cover_letter_file')) {
            $coverLetterFilePath = $request->file('cover_letter_file')
                ->store('cover_letters', 'public');
        }

        // Create application
        JobApplication::create([
            'job_post_id' => $job->id,
            'candidate_id' => $user->id,
            'status' => 'applied',
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'cover_letter' => $validated['cover_letter_text'] ?? null,
            'cover_letter_file_path' => $coverLetterFilePath,
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }
}