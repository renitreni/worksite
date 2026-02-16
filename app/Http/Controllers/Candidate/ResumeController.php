<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateResume;
use App\Models\ResumeAttachment;
use App\Models\ResumeEducation;
use App\Models\ResumeExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    private function resume(): CandidateResume
    {
        return CandidateResume::firstOrCreate(['user_id' => auth()->id()]);
    }

    public function index()
    {
        $resume = $this->resume()->load(['experiences', 'educations', 'attachments']);
        return view('candidate.contents.my-resume', compact('resume'));
    }

    public function uploadResume(Request $request)
    {
        $request->validate([
            'resume' => ['required', 'file', 'max:5120', 'mimes:pdf,doc,docx'],
        ]);

        $resume = $this->resume();

        // delete old
        if ($resume->resume_path) {
            Storage::disk('public')->delete($resume->resume_path);
        }

        $file = $request->file('resume');
        $path = $file->store('candidate/resume-files', 'public');

        $resume->update([
            'resume_path' => $path,
            'resume_original_name' => $file->getClientOriginalName(),
            'resume_mime' => $file->getMimeType(),
            'resume_size' => $file->getSize(),
        ]);

        return back()->with('success', 'Resume/CV uploaded.');
    }

    public function deleteResume()
    {
        $resume = $this->resume();

        if ($resume->resume_path) {
            Storage::disk('public')->delete($resume->resume_path);
        }

        $resume->update([
            'resume_path' => null,
            'resume_original_name' => null,
            'resume_mime' => null,
            'resume_size' => null,
        ]);

        return back()->with('danger', 'Resume/CV removed.');
    }

    public function uploadAttachments(Request $request)
    {
        $request->validate([
            'category' => ['required', 'string', 'max:120'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'max:5120', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
        ]);

        $resume = $this->resume();

        foreach ($request->file('files') as $file) {
            $path = $file->store('candidate/attachments', 'public');

            $resume->attachments()->create([
                'category' => $request->category,
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        return back()->with('success', 'Attachment(s) uploaded.');
    }

    public function deleteAttachment(ResumeAttachment $attachment)
    {
        $resume = $this->resume();
        abort_unless($attachment->resume_id === $resume->id, 403);

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return back()->with('danger', 'Attachment removed.');
    }

    public function storeExperience(Request $request)
    {
        $request->validate([
            'role' => ['required', 'string', 'max:120'],
            'company' => ['required', 'string', 'max:120'],
            'start' => ['nullable', 'string', 'max:50'],
            'end' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $resume = $this->resume();
        $resume->experiences()->create($request->only('role', 'company', 'start', 'end', 'description'));

        return back()->with('success', 'Experience added.');
    }

    public function deleteExperience(ResumeExperience $experience)
    {
        $resume = $this->resume();
        abort_unless($experience->resume_id === $resume->id, 403);

        $experience->delete();
        return back()->with('danger', 'Experience deleted.');
    }

    public function storeEducation(Request $request)
    {
        $request->validate([
            'degree' => ['required', 'string', 'max:160'],
            'school' => ['required', 'string', 'max:160'],
            'year' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $resume = $this->resume();
        $resume->educations()->create($request->only('degree', 'school', 'year', 'notes'));

        return back()->with('success', 'Education added.');
    }

    public function deleteEducation(ResumeEducation $education)
    {
        $resume = $this->resume();
        abort_unless($education->resume_id === $resume->id, 403);

        $education->delete();
        return back()->with('danger', 'Education deleted.');
    }
}
