@extends('candidate.layout')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Following Employers</h1>

        <div class="relative w-full sm:w-80 lg:w-96">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i data-lucide="search" class="h-4 w-4"></i>
            </span>
            <input
                type="text"
                placeholder="Search employers..."
                class="w-full rounded-xl border border-gray-200 bg-white pl-9 pr-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
            />
        </div>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        {{-- Card 1 --}}
        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
            <div class="flex flex-col items-center text-center">
                <div class="h-16 w-16 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-2xl font-semibold">
                    TE
                </div>
                <p class="mt-4 text-sm font-semibold text-gray-900">TechFlow</p>
                <p class="text-xs text-gray-500 mt-1">Software</p>

                <div class="mt-4 flex items-center justify-center gap-4 text-xs text-gray-500">
                    <span class="inline-flex items-center gap-1">
                        <i data-lucide="map-pin" class="h-4 w-4"></i> San Francisco, CA
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <i data-lucide="briefcase" class="h-4 w-4"></i> 12 Open Jobs
                    </span>
                </div>

                <div class="mt-5 flex w-full gap-3">
                    <button class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        View Profile
                    </button>
                    <button class="flex-1 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        Unfollow
                    </button>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
            <div class="flex flex-col items-center text-center">
                <div class="h-16 w-16 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-2xl font-semibold">
                    CS
                </div>
                <p class="mt-4 text-sm font-semibold text-gray-900">Creative Studio</p>
                <p class="text-xs text-gray-500 mt-1">Design Agency</p>

                <div class="mt-4 flex items-center justify-center gap-4 text-xs text-gray-500">
                    <span class="inline-flex items-center gap-1">
                        <i data-lucide="map-pin" class="h-4 w-4"></i> New York, NY
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <i data-lucide="briefcase" class="h-4 w-4"></i> 5 Open Jobs
                    </span>
                </div>

                <div class="mt-5 flex w-full gap-3">
                    <button class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        View Profile
                    </button>
                    <button class="flex-1 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        Unfollow
                    </button>
                </div>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
            <div class="flex flex-col items-center text-center">
                <div class="h-16 w-16 rounded-2xl bg-slate-800 text-white flex items-center justify-center text-2xl font-semibold">
                    GS
                </div>
                <p class="mt-4 text-sm font-semibold text-gray-900">Global Systems</p>
                <p class="text-xs text-gray-500 mt-1">Enterprise IT</p>

                <div class="mt-4 flex items-center justify-center gap-4 text-xs text-gray-500">
                    <span class="inline-flex items-center gap-1">
                        <i data-lucide="map-pin" class="h-4 w-4"></i> Chicago, IL
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <i data-lucide="briefcase" class="h-4 w-4"></i> 24 Open Jobs
                    </span>
                </div>

                <div class="mt-5 flex w-full gap-3">
                    <button class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        View Profile
                    </button>
                    <button class="flex-1 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                        Follow
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
