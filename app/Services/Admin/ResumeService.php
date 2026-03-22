<?php

namespace App\Services\Admin;

use App\Models\CandidateResume;

class ResumeService
{
    public function getAll()
    {
        $resumes = CandidateResume::with([
            'user.jobApplications.jobPost.employerProfile'
        ])->latest()->paginate(10);

        $all = CandidateResume::with('user.jobApplications')->get();

        $validCvCount = 0;
        $appliedWithCv = 0;
        $appliedButRemoved = 0;

        foreach ($all as $resume) {

            $hasCv = $resume->resume_path &&
                \Storage::disk('public')->exists($resume->resume_path);

            $hasApplication = $resume->user?->jobApplications?->count() > 0;

            if ($hasCv) {
                $validCvCount++;

                if ($hasApplication) {
                    $appliedWithCv++;
                }
            } else {
                if ($hasApplication) {
                    $appliedButRemoved++;
                }
            }
        }

        return [
            'resumes' => $resumes,
            'stats' => [
                'total_cv' => $validCvCount,
                'applied_with_cv' => $appliedWithCv,
                'applied_removed_cv' => $appliedButRemoved,
            ]
        ];
    }
}