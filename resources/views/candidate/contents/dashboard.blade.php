@extends('candidate.layout')

@section('content')

    <div class="max-w-7xl mx-auto space-y-10">

        {{-- HEADER --}}
        <header class="flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Dashboard
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Welcome back,
                    <span class="font-medium text-gray-800">
                        {{ auth()->user()->name }}
                    </span>
                </p>
            </div>

        </header>



        {{-- STATS --}}
        <section>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <div class="bg-white border border-gray-200 rounded-xl p-5 flex items-center justify-between">

                    <div>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $appliedJobsCount }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Applied
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <x-lucide-icon name="briefcase" class="w-4 h-4 text-blue-600" />
                    </div>

                </div>


                <div class="bg-white border border-gray-200 rounded-xl p-5 flex items-center justify-between">

                    <div>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $savedJobsCount }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Saved
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-lg bg-amber-50 flex items-center justify-center">
                        <x-lucide-icon name="bookmark" class="w-4 h-4 text-amber-600" />
                    </div>

                </div>


                <div class="bg-white border border-gray-200 rounded-xl p-5 flex items-center justify-between">

                    <div>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $followingCount }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Following
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-lg bg-purple-50 flex items-center justify-center">
                        <x-lucide-icon name="users" class="w-4 h-4 text-purple-600" />
                    </div>

                </div>


                <div class="bg-white border border-gray-200 rounded-xl p-5 flex items-center justify-between">

                    <div>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $shortlistedCount }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Shortlisted
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <x-lucide-icon name="check-circle" class="w-4 h-4 text-emerald-600" />
                    </div>

                </div>

            </div>

        </section>



        {{-- MAIN GRID --}}
        <section class="grid grid-cols-1 xl:grid-cols-12 gap-10">



            {{-- LEFT SIDE --}}
            <div class="xl:col-span-8 space-y-10">



                {{-- RECENT APPLICATIONS --}}
                <section>

                    <div class="flex items-center justify-between mb-6">

                        <h2 class="text-lg font-semibold text-gray-900">
                            Recent Applications
                        </h2>

                        <a href="{{ route('candidate.applied.jobs') }}" class="text-sm text-gray-500 hover:text-gray-700">

                            All applications

                        </a>

                    </div>


                    <div class="space-y-3">

                        @foreach ($recentApplications as $application)
                            @php $job = $application->jobPost; @endphp

                            @if ($job)
                                <div
                                    class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-between hover:bg-gray-50 transition">

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-sm font-semibold text-gray-700">
                                            {{ strtoupper(substr($job->title, 0, 2)) }}
                                        </div>

                                        <div>

                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $job->title }}
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                {{ $job->employerProfile->company_name ?? 'Agency' }}
                                            </p>

                                            <p class="text-sm text-gray-400 mt-1">
                                                Applied {{ $application->created_at->diffForHumans() }}
                                            </p>

                                        </div>

                                    </div>

                                    <a href="{{ route('jobs.show', $job->id) }}"
                                        class="text-sm text-emerald-600 hover:text-emerald-700">

                                        Details

                                    </a>

                                </div>
                            @endif
                        @endforeach

                    </div>

                </section>



                {{-- ACTIVITY --}}
                <section>

                    <h2 class="text-lg font-semibold text-gray-900 mb-6">
                        Recent Activity
                    </h2>

                    <div class="bg-white border border-gray-200 rounded-xl divide-y">

                        @foreach ($activities as $activity)
                            <div class="p-4 flex items-start gap-3">

                                <div class="h-9 w-9 rounded-full bg-emerald-100 flex items-center justify-center">
                                    <x-lucide-icon name="clock" class="w-4 h-4 text-emerald-600" />
                                </div>

                                <div>

                                    <p class="text-sm text-gray-700">
                                        Applied to
                                        <span class="font-medium text-gray-900">
                                            {{ $activity->jobPost->title ?? 'Job' }}
                                        </span>
                                    </p>

                                    <p class="text-sm text-gray-400">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </p>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </section>

            </div>



            {{-- RIGHT SIDE --}}
            <aside class="xl:col-span-4 space-y-10">


                {{-- SAVED JOBS --}}
                <section>

                    <div class="flex items-center justify-between mb-6">

                        <h2 class="text-lg font-semibold text-gray-900">
                            Saved Jobs
                        </h2>

                        <a href="{{ route('candidate.saved.jobs') }}" class="text-sm text-gray-500 hover:text-gray-700">

                            Manage

                        </a>

                    </div>

                    <div class="space-y-3">

                        @foreach ($savedPreview as $saved)
                            @php $job = $saved->jobPost; @endphp

                            @if ($job)
                                <div class="bg-white border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition">

                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $job->title }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        {{ $job->employerProfile->company_name ?? 'Agency' }}
                                    </p>

                                </div>
                            @endif
                        @endforeach

                    </div>

                </section>



                {{-- FOLLOWED AGENCIES --}}
                <section>

                    <h2 class="text-lg font-semibold text-gray-900 mb-6">
                        Following Agencies
                    </h2>

                    <div class="space-y-3">

                        @foreach ($followedAgencies as $follow)
                            <div class="flex items-center gap-3">

                                <div
                                    class="h-9 w-9 rounded-lg bg-gray-100 flex items-center justify-center text-sm font-semibold text-gray-700">
                                    {{ strtoupper(substr($follow->employerProfile->company_name ?? 'A', 0, 1)) }}
                                </div>

                                <p class="text-sm text-gray-800">
                                    {{ $follow->employerProfile->company_name ?? 'Agency' }}
                                </p>

                            </div>
                        @endforeach

                    </div>

                </section>

            </aside>

        </section>

    </div>



    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>

@endsection
