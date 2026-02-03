@extends('candidate.layout')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="space-y-1">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500">
            Welcome back, <span class="font-semibold text-gray-700">Sarah Jenkins</span> !
        </p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-2xl font-bold text-gray-900">12</p>
                <p class="text-sm text-gray-500 mt-1">Applied Jobs</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                <i data-lucide="briefcase" class="h-6 w-6 text-blue-600"></i>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-2xl font-bold text-gray-900">4</p>
                <p class="text-sm text-gray-500 mt-1">Reviews</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center">
                <i data-lucide="star" class="h-6 w-6 text-amber-600"></i>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-2xl font-bold text-gray-900">148</p>
                <p class="text-sm text-gray-500 mt-1">Profile Views</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center">
                <i data-lucide="eye" class="h-6 w-6 text-purple-600"></i>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-2xl font-bold text-gray-900">8</p>
                <p class="text-sm text-gray-500 mt-1">Shortlisted</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                <i data-lucide="bookmark" class="h-6 w-6 text-emerald-600"></i>
            </div>
        </div>
    </div>

    {{-- Main grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        {{-- Recently Applied --}}
        <section class="xl:col-span-8 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-900">Recently Applied</h2>
                <a href="#" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900">
                    View All <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </div>

            {{-- Item 1 --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="h-14 w-14 rounded-2xl bg-blue-600 flex items-center justify-center text-white font-semibold">
                            TE
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Senior Product Designer</p>
                            <p class="text-sm text-blue-600 font-semibold mt-1">TechFlow</p>

                            <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="map-pin" class="h-4 w-4"></i> Remote
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="clock" class="h-4 w-4"></i> Full-time
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="dollar-sign" class="h-4 w-4"></i> $120k - $150k
                                </span>
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 px-2 py-0.5 font-semibold">
                                    Interview
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="calendar" class="h-3.5 w-3.5"></i> Applied: 2023-10-15
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex md:justify-end">
                        <button class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            <i data-lucide="eye" class="h-4 w-4"></i> View Details
                        </button>
                    </div>
                </div>
            </div>

            {{-- Item 2 --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="h-14 w-14 rounded-2xl bg-emerald-500 flex items-center justify-center text-white font-semibold">
                            CS
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900">UX Researcher</p>
                            <p class="text-sm text-blue-600 font-semibold mt-1">Creative Studio</p>

                            <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="map-pin" class="h-4 w-4"></i> New York, NY
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="briefcase" class="h-4 w-4"></i> Contract
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="dollar-sign" class="h-4 w-4"></i> $90k - $110k
                                </span>
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 border border-blue-100 px-2 py-0.5 font-semibold">
                                    Applied
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="calendar" class="h-3.5 w-3.5"></i> Applied: 2023-10-12
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex md:justify-end">
                        <button class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            <i data-lucide="eye" class="h-4 w-4"></i> View Details
                        </button>
                    </div>
                </div>
            </div>

            {{-- Item 3 --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="h-14 w-14 rounded-2xl bg-red-500 flex items-center justify-center text-white font-semibold">
                            WE
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Frontend Developer</p>
                            <p class="text-sm text-blue-600 font-semibold mt-1">WebSolutions</p>

                            <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="map-pin" class="h-4 w-4"></i> Austin, TX
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="clock" class="h-4 w-4"></i> Full-time
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="dollar-sign" class="h-4 w-4"></i> $100k - $130k
                                </span>
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                <span class="inline-flex items-center rounded-full bg-red-50 text-red-700 border border-red-100 px-2 py-0.5 font-semibold">
                                    Rejected
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="calendar" class="h-3.5 w-3.5"></i> Applied: 2023-10-08
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex md:justify-end">
                        <button class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            <i data-lucide="eye" class="h-4 w-4"></i> View Details
                        </button>
                    </div>
                </div>
            </div>
        </section>

        {{-- Notifications --}}
        <aside class="xl:col-span-4 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-900">Notifications</h2>
                <a href="#" class="text-sm font-semibold text-gray-500 hover:text-gray-700">Mark all as read</a>
            </div>

            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm divide-y divide-gray-100">
                <div class="p-5 flex items-start gap-4">
                    <div class="h-11 w-11 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                        <i data-lucide="calendar-check" class="h-5 w-5 text-emerald-600"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-gray-900">Interview Scheduled</p>
                            <p class="text-xs text-gray-400 whitespace-nowrap">2 hours ago</p>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">
                            TechFlow has scheduled an interview for Senior Product Designer role.
                        </p>
                    </div>
                </div>

                <div class="p-5 flex items-start gap-4">
                    <div class="h-11 w-11 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                        <i data-lucide="eye" class="h-5 w-5 text-blue-600"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-gray-900">Application Viewed</p>
                            <p class="text-xs text-gray-400 whitespace-nowrap">5 hours ago</p>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">
                            Creative Studio viewed your application for UX Researcher.
                        </p>
                    </div>
                </div>

                <div class="p-5 flex items-start gap-4">
                    <div class="h-11 w-11 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center">
                        <i data-lucide="bell" class="h-5 w-5 text-amber-600"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-gray-900">New Job Alert</p>
                            <p class="text-xs text-gray-400 whitespace-nowrap">1 day ago</p>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">
                            3 new jobs match your “Remote Designer” alert.
                        </p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection