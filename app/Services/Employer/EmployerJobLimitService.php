<?php

namespace App\Services\Employer;

use App\Models\EmployerSubscription;
use App\Models\JobPost;

class EmployerJobLimitService
{
    public function getActiveSubscriptionForProfile($profile)
    {
        return EmployerSubscription::query()
            ->with([
                'plan' => fn($q) => $q->withTrashed()
                    ->with('featureValues.definition')
            ])
            ->where('employer_profile_id', $profile->id)
            ->where('subscription_status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->orderByDesc('ends_at')
            ->first();
    }

    public function activeJobPostingLimitForProfile($profile)
    {
        $activeSub = $this->getActiveSubscriptionForProfile($profile);

        if (!$activeSub || !$activeSub->plan) {
            return 1;
        }

        $raw = $activeSub->plan->feature('job_limit_active');

        if (is_array($raw)) {
            $raw = $raw['value'] ?? $raw[0] ?? null;
        }

        $rawString = strtolower(trim((string) $raw));

        if ($raw === null || $rawString === '' || $rawString === 'unlimited') {
            return null;
        }

        if ($rawString === '0') {
            return null;
        }

        if (is_numeric($raw)) {
            return (int) $raw;
        }

        return 1;
    }

    public function openJobsCountForProfile($profile)
    {
        return JobPost::where('employer_profile_id', $profile->id)
            ->where('status', 'open')
            ->where('is_disabled', false)
            ->where('is_held', false)
            ->count();
    }

    public function postingLimitState($profile)
    {
        $limit = $this->activeJobPostingLimitForProfile($profile);
        $openCount = $this->openJobsCountForProfile($profile);

        return [
            'limit' => $limit,
            'openCount' => $openCount,
            'exceeded' => ($limit !== null && $openCount >= $limit)
        ];
    }

    public function canPostJob($profile): bool
    {
        return !$this->postingLimitState($profile)['exceeded'];
    }
    public function enforceLimitOrRedirect($profile)
    {
        $limit = $this->activeJobPostingLimitForProfile($profile);
        $currentJobs = $this->openJobsCountForProfile($profile);

        if ($limit !== null && $currentJobs >= $limit) {
            abort(403, 'Job posting limit reached. Please upgrade your plan.');
        }

        return true;
    }
}