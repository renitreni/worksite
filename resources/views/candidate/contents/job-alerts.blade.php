@extends('candidate.layout')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Job Alerts</h1>

            <button type="button"
                class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition">
                <i data-lucide="bell-plus" class="h-5 w-5"></i>
                <span>Create New Alert</span>
            </button>
        </div>

        {{-- Cards --}}
        <div class="space-y-4">
            {{-- Card 1: Active --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm">
                <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                    {{-- Left --}}
                    <div class="min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Senior UX Designer</h2>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-100">
                                Active
                            </span>
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                            <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">Criteria</p>
                                <p class="mt-1 font-medium text-gray-900">Remote, Full-time, &gt;$100k</p>
                            </div>
                            <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">Frequency</p>
                                <p class="mt-1 font-medium text-gray-900">Daily</p>
                            </div>
                            <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">Created</p>
                                <p class="mt-1 font-medium text-gray-900">Sep 15, 2023</p>
                            </div>
                        </div>
                    </div>

                    {{-- Right actions --}}
                    <div class="flex flex-wrap items-center gap-2 lg:justify-end">
                        <button type="button"
                            class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 hover:bg-gray-50 transition">
                            <i data-lucide="search" class="h-4 w-4 text-gray-600"></i>
                            <span>Show Jobs</span>
                        </button>

                        <button type="button"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition"
                            title="Edit">
                            <i data-lucide="pencil" class="h-5 w-5 text-gray-600"></i>
                        </button>

                        <button type="button"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-amber-200 bg-amber-50 hover:bg-amber-100 transition"
                            title="Pause">
                            <i data-lucide="pause" class="h-5 w-5 text-amber-700"></i>
                        </button>

                        <button type="button"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-red-200 bg-red-50 hover:bg-red-100 transition"
                            title="Delete">
                            <i data-lucide="trash-2" class="h-5 w-5 text-red-600"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Card 2: Paused --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm">
                <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                    {{-- Left --}}
                    <div class="min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Product Designer in SF</h2>
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 border border-gray-200">
                                Paused
                            </span>
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                            <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">Criteria</p>
                                <p class="mt-1 font-medium text-gray-900">San Francisco, Hybrid</p>
                            </div>
                            <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">Frequency</p>
                                <p class="mt-1 font-medium text-gray-900">Weekly</p>
                            </div>
                            <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">Created</p>
                                <p class="mt-1 font-medium text-gray-900">Aug 20, 2023</p>
                            </div>
                        </div>
                    </div>

                    {{-- Right actions --}}
                    <div class="flex flex-wrap items-center gap-2 lg:justify-end">
                        <button type="button"
                            class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 hover:bg-gray-50 transition">
                            <i data-lucide="search" class="h-4 w-4 text-gray-600"></i>
                            <span>Show Jobs</span>
                        </button>

                        <button type="button"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition"
                            title="Edit">
                            <i data-lucide="pencil" class="h-5 w-5 text-gray-600"></i>
                        </button>

                        <button type="button"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 transition"
                            title="Resume">
                            <i data-lucide="play" class="h-5 w-5 text-emerald-700"></i>
                        </button>

                        <button type="button"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-red-200 bg-red-50 hover:bg-red-100 transition"
                            title="Delete">
                            <i data-lucide="trash-2" class="h-5 w-5 text-red-600"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection