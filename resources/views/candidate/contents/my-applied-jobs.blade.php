@extends('candidate.layout')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">My Applied Jobs</h1>

        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-end">
            <div class="relative w-full sm:w-72">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i data-lucide="search" class="h-4 w-4"></i>
                </span>
                <input
                    type="text"
                    placeholder="Search by job title..."
                    class="w-full rounded-xl border border-gray-200 bg-white pl-9 pr-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                />
            </div>

            <select
                class="w-full sm:w-44 rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
            >
                <option selected>Newest First</option>
                <option>Oldest First</option>
                <option>Status: Interview</option>
                <option>Status: Applied</option>
                <option>Status: Rejected</option>
            </select>
        </div>
    </div>

    {{-- List --}}
    <div class="space-y-4">
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
    </div>
</div>
@endsection