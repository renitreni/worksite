<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\ResumeAttachment;
use App\Services\EmployerAccessService;
use Illuminate\Support\Facades\Storage;

class ApplicantFileController extends Controller
{
    public function __construct(
        private EmployerAccessService $accessService
    ) {}

    public function previewCv(JobApplication $application)
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        abort_unless($access['can_preview_cv'] || $access['can_download_cv'], 403, 'Upgrade required to preview CV.');

        $application->loadMissing('candidateProfile.resume');

        $resume = $application->candidateProfile?->resume;
        abort_if(!$resume || !$resume->resume_path, 404, 'CV not found.');

        $path = $resume->resume_path;

        abort_if(!Storage::disk('public')->exists($path), 404, 'File missing.');

        $mime = $resume->resume_mime ?: Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';
        $filename = $resume->resume_original_name ?: 'resume.pdf';

        return response(Storage::disk('public')->get($path), 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function downloadCv(JobApplication $application)
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        abort_unless($access['can_download_cv'], 403, 'Upgrade required to download CV.');

        $application->loadMissing('candidateProfile.resume');

        $resume = $application->candidateProfile?->resume;
        abort_if(!$resume || !$resume->resume_path, 404, 'CV not found.');

        $path = $resume->resume_path;

        abort_if(!Storage::disk('public')->exists($path), 404, 'File missing.');

        $filename = $resume->resume_original_name ?: 'resume.pdf';

        return Storage::disk('public')->download($path, $filename);
    }

    public function downloadDocument(JobApplication $application, ResumeAttachment $attachment)
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        abort_unless($access['can_download_documents'], 403, 'Upgrade required to download documents.');

        $application->loadMissing(['jobPost', 'candidateProfile.resume']);

        abort_if($application->jobPost?->employer_profile_id !== $profile->id, 403, 'Unauthorized.');

        $resume = $application->candidateProfile?->resume;
        abort_if(!$resume, 404, 'Resume not found.');

        abort_if((int) $attachment->resume_id !== (int) $resume->id, 403, 'Unauthorized attachment.');

        $path = $attachment->file_path;
        abort_if(!$path, 404, 'File path missing.');

        abort_if(!Storage::disk('public')->exists($path), 404, 'File missing.');

        $filename = $attachment->original_name ?: 'document';

        return Storage::disk('public')->download($path, $filename);
    }

    public function previewDocument(JobApplication $application, ResumeAttachment $attachment)
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        abort_unless($access['can_download_documents'], 403, 'Upgrade required.');

        $application->loadMissing(['jobPost', 'candidateProfile.resume']);

        abort_if($application->jobPost?->employer_profile_id !== $profile->id, 403, 'Unauthorized.');

        $resume = $application->candidateProfile?->resume;
        abort_if(!$resume, 404, 'Resume not found.');

        abort_if((int) $attachment->resume_id !== (int) $resume->id, 403, 'Unauthorized attachment.');

        $path = $attachment->file_path;
        abort_if(!$path, 404, 'File path missing.');

        abort_if(!Storage::disk('public')->exists($path), 404, 'File missing.');

        $mime = $attachment->mime
            ?: Storage::disk('public')->mimeType($path)
            ?: 'application/octet-stream';

        $filename = $attachment->original_name ?: 'document';

        return response(Storage::disk('public')->get($path), 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}