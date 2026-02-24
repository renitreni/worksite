@extends('mainpage.agency-details-page.layout')

@section('title', 'Agency Profile | Worksite')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- HEADER --}}
        @include('mainpage.agency-details-page.agency.partials._header', [
            'agency' => $agency,
            'openJobsCount' => $openJobsCount
        ])

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- LEFT --}}
            <div class="lg:col-span-1 space-y-6">
                @include('mainpage.agency-details-page.agency.partials._about', [
                    'agency' => $agency,
                    'openJobsCount' => $openJobsCount
                ])
            </div>

            {{-- RIGHT --}}
            <div class="lg:col-span-2">
                @include('mainpage.agency-details-page.agency.partials._jobs', [
                    'jobs' => $jobs
                ])
            </div>
        </div>

    </div>
@endsection