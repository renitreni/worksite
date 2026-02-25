<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\EmployerProfile;
use App\Models\Country;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SEARCH JOBS
    |--------------------------------------------------------------------------
    */

    public function jobs(Request $request)
    {
        $query = JobPost::query()
            ->with('employerProfile')
            ->open()
            ->notHeld()
            ->notDisabled();

        // Keyword
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                  ->orWhere('job_description', 'like', '%' . $request->keyword . '%');
            });
        }

        // Country
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        // Filters
        if ($request->boolean('no_fee')) {
            $query->where('placement_fee', 0);
        }

        if ($request->boolean('hs_grad')) {
            $query->where('education_level', 'high_school');
        }

        if ($request->boolean('college_grad')) {
            $query->where('education_level', 'college');
        }

        if ($request->boolean('masteral')) {
            $query->where('education_level', 'masteral');
        }

        if ($request->boolean('phd')) {
            $query->where('education_level', 'phd');
        }

        if ($request->boolean('no_exp')) {
            $query->where('min_experience_years', 0);
        }

        $jobs = $query->latest('posted_at')
                      ->paginate(9)
                      ->withQueryString();

        return view('mainpage.search-jobs-page.search-jobs', compact('jobs'));
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH AGENCY
    |--------------------------------------------------------------------------
    */

    public function agency(Request $request)
    {
        $agencies = EmployerProfile::query()
            ->when($request->filled('keyword'), function ($q) use ($request) {
                $q->where('company_name', 'like', '%' . $request->keyword . '%');
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('mainpage.search-jobs-page.search-agency', compact('agencies'));
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH INDUSTRIES
    |--------------------------------------------------------------------------
    */

    public function industries()
    {
        return view('mainpage.search-jobs-page.search-industries');
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH COUNTRY
    |--------------------------------------------------------------------------
    */

    public function country()
    {
        $countries = Country::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('mainpage.search-jobs-page.search-country', compact('countries'));
    }
}