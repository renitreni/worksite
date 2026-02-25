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
        $user = Auth::user();
        abort_if(!$user, 403);

        $profile = $user->employerProfile;

        if (!$profile) {
            abort(403, 'Employer profile not found.');
        }

        // ✅ status is now in employer_verifications table
        $verification = $profile->verification; // assumes EmployerProfile has verification() relation

        // optional: ensure row exists (so you don't get null always)
        if (!$verification) {
            $verification = $profile->verification()->create([
                'status' => 'pending',
            ]);
        }

        if ($verification->status !== 'approved') {
            abort(403, 'Employer not approved.');
        }

        return $profile;
    }

    private function requireOwner(JobPost $job)
    {
        // safety: load relation if missing
        $job->loadMissing('employerProfile');

        if (!$job->employerProfile || $job->employerProfile->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
    /**
     * Loads dropdown lists from DB/config (NO external API calls).
     */
    private function taxonomies(): array
    {
        // ✅ industries as models (id+name)
        $industries = \App\Models\Industry::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        // ✅ start empty, will be loaded by AJAX
        $skills = [];

        // Countries from config
        $countries = collect(config('countries', []))
            ->pluck('name')
            ->filter()
            ->values()
            ->toArray();

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

    public function skillsByIndustry(\App\Models\Industry $industry)
    {
        return response()->json(
            \App\Models\Skill::query()
                ->where('industry_id', $industry->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }


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
            'industry_id' => 'required|exists:industries,id',

            'skills' => 'required|array|min:1',
            'skills.*' => 'integer|exists:skills,id',

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

        // ✅ ensure selected skills belong to selected industry
        $industryId = (int) $validated['industry_id'];
        $skillIds = $validated['skills'];

        $skillRows = \App\Models\Skill::where('industry_id', $industryId)
            ->whereIn('id', $skillIds)
            ->get(['id', 'name']);

        if ($skillRows->count() !== count($skillIds)) {
            return back()->withInput()->withErrors([
                'skills' => 'Some selected skills do not belong to the chosen industry.',
            ]);
        }



        // Normalize city/area if "__custom__"
        [$city, $area] = $this->normalizeCityArea($request);
        $validated['city'] = $city;
        $validated['area'] = $area;

        // ✅ store industry name (string column)
        $industry = \App\Models\Industry::findOrFail($industryId);
        $validated['industry'] = $industry->name;

        // ✅ store skills as CSV names (string column)
        // Create/update suggestion if custom typed
        $this->maybeCreateLocationSuggestion($request, (string) $validated['country'], $city, $area);

        // Skills -> CSV
        $validated['skills'] = $skillRows->pluck('name')->implode(',');
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
            'industry_id' => 'required|exists:industries,id',

            'skills' => 'required|array|min:1',
            'skills.*' => 'integer|exists:skills,id',

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

        // ✅ ensure selected skills belong to selected industry
        $industryId = (int) $validated['industry_id'];
        $skillIds = $validated['skills'];

        $skillRows = \App\Models\Skill::where('industry_id', $industryId)
            ->whereIn('id', $skillIds)
            ->get(['id', 'name']);

        if ($skillRows->count() !== count($skillIds)) {
            return back()->withInput()->withErrors([
                'skills' => 'Some selected skills do not belong to the chosen industry.',
            ]);
        }

        // ✅ store industry name (string column)
        $industry = \App\Models\Industry::findOrFail($industryId);
        $validated['industry'] = $industry->name;


        // Normalize city/area if "__custom__"
        [$city, $area] = $this->normalizeCityArea($request);
        $validated['city'] = $city;
        $validated['area'] = $area;

        // Create/update suggestion if custom typed
        $this->maybeCreateLocationSuggestion($request, (string) $validated['country'], $city, $area);

        // Skills -> CSV
        $validated['skills'] = $skillRows->pluck('name')->implode(',');
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
