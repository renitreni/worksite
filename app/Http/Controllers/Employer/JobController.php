<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\LocationSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use App\Models\City;
use App\Models\Area;
use Illuminate\Support\Facades\Log;
use App\Models\Country;

class JobController extends Controller
{
    // ----------------------------
    // Helpers
    // ----------------------------
    private function requireApprovedEmployerProfile()
    {
        $profile = Auth::user()->employerProfile;

        if (!$profile || $profile->status !== 'approved') {
            abort(403, 'Employer not approved.');
        }

        return $profile;
    }

    private function requireOwner(JobPost $job)
    {
        if ($job->employerProfile->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Loads dropdown lists from DB/config (NO external API calls).
     */
    private function taxonomies(): array
    {
        // Industries
        $industries = \App\Models\Industry::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name')
            ->values()
            ->toArray();

        // Skills
        if (Schema::hasTable('skills')) {
            $skillsQ = \App\Models\Skill::query();
            if (Schema::hasColumn('skills', 'is_active')) {
                $skillsQ->where('is_active', true);
            }
            $skills = $skillsQ->orderBy('name')->pluck('name')->values()->toArray();
        } else {
            $skills = ['Welding', 'Driving', 'Caregiving', 'Cooking', 'Cleaning'];
        }

        // Countries from config
        $countries = collect(config('countries', []))
            ->pluck('name')
            ->filter()
            ->values()
            ->toArray();

        // ✅ Start empty, will be loaded by AJAX
        $cities = [];
        $areas  = [];

        $currencies = config('currencies', []);
        asort($currencies);

        return compact('industries', 'skills', 'countries', 'cities', 'areas', 'currencies');
    }

    /**
     * Normalize city/area values when select uses "__custom__".
     * Returns [$city, $area]
     */


    public function citiesByCountry(Request $request)
    {
        $countryName = $request->query('country');
        if (!$countryName) return response()->json([]);

        // Find country by name
        $country = Country::query()
            ->where('name', $countryName)
            ->first();

        if (!$country) {
            return response()->json([]); // country not found in DB
        }

        $q = City::query()->where('country_id', $country->id);

        if (Schema::hasColumn('cities', 'is_active')) {
            $q->where('is_active', true);
        }

        return response()->json(
            $q->orderBy('sort_order')
                ->orderBy('name')
                ->pluck('name')
                ->values()
        );
    }

    public function areasByCity(Request $request)
    {
        $countryName = $request->query('country');
        $cityName = $request->query('city');

        if (!$countryName || !$cityName) return response()->json([]);

        $country = Country::query()
            ->where('name', $countryName)
            ->first();

        if (!$country) return response()->json([]);

        // Find the city under that country
        $city = City::query()
            ->where('country_id', $country->id)
            ->where('name', $cityName)
            ->first();

        if (!$city) return response()->json([]);

        $areasQ = Area::query()->where('city_id', $city->id);

        if (Schema::hasColumn('areas', 'is_active')) {
            $areasQ->where('is_active', true);
        }

        return response()->json(
            $areasQ->orderBy('sort_order')
                ->orderBy('name')
                ->pluck('name')
                ->values()
        );
    }
    private function normalizeCityArea(Request $request): array
    {
        $city = $request->input('city');
        $area = $request->input('area');

        if ($city === '__custom__') {
            $city = trim((string) $request->input('city_custom', ''));
        }
        if ($area === '__custom__') {
            $area = trim((string) $request->input('area_custom', ''));
        }

        $city = $city !== '' ? $city : null;
        $area = $area !== '' ? $area : null;

        return [$city, $area];
    }

    /**
     * Create/update location suggestion when user typed custom city/area.
     */
    private function maybeCreateLocationSuggestion(Request $request, string $country, ?string $city, ?string $area): void
    {
        // Only create if employer used custom option
        $usedCustom = ($request->input('city') === '__custom__') || ($request->input('area') === '__custom__');
        if (!$usedCustom) return;

        $country = trim($country);
        if ($country === '') return;

        // Only if there is something to suggest
        if (!$city && !$area) return;

        $suggestion = LocationSuggestion::firstOrCreate(
            [
                'country' => $country,
                'city'    => $city,
                'area'    => $area,
            ],
            [
                'count'  => 0,
                'status' => 'pending',
            ]
        );

        $suggestion->increment('count');

        // If previously ignored, push back to pending when someone suggests again
        if ($suggestion->status === 'ignored') {
            $suggestion->update(['status' => 'pending']);
        }
    }

    // ----------------------------
    // Lists
    // ----------------------------
    public function index()
    {
        $profile = $this->requireApprovedEmployerProfile();

        $jobs = $profile->jobPosts()
            ->where('status', 'open')
            ->latest()
            ->get();

        return view('employer.contents.job-postings.active', compact('jobs'));
    }

    public function closed()
    {
        $profile = $this->requireApprovedEmployerProfile();

        $jobs = $profile->jobPosts()
            ->where('status', 'closed')
            ->latest()
            ->get();

        return view('employer.contents.job-postings.closed', compact('jobs'));
    }

    // ----------------------------
    // Create / Store
    // ----------------------------
    public function create()
    {
        $this->requireApprovedEmployerProfile();

        return view('employer.contents.job-postings.create', $this->taxonomies());
    }

    public function store(Request $request)
    {
        $profile = $this->requireApprovedEmployerProfile();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'industry' => 'required|string|max:255',

            'skills' => 'required|array|min:1',
            'skills.*' => 'string|max:255',

            'country' => 'required|string|max:255',

            // dropdown values (may be "__custom__")
            'city' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',

            // typed values (required only when dropdown is "__custom__")
            'city_custom' => 'nullable|required_if:city,__custom__|string|max:255',
            'area_custom' => 'nullable|required_if:area,__custom__|string|max:255',

            'min_experience_years' => 'nullable|integer|min:0|max:60',

            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'salary_currency' => 'nullable|string|max:10',

            'gender' => 'required|in:male,female,both',
            'age_min' => 'nullable|integer|min:0|max:99',
            'age_max' => 'nullable|integer|min:0|max:99',

            'apply_until' => 'nullable|date|after_or_equal:today',

            'job_description' => 'required|string',
            'job_qualifications' => 'nullable|string',
            'additional_information' => 'nullable|string',

            'principal_employer' => 'nullable|string|max:255',
            'dmw_registration_no' => 'nullable|string|max:255',
            'principal_employer_address' => 'nullable|string|max:255',

            'placement_fee' => 'nullable|numeric|min:0',
            'placement_fee_currency' => 'nullable|string|max:10',
        ]);

        // Normalize city/area if "__custom__"
        [$city, $area] = $this->normalizeCityArea($request);
        $validated['city'] = $city;
        $validated['area'] = $area;

        // Create/update suggestion if custom typed
        $this->maybeCreateLocationSuggestion($request, (string) $validated['country'], $city, $area);

        // Skills -> CSV
        $validated['skills'] = implode(',', $validated['skills']);
        $validated['posted_at'] = now();
        $validated['status'] = 'open';

        // Clean extra fields not in job_posts table (if job_posts doesn't have these)
        unset($validated['city_custom'], $validated['area_custom']);

        $profile->jobPosts()->create($validated);

        return redirect()->route('employer.job-postings.index')
            ->with('success', 'Job posted successfully!');
    }

