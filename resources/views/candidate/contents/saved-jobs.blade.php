@extends('candidate.layout')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">
                Saved Jobs
            </h1>

            <p class="text-sm text-gray-500">
                Jobs you bookmarked appear here.
            </p>
        </div>



        {{-- EMPTY STATE --}}
        @if ($savedJobs->isEmpty())
            <div class="rounded-2xl bg-white border border-gray-200 p-10 text-center">

                <div class="mx-auto h-12 w-12 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center">
                    <i data-lucide="bookmark" class="h-6 w-6 text-gray-400"></i>
                </div>

                <p class="mt-3 text-sm font-semibold text-gray-900">
                    No saved jobs yet
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Save jobs while browsing to see them here.
                </p>

            </div>
        @endif



        {{-- SAVED JOBS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @foreach ($savedJobs as $saved)
                @php
                    $job = $saved->jobPost;
                @endphp

                @if ($job)
                    <x-job-card :job="$job" />
                @endif
            @endforeach

        </div>

    </div>
@endsection
