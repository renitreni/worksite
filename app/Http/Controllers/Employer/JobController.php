<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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

    private function taxonomies(): array
    {
        $industries = \App\Models\Industry::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name');

        // Replace with DB later
        $skills     = ['Welding', 'Driving', 'Caregiving', 'Cooking', 'Cleaning'];
        $countries  = ['Saudi Arabia', 'UAE', 'Qatar', 'Kuwait', 'Japan'];
        $cities     = ['Riyadh', 'Dubai', 'Doha', 'Kuwait City', 'Tokyo'];
        $areas      = ['Downtown', 'Industrial Area', 'Business District'];

        $currencies = Cache::remember('currency_list', now()->addDays(30), function () {
            $res = Http::timeout(10)->get('https://openexchangerates.org/api/currencies.json');
            if (!$res->ok()) return ['PHP' => 'Philippine Peso', 'USD' => 'US Dollar'];
            $data = $res->json();
            asort($data);
            return $data;
        });

        return compact('industries', 'skills', 'countries', 'cities', 'areas', 'currencies');
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
            'city' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',

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

        $validated['skills'] = implode(',', $validated['skills']);
        $validated['posted_at'] = now();
        $validated['status'] = 'open';

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
            'city' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',

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

        $validated['skills'] = implode(',', $validated['skills']);

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
