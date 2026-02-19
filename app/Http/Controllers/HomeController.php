<?php

namespace App\Http\Controllers;

use App\Models\JobPost;

class HomeController extends Controller
{
    public function index()
    {
        // Featured jobs = latest OPEN jobs (you can add a 'is_featured' field later)
        $featuredJobs = JobPost::query()
            ->with(['employerProfile:id,company_name'])
            ->where('status', 'open')
            ->orderByDesc('posted_at')
            ->orderByDesc('created_at')
            ->take(9)
            ->get();

        return view('main', compact('featuredJobs'));
    }
}
