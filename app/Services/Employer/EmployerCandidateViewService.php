<?php

namespace App\Services\Employer;

use App\Models\CandidateProfileView;

class EmployerCandidateViewService
{
    public function __construct(
        private EmployerSubscriptionService $subscriptionService
    ) {
    }

    public function dailyCandidateProfileViewLimit($profile): ?int
    {
        $activeSub = $this->subscriptionService
            ->getActiveSubscriptionForProfile($profile);

        if (!$activeSub || !$activeSub->plan) {
            return 1;
        }

        $raw = $activeSub->plan->feature('candidate_profile_views_per_day', 1);

        if ($raw === null || $raw === '') {
            return null;
        }

        return max(1, (int) $raw);
    }

    public function canViewApplicationToday($profile, int $jobApplicationId): bool
    {
        $limit = $this->dailyCandidateProfileViewLimit($profile);

        if ($limit === null) {
            return true; // unlimited
        }

        $today = now()->toDateString();

        $alreadyViewed = CandidateProfileView::query()
            ->where('employer_profile_id', $profile->id)
            ->where('job_application_id', $jobApplicationId)
            ->where('view_date', $today)
            ->exists();

        if ($alreadyViewed) {
            return true;
        }

        $countToday = CandidateProfileView::query()
            ->where('employer_profile_id', $profile->id)
            ->where('view_date', $today)
            ->count();

        return $countToday < $limit;
    }

    public function recordApplicationView(
        $profile,
        int $jobApplicationId,
        int $candidateProfileId
    ): void {

        $today = now()->toDateString();

        CandidateProfileView::firstOrCreate(
            [
                'employer_profile_id' => $profile->id,
                'job_application_id' => $jobApplicationId,
                'view_date' => $today,
            ],
            [
                'candidate_profile_id' => $candidateProfileId,
                'viewed_at' => now(),
            ]
        );
    }

    public function usedApplicationViewsToday($profile): int
    {
        return CandidateProfileView::query()
            ->where('employer_profile_id', $profile->id)
            ->where('view_date', now()->toDateString())
            ->count();
    }
}