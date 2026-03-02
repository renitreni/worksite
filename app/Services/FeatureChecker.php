<?php

namespace App\Services;

use App\Models\EmployerProfile;

class FeatureChecker
{
    protected EmployerProfile $employer;

    public function __construct(EmployerProfile $employer)
    {
        $this->employer = $employer;
    }

    public function checkJobLimit(): void
    {
        $limit = $this->employer->canFeature('job_limit_active');
        $activeJobs = $this->employer->jobPosts()->active()->count();

        if ($limit !== null && $activeJobs >= $limit) {
            abort(403, "You have reached your plan's job posting limit of $limit.");
        }
    }

    public function checkDirectMessaging(): void
    {
        if (!$this->employer->canFeature('direct_messaging')) {
            abort(403, 'Your plan does not allow direct messaging.');
        }
    }

    public function checkCvAccess(): string
    {
        $access = $this->employer->canFeature('cv_access');

        if ($access === 'none') {
            abort(403, 'You cannot access CVs with your current plan.');
        }

        return $access; // 'preview' or 'download'
    }

    public function checkProfileViews(): void
    {
        $limit = $this->employer->canFeature('candidate_profile_views_per_day');
        // implement your logic to count today’s views and abort if limit reached
    }

    // Add more checks as needed for filters, analytics, search visibility, etc.
}