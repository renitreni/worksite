<?php

namespace App\Services\Employer;

use Illuminate\Support\Facades\Auth;

class EmployerAccessService
{
    public function __construct(
        private EmployerSubscriptionService $subscriptionService,
        private EmployerFeatureService $featureService,
        private EmployerPermissionService $permissionService,
        private EmployerCandidateViewService $viewService
    ) {}

    public function requireApprovedEmployerProfile()
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $profile = $user->employerProfile;
        abort_if(!$profile, 403, 'Employer profile not found.');

        $verification = $profile->verification;

        if (!$verification) {
            $verification = $profile->verification()->create([
                'status' => 'pending'
            ]);
        }

        abort_if($verification->status !== 'approved', 403, 'Employer not approved.');

        return $profile;
    }

    public function resolveAccessForCurrentEmployer(): array
    {
        $profile = $this->requireApprovedEmployerProfile();

        $candidateInfoLevel = $this->featureService
            ->candidateInfoLevelForProfile($profile);

        $cvAccess = $this->featureService
            ->cvAccessForProfile($profile);

        $access = $this->permissionService
            ->accessMatrix($candidateInfoLevel, $cvAccess);

        $access['can_use_advanced_candidate_filters'] =
            $this->featureService->hasAdvancedCandidateFilters($profile);

        return [$profile, $access];
    }

    // proxy methods for controllers

    public function dailyCandidateProfileViewLimit($profile)
    {
        return $this->viewService->dailyCandidateProfileViewLimit($profile);
    }

    public function canViewApplicationToday($profile, int $applicationId)
    {
        return $this->viewService->canViewApplicationToday($profile, $applicationId);
    }

    public function recordApplicationView($profile, int $applicationId, int $candidateProfileId)
    {
        $this->viewService->recordApplicationView($profile, $applicationId, $candidateProfileId);
    }

    public function usedApplicationViewsToday($profile)
    {
        return $this->viewService->usedApplicationViewsToday($profile);
    }

    public function analyticsLevelForProfile($profile)
    {
        return $this->featureService->analyticsLevelForProfile($profile);
    }

    public function canUseDirectMessaging($profile)
    {
        return $this->featureService->canUseDirectMessaging($profile);
    }
}