    // ----------------------------
    // Show
    // ----------------------------
    public function show(JobPost $job)
    {
        $this->requireOwner($job);

        return view('employer.contents.job-postings.show', compact('job'));
    }

    // ----------------------------
    // Edit / Update
    // ----------------------------
    public function edit(JobPost $job)
    {
        $this->requireOwner($job);

        return view('employer.contents.job-postings.edit', array_merge(
            ['job' => $job],
            $this->taxonomies()
        ));
    }

    public function update(Request $request, JobPost $job)
    {
        $this->requireOwner($job);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'industry' => 'required|string|max:255',

            'skills' => 'required|array|min:1',
            'skills.*' => 'string|max:255',

            'country' => 'required|string|max:255',

            // dropdown values (may be "__custom__")
            'city' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',

            // typed values (required only when dropdown is "__custom__")
            'city_custom' => 'nullable|required_if:city,__custom__|string|max:255',
            'area_custom' => 'nullable|required_if:area,__custom__|string|max:255',

            'min_experience_years' => 'nullable|integer|min:0|max:60',

            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'salary_currency' => 'nullable|string|max:10',

            'gender' => 'required|in:male,female,both',
            'age_min' => 'nullable|integer|min:0|max:99',
            'age_max' => 'nullable|integer|min:0|max:99',

            'apply_until' => 'nullable|date',

            'job_description' => 'required|string',
            'job_qualifications' => 'nullable|string',
            'additional_information' => 'nullable|string',

            'principal_employer' => 'nullable|string|max:255',
            'dmw_registration_no' => 'nullable|string|max:255',
            'principal_employer_address' => 'nullable|string|max:255',

            'placement_fee' => 'nullable|numeric|min:0',
            'placement_fee_currency' => 'nullable|string|max:10',

            'status' => 'nullable|in:open,closed',
        ]);

        // Normalize city/area if "__custom__"
        [$city, $area] = $this->normalizeCityArea($request);
        $validated['city'] = $city;
        $validated['area'] = $area;

        // Create/update suggestion if custom typed
        $this->maybeCreateLocationSuggestion($request, (string) $validated['country'], $city, $area);

        // Skills -> CSV
        $validated['skills'] = implode(',', $validated['skills']);

        // Remove fields not in job_posts table
        unset($validated['city_custom'], $validated['area_custom']);

        $job->update($validated);

        return redirect()->route('employer.job-postings.show', $job->id)
            ->with('success', 'Job updated successfully!');
    }

    // ----------------------------
    // Close / Reopen
    // ----------------------------
    public function destroy(JobPost $job)
    {
        $this->requireOwner($job);

        // soft-close
        $job->update(['status' => 'closed']);

        return redirect()->route('employer.job-postings.index')
            ->with('info', 'Job closed successfully.');
    }

    public function reopen(JobPost $job)
    {
        $this->requireOwner($job);

        if ($job->status !== 'closed') {
            return redirect()->back()->with('error', 'Job is already open.');
        }

        $job->update(['status' => 'open']);

        return redirect()->route('employer.job-postings.index')
            ->with('success', 'Job reopened successfully!');
    }
}
