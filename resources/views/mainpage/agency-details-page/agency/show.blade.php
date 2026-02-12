@extends('mainpage.agency-details-page.layout')

@section('title', 'Agency Profile | Worksite')

@php
    $agency = [
        'name' => 'Global Workforce Solutions',
        'jobs_available' => 145,
        'description' => 'Leading recruitment agency specializing in healthcare and tech placements nationwide.',
        'location' => 'New York, USA',
        'email' => 'contact@globalworkforce.com',
        'phone' => '+1 (555) 123-4567',
        'website' => 'globalworkforce.com',
        'tags' => ['Healthcare', 'IT', 'Manufacturing'],

        // NEW: cover + logo
        'cover_image' => asset('images//usa-flag.webp'),     // put your USA flag image here
        'logo_image' => asset('images/4.png'),  // put company logo here
    ];

    $jobs = collect([
        ['title' => 'Registered Nurse (RN)', 'type' => 'Full-time', 'location' => 'New York, USA', 'salary' => '$3,500 - $4,200 / month', 'posted' => '2 days ago'],
        ['title' => 'IT Support Specialist', 'type' => 'Full-time', 'location' => 'Remote', 'salary' => '$900 - $1,200 / month', 'posted' => '4 days ago'],
        ['title' => 'Warehouse Associate', 'type' => 'Contract', 'location' => 'New Jersey, USA', 'salary' => '$650 - $800 / month', 'posted' => '1 week ago'],
        ['title' => 'Factory Production Operator', 'type' => 'Full-time', 'location' => 'Pennsylvania, USA', 'salary' => '$700 - $950 / month', 'posted' => '1 week ago'],
        ['title' => 'Healthcare Assistant', 'type' => 'Part-time', 'location' => 'New York, USA', 'salary' => '$400 - $650 / month', 'posted' => '2 weeks ago'],
    ]);
@endphp

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">


        {{-- FACEBOOK STYLE HEADER (cover + logo overlap + name/actions) --}}
        @include('mainpage.agency-details-page.agency.partials._header', ['agency' => $agency])

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- LEFT: intro/details card --}}
            <div class="lg:col-span-1 space-y-6">
                @include('mainpage.agency-details-page.agency.partials._about', ['agency' => $agency])
            </div>

            {{-- RIGHT: jobs --}}
            <div class="lg:col-span-2">
                @include('mainpage.agency-details-page.agency.partials._jobs', ['jobs' => $jobs])
            </div>
        </div>

    </div>
@endsection