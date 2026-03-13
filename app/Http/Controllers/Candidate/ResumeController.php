<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResumeAttachment;
use App\Models\ResumeEducation;
use App\Models\ResumeExperience;
use App\Services\Candidate\ResumeService;

class ResumeController extends Controller
{
    protected $resumeService;

    public function __construct(ResumeService $resumeService)
    {
        $this->resumeService = $resumeService;
    }

    public function index()
    {
        return $this->resumeService->showResume();
    }

    public function uploadResume(Request $request)
    {
        return $this->resumeService->uploadResume($request);
    }

    public function deleteResume()
    {
        return $this->resumeService->deleteResume();
    }

    public function uploadAttachments(Request $request)
    {
        return $this->resumeService->uploadAttachments($request);
    }

    public function deleteAttachment(ResumeAttachment $attachment)
    {
        return $this->resumeService->deleteAttachment($attachment);
    }

    public function storeExperience(Request $request)
    {
        return $this->resumeService->storeExperience($request);
    }

    public function deleteExperience(ResumeExperience $experience)
    {
        return $this->resumeService->deleteExperience($experience);
    }

    public function storeEducation(Request $request)
    {
        return $this->resumeService->storeEducation($request);
    }

    public function deleteEducation(ResumeEducation $education)
    {
        return $this->resumeService->deleteEducation($education);
    }
}