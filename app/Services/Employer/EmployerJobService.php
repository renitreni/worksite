<?php

namespace App\Services\Employer;

use App\Models\JobPost;
use App\Models\Industry;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;

class EmployerJobService
{
    public function __construct(
        private EmployerAccessService $accessService,
        private EmployerJobLimitService $limitService,
        private EmployerTaxonomyService $taxonomyService,
        private EmployerLocationService $locationService
    ) {
    }

    public function getActiveJobs()
    {
        $profile = $this->accessService->requireApprovedEmployerProfile();

        return $profile->jobPosts()
            ->where('status', 'open')
            ->latest()
            ->get();
    }

    public function getClosedJobs()
    {
        $profile = $this->accessService->requireApprovedEmployerProfile();

        return $profile->jobPosts()
            ->where('status', 'closed')
            ->latest()
            ->get();
    }

    public function getCreatePageData(): array
    {
        $profile = $this->accessService->requireApprovedEmployerProfile();

        $limitState = $this->limitService->postingLimitState($profile);

        if ($limitState['exceeded']) {
            return [
                'limitReached' => true,
                'limitData' => $limitState
            ];
        }

        return array_merge(
            [
                'limitReached' => false
            ],
            $this->taxonomyService->taxonomies()
        );
    }

    public function getEditPageData(JobPost $job): array
    {
        $this->authorizeOwner($job);

        return array_merge(
            [
                'job' => $job
            ],
            $this->taxonomyService->taxonomies()
        );
    }

    public function updateJob($request, JobPost $job)
    {
        $this->authorizeOwner($job);

        $validated = $this->validateJob($request);

        [$city, $area] = $this->locationService->normalizeCityArea($request);

        $validated['city'] = $city;
        $validated['area'] = $area;

        $industry = Industry::findOrFail($validated['industry_id']);

        $skills = Skill::where('industry_id', $industry->id)
            ->whereIn('id', $validated['skills'])
            ->pluck('name');

        $validated['industry'] = $industry->name;
        $validated['skills'] = $skills->implode(',');

        unset($validated['city_custom'], $validated['area_custom']);

        $job->update($validated);
    }

    public function storeJob($request)
    {
        $profile = $this->accessService->requireApprovedEmployerProfile();

        $this->limitService->enforceLimitOrRedirect($profile);

        $validated = $this->validateJob($request);

        [$city, $area] = $this->locationService->normalizeCityArea($request);

        $validated['city'] = $city;
        $validated['area'] = $area;

        $industry = Industry::findOrFail($validated['industry_id']);

        $skills = Skill::where('industry_id', $industry->id)
            ->whereIn('id', $validated['skills'])
            ->pluck('name');

        $validated['industry'] = $industry->name;
        $validated['skills'] = $skills->implode(',');

        $validated['posted_at'] = now();
        $validated['status'] = 'open';

        unset($validated['city_custom'], $validated['area_custom']);

        $profile->jobPosts()->create($validated);
    }

    public function authorizeOwner(JobPost $job)
    {
        $job->loadMissing('employerProfile');

        if ($job->employerProfile->user_id !== Auth::id()) {
            abort(403);
        }
    }

    public function closeJob(JobPost $job)
    {
        $this->authorizeOwner($job);

        $job->update(['status' => 'closed']);
    }

    public function reopenJob(JobPost $job)
    {
        $this->authorizeOwner($job);

        $profile = $this->accessService->requireApprovedEmployerProfile();

        $this->limitService->enforceLimitOrRedirect($profile);

        $job->update(['status' => 'open']);
    }

    private function validateJob($request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'industry_id' => 'required|exists:industries,id',
            'skills' => 'required|array|min:1',
            'skills.*' => 'integer|exists:skills,id',

            'country' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',

            'job_description' => 'required|string',

            'job_qualifications' => 'nullable|string',
            'additional_information' => 'nullable|string',

            'principal_employer' => 'nullable|string|max:255',
            'dmw_registration_no' => 'nullable|string|max:255',
            'principal_employer_address' => 'nullable|string|max:255',

            'placement_fee' => 'nullable|string|max:255',
            'placement_fee_currency' => 'nullable|string|max:10',

            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'salary_currency' => 'nullable|string|max:10',

            'apply_until' => 'nullable|date|after_or_equal:today',
            'min_experience_years' => 'nullable|integer|min:0|max:50',
        ]);
    }
}