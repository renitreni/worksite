<?php

namespace App\Services\Admin;

use App\Models\CandidateResume;

class ResumeService
{
    public function getAll()
    {
        return CandidateResume::with([
            'user',
            'experiences',
            'educations',
            'user.jobApplications.jobPost',
            'user.jobApplications.employerProfile'
        ])->latest()->paginate(10);
    }
}