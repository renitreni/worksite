<?php

namespace App\Services\Employer;

class EmployerFeatureService
{
    public function __construct(
        private EmployerSubscriptionService $subscriptionService
    ) {}

    private function featureScalar($plan, string $key, $default = null): string
    {
        if (!$plan) {
            return strtolower(trim((string) $default));
        }

        $raw = $plan->feature($key, $default);

        if (is_array($raw)) {
            $raw = $raw['value'] ?? ($raw[0] ?? $default);
        }

        return strtolower(trim((string) $raw));
    }

    public function candidateInfoLevelForProfile($profile): string
    {
        $sub = $this->subscriptionService->getActiveSubscriptionForProfile($profile);

        if (!$sub || !$sub->plan) {
            return 'default';
        }

        $val = $this->featureScalar($sub->plan,'candidate_info_level','default');

        return in_array($val,['default','basic_preview','expanded','full'],true)
            ? $val : 'default';
    }

    public function cvAccessForProfile($profile): string
    {
        $sub = $this->subscriptionService->getActiveSubscriptionForProfile($profile);

        if (!$sub || !$sub->plan) {
            return 'none';
        }

        $val = $this->featureScalar($sub->plan,'cv_access','default');

        return match ($val) {
            'download' => 'download',
            'preview' => 'preview',
            default => 'none',
        };
    }

    public function hasAdvancedCandidateFilters($profile): bool
    {
        $sub = $this->subscriptionService->getActiveSubscriptionForProfile($profile);

        if (!$sub || !$sub->plan) {
            return false;
        }

        $val = $this->featureScalar($sub->plan,'advanced_candidate_filters','false');

        return in_array($val,['1','true','yes'],true);
    }

    public function analyticsLevelForProfile($profile): string
    {
        $sub = $this->subscriptionService->getActiveSubscriptionForProfile($profile);

        if (!$sub || !$sub->plan) {
            return 'default';
        }

        $val = $this->featureScalar($sub->plan,'analytics_level','default');

        return in_array($val,['default','basic','advanced','enterprise'],true)
            ? $val : 'default';
    }

    public function canUseDirectMessaging($profile): bool
    {
        $sub = $this->subscriptionService->getActiveSubscriptionForProfile($profile);

        if (!$sub || !$sub->plan) {
            return false;
        }

        $val = $this->featureScalar($sub->plan,'direct_messaging','false');

        return in_array($val,['1','true','yes'],true);
    }
}