<?php

namespace App\Services\Employer;

use App\Models\JobApplication;
use App\Models\ResumeAttachment;
use Illuminate\Support\Facades\Storage;

class ApplicantFileService
{
    public function __construct(
        private EmployerAccessService $accessService
    ) {}

    public function previewCv(JobApplication $application)
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        abort_unless(
            $access['can_preview_cv'] || $access['can_download_cv'],
            403,
            'Upgrade required to preview CV.'
        );

        $resume = $this->getResumeFromApplication($application);

        $path = $resume->resume_path;

        $this->ensureFileExists($path);

        $mime = $resume->resume_mime
            ?: Storage::disk('public')->mimeType($path)
            ?: 'application/octet-stream';

        $filename = $resume->resume_original_name ?: 'resume.pdf';

        return response(
            Storage::disk('public')->get($path),
            200,
            [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="'.$filename.'"',
            ]
        );
    }

    public function downloadCv(JobApplication $application)
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        abort_unless(
            $access['can_download_cv'],
            403,
            'Upgrade required to download CV.'
        );

        $resume = $this->getResumeFromApplication($application);

        $path = $resume->resume_path;

        $this->ensureFileExists($path);

        $filename = $resume->resume_original_name ?: 'resume.pdf';

        return Storage::disk('public')->download($path, $filename);
    }

    public function downloadDocument(JobApplication $application, ResumeAttachment $attachment)
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        abort_unless(
            $access['can_download_documents'],
            403,
            'Upgrade required to download documents.'
        );

        $resume = $this->validateAttachmentOwnership($application, $attachment, $profile);

        $path = $attachment->file_path;

        $this->ensureFileExists($path);

        $filename = $attachment->original_name ?: 'document';

        return Storage::disk('public')->download($path, $filename);
    }

    public function previewDocument(JobApplication $application, ResumeAttachment $attachment)
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        abort_unless(
            $access['can_download_documents'],
            403,
            'Upgrade required.'
        );

        $resume = $this->validateAttachmentOwnership($application, $attachment, $profile);

        $path = $attachment->file_path;

        $this->ensureFileExists($path);

        $mime = $attachment->mime
            ?: Storage::disk('public')->mimeType($path)
            ?: 'application/octet-stream';

        $filename = $attachment->original_name ?: 'document';

        return response(
            Storage::disk('public')->get($path),
            200,
            [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="'.$filename.'"',
            ]
        );
    }

    // ------------------------
    // Helpers
    // ------------------------

    private function getResumeFromApplication(JobApplication $application)
    {
        $application->loadMissing('candidateProfile.resume');

        $resume = $application->candidateProfile?->resume;

        abort_if(!$resume || !$resume->resume_path, 404, 'CV not found.');

        return $resume;
    }

    private function validateAttachmentOwnership(JobApplication $application, ResumeAttachment $attachment, $profile)
    {
        $application->loadMissing(['jobPost','candidateProfile.resume']);

        abort_if(
            $application->jobPost?->employer_profile_id !== $profile->id,
            403,
            'Unauthorized.'
        );

        $resume = $application->candidateProfile?->resume;

        abort_if(!$resume, 404, 'Resume not found.');

        abort_if(
            (int)$attachment->resume_id !== (int)$resume->id,
            403,
            'Unauthorized attachment.'
        );

        return $resume;
    }

    private function ensureFileExists($path)
    {
        abort_if(
            !Storage::disk('public')->exists($path),
            404,
            'File missing.'
        );
    }
}