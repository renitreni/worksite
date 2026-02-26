<?php

namespace App\Services;

use App\Models\EmployerSubscription;
use Illuminate\Support\Facades\Auth;
use App\Models\CandidateProfileView;


class EmployerAccessService
{
    public function requireApprovedEmployerProfile()
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $profile = $user->employerProfile;
        abort_if(!$profile, 403, 'Employer profile not found.');

        $verification = $profile->verification;
        if (!$verification) {
            $verification = $profile->verification()->create(['status' => 'pending']);
        }

        abort_if($verification->status !== 'approved', 403, 'Employer not approved.');

        return $profile;
    }

    public function getActiveSubscriptionForProfile($profile): ?EmployerSubscription
    {
        return EmployerSubscription::query()
            ->with([
                // make sure plan features are loaded
                'plan' => fn($q) => $q->withTrashed()->with('featureValues.definition'),
            ])
            ->where('employer_profile_id', $profile->id)
            ->where('subscription_status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderByDesc('ends_at')
            ->first();
    }

    // ----------------------------
    // Feature parsing helpers
    // ----------------------------

    private function featureScalar($plan, string $key, $default = null): string
    {
        if (!$plan)
            return strtolower(trim((string) $default));

        $raw = $plan->feature($key, $default);

        // your PlanFeature casts value as array,
        // but DB can contain scalar / string / boolean / null.
        if (is_array($raw)) {
            $raw = $raw['value'] ?? ($raw[0] ?? $default);
        }

        return strtolower(trim((string) $raw));
    }

    /**
     * candidate_info_level feature values expected:
     * default | basic_preview | expanded | full
     */
    public function candidateInfoLevelForProfile($profile): string
    {
        $activeSub = $this->getActiveSubscriptionForProfile($profile);

        if (!$activeSub || !$activeSub->plan) {
            return 'default'; // treat as basic
        }

        $val = $this->featureScalar($activeSub->plan, 'candidate_info_level', 'default');

        return in_array($val, ['default', 'basic_preview', 'expanded', 'full'], true)
            ? $val
            : 'default';
    }

    /**
     * cv_access feature values expected:
     * default | none | preview | download
     */
    public function cvAccessForProfile($profile): string
    {
        $activeSub = $this->getActiveSubscriptionForProfile($profile);

        if (!$activeSub || !$activeSub->plan) {
            return 'none';
        }

        $val = $this->featureScalar($activeSub->plan, 'cv_access', 'default');

        return match ($val) {
            'download' => 'download',
            'preview' => 'preview',
            'none', 'default', '' => 'none',
            default => 'none',
        };
    }

    // ----------------------------
    // Access Matrix (dynamic)
    // ----------------------------

    public function accessMatrix(string $candidateInfoLevel, string $cvAccess): array
    {
        // Candidate info access (based on candidate_info_level)
        $access = match ($candidateInfoLevel) {
            // STANDARD equivalent
            'basic_preview' => [
                'level' => 'basic_preview',

                'can_view_profile_picture' => true,
                'can_view_full_name' => true,
                'can_view_birthdate' => true,
                'can_view_years_experience' => true,
                'can_view_highest_education' => true,
                'can_view_address_city_province_only' => true,
                'can_view_short_bio' => true,

                // locked
                'can_view_work_history' => false,
                'can_view_education_history' => false,
                'can_view_social_links' => false,

                // locked
                'can_download_documents' => false,
                'can_view_full_contact_info' => false,
            ],

            // GOLD equivalent
            'expanded' => [
                'level' => 'expanded',

                'can_view_profile_picture' => true,
                'can_view_full_name' => true,
                'can_view_birthdate' => true,
                'can_view_years_experience' => true,
                'can_view_highest_education' => true,
                'can_view_address_city_province_only' => true,
                'can_view_short_bio' => true,

                // unlocked
                'can_view_work_history' => true,
                'can_view_education_history' => true,
                'can_view_social_links' => true,

                // locked
                'can_download_documents' => false,
                'can_view_full_contact_info' => false,
            ],

            // PLATINUM equivalent
            'full' => [
                'level' => 'full',

                'can_view_profile_picture' => true,
                'can_view_full_name' => true,
                'can_view_birthdate' => true,
                'can_view_years_experience' => true,
                'can_view_highest_education' => true,
                'can_view_address_city_province_only' => true,
                'can_view_short_bio' => true,

                // unlocked
                'can_view_work_history' => true,
                'can_view_education_history' => true,
                'can_view_social_links' => true,

                // full unlocked
                'can_download_documents' => true,      // download implies preview
                'can_view_full_contact_info' => true,
            ],

            // DEFAULT => BASIC equivalent
            default => [
                'level' => 'default',

                'can_view_profile_picture' => false,
                'can_view_full_name' => true,
                'can_view_birthdate' => false,
                'can_view_years_experience' => true,
                'can_view_highest_education' => false,
                'can_view_address_city_province_only' => true,
                'can_view_short_bio' => false,

                'can_view_work_history' => false,
                'can_view_education_history' => false,
                'can_view_social_links' => false,

                'can_download_documents' => false,
                'can_view_full_contact_info' => false,
            ],
        };

        // CV access is separate feature (based on cv_access)
        $access['can_preview_cv'] = in_array($cvAccess, ['preview', 'download'], true);
        $access['can_download_cv'] = ($cvAccess === 'download');

        // download implies preview
        if ($access['can_download_cv']) {
            $access['can_preview_cv'] = true;
        }

        return $access;
    }

    public function resolveAccessForCurrentEmployer(): array
    {
        $profile = $this->requireApprovedEmployerProfile();

        $candidateInfoLevel = $this->candidateInfoLevelForProfile($profile);
        $cvAccess = $this->cvAccessForProfile($profile);

        $access = $this->accessMatrix($candidateInfoLevel, $cvAccess);

        return [$profile, $access];
    }

    public function dailyCandidateProfileViewLimit($profile): ?int
    {
        $activeSub = $this->getActiveSubscriptionForProfile($profile);

        // no subscription => optional: treat as 0 or small limit, ikaw bahala
        if (!$activeSub || !$activeSub->plan) {
            return 0; // or null if you want unlimited for basic (but usually 0 or small)
        }

        $raw = $activeSub->plan->feature('candidate_profile_views_per_day', null);

        // admin said: blank = unlimited (NULL)
        if ($raw === null || $raw === '')
            return null;

        $n = (int) $raw;
        return $n < 0 ? 0 : $n;
    }

    public function canViewApplicationToday($profile, int $jobApplicationId): bool
    {
        $limit = $this->dailyCandidateProfileViewLimit($profile);

        if ($limit === null)
            return true; // unlimited

        $today = now()->toDateString();

        // already viewed this application today => allowed
        $already = CandidateProfileView::query()
            ->where('employer_profile_id', $profile->id)
            ->where('job_application_id', $jobApplicationId)
            ->where('view_date', $today)
            ->exists();

        if ($already)
            return true;

        // count viewed applications today
        $countToday = CandidateProfileView::query()
            ->where('employer_profile_id', $profile->id)
            ->where('view_date', $today)
            ->count();

        return $countToday < $limit;
    }

    public function recordApplicationView($profile, int $jobApplicationId, int $candidateProfileId): void
    {
        $today = now()->toDateString();

        CandidateProfileView::firstOrCreate(
            [
                'employer_profile_id' => $profile->id,
                'job_application_id' => $jobApplicationId,
                'view_date' => $today,
            ],
            [
                'candidate_profile_id' => $candidateProfileId, // keep for reference
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