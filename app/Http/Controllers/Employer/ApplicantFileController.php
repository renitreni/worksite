<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\ResumeAttachment;
use App\Services\Employer\ApplicantFileService;

class ApplicantFileController extends Controller
{
    public function __construct(
        private ApplicantFileService $fileService
    ) {}

    public function previewCv(JobApplication $application)
    {
        return $this->fileService->previewCv($application);
    }

    public function downloadCv(JobApplication $application)
    {
        return $this->fileService->downloadCv($application);
    }

    public function downloadDocument(JobApplication $application, ResumeAttachment $attachment)
    {
        return $this->fileService->downloadDocument($application, $attachment);
    }

    public function previewDocument(JobApplication $application, ResumeAttachment $attachment)
    {
        return $this->fileService->previewDocument($application, $attachment);
    }
}