@extends('candidate.layout')

@section('content')
    <div class="space-y-6" x-data="dashboardJobs()" x-init="init()">

        {{-- Header --}}
        <div class="space-y-1">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500">
                Welcome back,
                <span class="font-semibold text-gray-700">
                    {{ auth()->user()->name }}
                </span> !
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

                    <a href="{{ route('candidate.my-applied-jobs') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900">
                        View All
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </a>
                </div>

                {{-- Render Job Cards --}}
                <template x-for="job in jobs" :key="job.id">
                    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-start gap-4 min-w-0">
                                <div class="h-14 w-14 rounded-2xl flex items-center justify-center text-white font-semibold shrink-0"
                                    :class="job.badgeBg" x-text="job.badge"></div>

                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900" x-text="job.title"></p>
                                    <p class="text-sm text-blue-600 font-semibold mt-1" x-text="job.company"></p>

                                    <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="map-pin" class="h-4 w-4"></i>
                                            <span x-text="job.location"></span>
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="clock" class="h-4 w-4"></i>
                                            <span x-text="job.type"></span>
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="dollar-sign" class="h-4 w-4"></i>
                                            <span x-text="job.salary"></span>
                                        </span>
                                    </div>

                                    <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 font-semibold border"
                                            :class="job.statusPill" x-text="job.status"></span>

                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="calendar" class="h-3.5 w-3.5"></i>
                                            <span x-text="'Applied: ' + job.appliedDate"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex md:justify-end">
                                <button type="button" @click="openJob(job)"
                                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                    <i data-lucide="eye" class="h-4 w-4"></i> View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </section>

            {{-- Notifications --}}
            <aside class="xl:col-span-4 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">Notifications</h2>

                    <button type="button" @click="markAllRead()"
                        class="text-sm font-semibold text-gray-500 hover:text-gray-700"
                        :disabled="notifications.length === 0"
                        :class="notifications.length === 0 ? 'opacity-50 cursor-not-allowed' : ''">
                        Mark all as read
                    </button>
                </div>

                {{-- Empty state --}}
                <template x-if="notifications.length === 0">
                    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6 text-center">
                        <div
                            class="mx-auto h-12 w-12 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center">
                            <i data-lucide="bell-off" class="h-6 w-6 text-gray-500"></i>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-gray-900">All caught up!</p>
                        <p class="mt-1 text-sm text-gray-500">No new notifications right now.</p>
                    </div>
                </template>

                {{-- Notifications list --}}
                <template x-if="notifications.length > 0">
                    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm divide-y divide-gray-100">
                        <template x-for="n in notifications" :key="n.id">
                            <div class="p-5 flex items-start gap-4">
                                <div class="h-11 w-11 rounded-2xl border flex items-center justify-center"
                                    :class="n.iconWrap">
                                    <i :data-lucide="n.icon" class="h-5 w-5" :class="n.iconColor"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-sm font-semibold text-gray-900" x-text="n.title"></p>
                                        <p class="text-xs text-gray-400 whitespace-nowrap" x-text="n.time"></p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600" x-text="n.body"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </aside>
        </div>


        <div x-show="jobModalOpen" x-transition.opacity
            class="fixed inset-0 z-[999] flex items-start justify-center p-3 sm:p-6" aria-modal="true" role="dialog"
            @keydown.escape.window="closeJob()" x-cloak>
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-gray-900/40" @click="closeJob()"></div>

            {{-- Panel --}}
            <div x-transition @click.stop
                class="relative w-full max-w-6xl max-h-[92vh] overflow-y-auto rounded-2xl bg-gray-50 border border-gray-200 shadow-xl">
                {{-- Top bar --}}
                <div class="sticky top-0 z-10 bg-gray-50/95 backdrop-blur border-b border-gray-200">
                    <div class="flex items-center justify-between px-4 sm:px-6 py-3">
                        <button type="button" @click="closeJob()"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-gray-900">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            Back
                        </button>

                        <button type="button" @click="closeJob()"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                            title="Close">
                            <i data-lucide="x" class="h-5 w-5 text-gray-700"></i>
                        </button>
                    </div>
                </div>

                <div class="p-4 sm:p-6 space-y-6">

                    {{-- Job header card --}}
                    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">
                            <div class="flex items-start gap-4 min-w-0">
                                <div class="h-16 w-16 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500 font-semibold shrink-0"
                                    x-text="selectedJob?.badge ?? 'JB'">
                                </div>

                                <div class="min-w-0">
                                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900"
                                        x-text="selectedJob?.title ?? ''"></h2>
                                    <p class="mt-1 text-sm font-semibold text-blue-600" x-text="selectedJob?.company ?? ''">
                                    </p>

                                    <div class="mt-3 flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                        <span class="inline-flex items-center gap-1.5">
                                            <i data-lucide="map-pin" class="h-4 w-4"></i>
                                            <span x-text="selectedJob?.location ?? ''"></span>
                                        </span>
                                        <span class="inline-flex items-center gap-1.5">
                                            <i data-lucide="clock" class="h-4 w-4"></i>
                                            <span x-text="selectedJob?.type ?? ''"></span>
                                        </span>
                                        <span class="inline-flex items-center gap-1.5">
                                            <i data-lucide="dollar-sign" class="h-4 w-4"></i>
                                            <span x-text="selectedJob?.salary ?? ''"></span>
                                        </span>
                                        <span class="inline-flex items-center gap-1.5">
                                            <i data-lucide="calendar" class="h-4 w-4"></i>
                                            <span x-text="selectedJob?.posted ?? ''"></span>
                                        </span>
                                    </div>

                                    <div class="mt-3 flex flex-wrap items-center gap-3">
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold border"
                                            :class="selectedJob?.statusPill ?? 'bg-gray-50 text-gray-700 border-gray-200'"
                                            x-text="selectedJob?.status ?? ''">
                                        </span>
                                        <span class="text-xs text-gray-500"
                                            x-text="'Applied: ' + (selectedJob?.appliedDate ?? '')"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 justify-end">
                                <button
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                                    title="Save">
                                    <i data-lucide="bookmark" class="h-5 w-5 text-gray-700"></i>
                                </button>
                                <button
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                                    title="Share">
                                    <i data-lucide="share-2" class="h-5 w-5 text-gray-700"></i>
                                </button>

                                <button type="button" @click="jobModalOpen=false; applyModalOpen=true"
                                    class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                                    Apply Now
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Content grid --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                        {{-- Left --}}
                        <div class="lg:col-span-8 space-y-6">

                            {{-- Job Description --}}
                            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900">Job Description</h3>
                                <div class="mt-4 space-y-4 text-sm leading-relaxed text-gray-600">
                                    <template x-for="(p, idx) in (selectedJob?.description ?? [])" :key="idx">
                                        <p x-text="p"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Responsibilities --}}
                            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900">Responsibilities</h3>
                                <ul class="mt-4 space-y-3 text-sm text-gray-700">
                                    <template x-for="(item, idx) in (selectedJob?.responsibilities ?? [])" :key="idx">
                                        <li class="flex items-start gap-3">
                                            <span class="mt-2 h-1.5 w-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                            <span x-text="item"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            {{-- Requirements --}}
                            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900">Requirements</h3>
                                <ul class="mt-4 space-y-3 text-sm text-gray-700">
                                    <template x-for="(item, idx) in (selectedJob?.requirements ?? [])" :key="idx">
                                        <li class="flex items-start gap-3">
                                            <span class="mt-2 h-1.5 w-1.5 rounded-full bg-blue-500 shrink-0"></span>
                                            <span x-text="item"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            {{-- Nice to Have --}}
                            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900">Nice to Have</h3>
                                <ul class="mt-4 space-y-3 text-sm text-gray-700">
                                    <template x-for="(item, idx) in (selectedJob?.niceToHave ?? [])" :key="idx">
                                        <li class="flex items-start gap-3">
                                            <span class="mt-2 h-1.5 w-1.5 rounded-full bg-gray-400 shrink-0"></span>
                                            <span x-text="item"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            {{-- What happens next (extra nice section) --}}
                            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900">What happens next</h3>
                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                        <p class="text-xs font-semibold text-gray-500">Step 1</p>
                                        <p class="mt-1 font-semibold text-gray-900">Application Review</p>
                                        <p class="mt-1 text-gray-600">Recruiter checks your resume & portfolio.</p>
                                    </div>
                                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                        <p class="text-xs font-semibold text-gray-500">Step 2</p>
                                        <p class="mt-1 font-semibold text-gray-900">Interview</p>
                                        <p class="mt-1 text-gray-600">Short call + role-specific questions.</p>
                                    </div>
                                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                        <p class="text-xs font-semibold text-gray-500">Step 3</p>
                                        <p class="mt-1 font-semibold text-gray-900">Decision</p>
                                        <p class="mt-1 text-gray-600">Offer or feedback sent by email.</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Right --}}
                        <div class="lg:col-span-4 space-y-6">

                            {{-- About Company --}}
                            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900"
                                    x-text="'About ' + (selectedJob?.company ?? '')"></h3>

                                <div class="mt-4 space-y-4 text-sm text-gray-700">
                                    <div class="flex items-start gap-3">
                                        <i data-lucide="building-2" class="h-5 w-5 text-gray-500"></i>
                                        <div>
                                            <p class="font-semibold text-gray-900">Industry</p>
                                            <p class="text-gray-600 mt-0.5"
                                                x-text="selectedJob?.companyInfo?.industry ?? ''"></p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <i data-lucide="users" class="h-5 w-5 text-gray-500"></i>
                                        <div>
                                            <p class="font-semibold text-gray-900">Company Size</p>
                                            <p class="text-gray-600 mt-0.5" x-text="selectedJob?.companyInfo?.size ?? ''">
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <i data-lucide="calendar" class="h-5 w-5 text-gray-500"></i>
                                        <div>
                                            <p class="font-semibold text-gray-900">Founded</p>
                                            <p class="text-gray-600 mt-0.5"
                                                x-text="selectedJob?.companyInfo?.founded ?? ''"></p>
                                        </div>
                                    </div>

                                    <p class="text-gray-600 leading-relaxed" x-text="selectedJob?.companyInfo?.about ?? ''">
                                    </p>

                                    <button
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                        View Company Profile
                                    </button>
                                </div>
                            </div>

                            {{-- Benefits --}}
                            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900">Benefits</h3>

                                <ul class="mt-4 space-y-3 text-sm text-gray-700">
                                    <template x-for="(b, idx) in (selectedJob?.benefits ?? [])" :key="idx">
                                        <li class="flex items-start gap-3">
                                            <i data-lucide="check" class="h-4 w-4 text-emerald-600 mt-0.5"></i>
                                            <span x-text="b"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            {{-- Quick Summary --}}
                            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                                <h3 class="text-lg font-semibold text-gray-900">Quick Summary</h3>
                                <div class="mt-4 space-y-3 text-sm text-gray-700">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">Experience</span>
                                        <span class="font-semibold text-gray-900"
                                            x-text="selectedJob?.summary?.experience ?? ''"></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">Primary Tools</span>
                                        <span class="font-semibold text-gray-900"
                                            x-text="selectedJob?.summary?.tools ?? ''"></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">Team</span>
                                        <span class="font-semibold text-gray-900"
                                            x-text="selectedJob?.summary?.team ?? ''"></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="applyModalOpen" x-transition.opacity
            class="fixed inset-0 z-[1000] flex items-center justify-center p-3 sm:p-6" aria-modal="true" role="dialog"
            @keydown.escape.window="applyModalOpen=false" x-cloak>
            <div class="absolute inset-0 bg-gray-900/40" @click="applyModalOpen=false"></div>

            <div x-transition @click.stop
                class="relative w-full max-w-xl rounded-2xl bg-white border border-gray-200 shadow-xl">
                <div class="flex items-start justify-between gap-4 px-5 sm:px-6 py-5">
                    <div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900">
                            Apply for <span x-text="selectedJob?.title ?? 'this job'"></span>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            <span x-text="selectedJob?.company ?? 'Company'"></span> • Complete your application below
                        </p>
                    </div>

                    <button type="button" @click="applyModalOpen=false"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                        title="Close">
                        <i data-lucide="x" class="h-5 w-5 text-gray-700"></i>
                    </button>
                </div>

                <div class="px-5 sm:px-6 pb-6 space-y-5">
                    <div class="space-y-3">
                        <p class="text-sm font-semibold text-gray-900">Resume</p>

                        <label
                            class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="resume" class="h-4 w-4 text-emerald-600" checked>
                            <span
                                class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 border border-red-100">
                                <i data-lucide="file-text" class="h-5 w-5 text-red-600"></i>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold text-gray-900 truncate">Keith_CV_2023.pdf</span>
                                <span class="block text-xs text-gray-500">2.3 MB • Uploaded</span>
                            </span>
                        </label>

                        <label
                            class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="resume" class="h-4 w-4 text-emerald-600">
                            <span
                                class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gray-50 border border-gray-200">
                                <i data-lucide="upload" class="h-5 w-5 text-gray-700"></i>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold text-gray-900 truncate">Upload a different
                                    resume</span>
                                <span class="block text-xs text-gray-500">PDF, DOC (max. 10MB)</span>
                            </span>
                        </label>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-semibold text-gray-900">Cover Letter</p>
                            <p class="text-sm text-gray-500">(Optional)</p>
                        </div>

                        <textarea rows="4"
                            placeholder="Write a brief cover letter to introduce yourself and explain why you're a good fit for this role..."
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"></textarea>
                        <p class="text-xs text-gray-500">A well-written cover letter can help you stand out from other
                            candidates</p>
                    </div>

                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                        <p class="text-sm font-semibold text-blue-900">Before you apply</p>
                        <ul class="mt-3 space-y-2 text-sm text-blue-800">
                            <li class="flex items-start gap-2">
                                <span class="mt-2 h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                <span>Make sure your resume is up to date</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-2 h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                <span>Review the job requirements carefully</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-2 h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                <span>Your application will be sent directly to the employer</span>
                            </li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-1">
                        <button type="button" @click="applyModalOpen=false"
                            class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="button"
                            class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                            Submit Application
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Alpine Data --}}
    <script>
        function dashboardJobs() {
            return {
                jobModalOpen: false,
                applyModalOpen: false,
                selectedJob: null,

                jobs: [
                    {
                        id: 1,
                        badge: 'TE',
                        badgeBg: 'bg-blue-600',
                        title: 'Senior Product Designer',
                        company: 'TechFlow',
                        location: 'Remote',
                        type: 'Full-time',
                        salary: '$120k - $150k',
                        status: 'Interview',
                        statusPill: 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        appliedDate: '2023-10-15',
                        posted: 'Posted 3 days ago',

                        summary: {
                            experience: '5+ years',
                            tools: 'Figma, Prototyping',
                            team: 'Design + Product'
                        },

                        description: [
                            'You will own end-to-end product design for core features and collaborate closely with PMs and engineers.',
                            'Your goal is to simplify complex workflows and create clean, modern UI experiences for enterprise users.'
                        ],
                        responsibilities: [
                            'Lead design from concept to delivery (wireframes → high fidelity)',
                            'Build prototypes and validate with quick user tests',
                            'Work with engineers to ensure design is implemented correctly',
                            'Maintain and improve the design system',
                            'Mentor junior designers and provide feedback'
                        ],
                        requirements: [
                            '5+ years experience in product design',
                            'Strong portfolio with UX + UI case studies',
                            'Proficiency in Figma and prototyping tools',
                            'Experience with design systems',
                            'Excellent communication and teamwork'
                        ],
                        niceToHave: [
                            'Experience designing SaaS dashboards',
                            'Basic front-end knowledge (HTML/CSS)',
                            'Accessibility awareness (WCAG)',
                            'Experience leading workshops'
                        ],
                        companyInfo: {
                            industry: 'Technology / SaaS',
                            size: '200-500 employees',
                            founded: '2015',
                            about: 'TechFlow builds enterprise workflow software that helps teams automate tasks, track progress, and collaborate faster.'
                        },
                        benefits: [
                            'Competitive salary and equity',
                            'Health, dental, and vision insurance',
                            '401(k) matching',
                            'Unlimited PTO',
                            'Remote work flexibility',
                            'Professional development budget'
                        ]
                    },

                    {
                        id: 2,
                        badge: 'CS',
                        badgeBg: 'bg-emerald-500',
                        title: 'UX Researcher',
                        company: 'Creative Studio',
                        location: 'New York, NY',
                        type: 'Contract',
                        salary: '$90k - $110k',
                        status: 'Applied',
                        statusPill: 'bg-blue-50 text-blue-700 border-blue-100',
                        appliedDate: '2023-10-12',
                        posted: 'Posted 5 days ago',

                        summary: {
                            experience: '3+ years',
                            tools: 'Interviews, Surveys',
                            team: 'Research + Design'
                        },

                        description: [
                            'You will plan and conduct user research to improve product usability and discover insights for future features.',
                            'You will work with designers to turn research into clear recommendations and measurable improvements.'
                        ],
                        responsibilities: [
                            'Run interviews, surveys, and usability tests',
                            'Create research plans and discussion guides',
                            'Synthesize findings into insights and recommendations',
                            'Partner with designers and PMs to prioritize improvements',
                            'Maintain a research repository for the team'
                        ],
                        requirements: [
                            '3+ years UX research experience',
                            'Strong qualitative research skills',
                            'Clear storytelling and presentation skills',
                            'Experience running usability tests',
                            'Understanding of UX principles'
                        ],
                        niceToHave: [
                            'Experience in e-commerce or marketplace apps',
                            'Quantitative analysis basics',
                            'Familiarity with analytics tools',
                            'Workshop facilitation'
                        ],
                        companyInfo: {
                            industry: 'Design / Agency',
                            size: '50-100 employees',
                            founded: '2018',
                            about: 'Creative Studio partners with startups and brands to deliver research-backed design solutions and product strategy.'
                        },
                        benefits: [
                            'Flexible schedule',
                            'Remote days available',
                            'Training stipend',
                            'Contract renewal opportunities'
                        ]
                    },

                    {
                        id: 3,
                        badge: 'WE',
                        badgeBg: 'bg-red-500',
                        title: 'Frontend Developer',
                        company: 'WebSolutions',
                        location: 'Austin, TX',
                        type: 'Full-time',
                        salary: '$100k - $130k',
                        status: 'Rejected',
                        statusPill: 'bg-red-50 text-red-700 border-red-100',
                        appliedDate: '2023-10-08',
                        posted: 'Posted 1 week ago',

                        summary: {
                            experience: '2-4 years',
                            tools: 'HTML/CSS/JS',
                            team: 'Engineering'
                        },

                        description: [
                            'You will build responsive UI components and improve performance across the web app.',
                            'You will collaborate with backend devs and designers to implement clean and accessible interfaces.'
                        ],
                        responsibilities: [
                            'Build reusable UI components',
                            'Implement responsive layouts with Tailwind',
                            'Optimize performance and accessibility',
                            'Work with APIs and integrate backend data',
                            'Write clean, maintainable code'
                        ],
                        requirements: [
                            '2+ years frontend development experience',
                            'Strong HTML/CSS/JavaScript skills',
                            'Experience with Tailwind (or similar)',
                            'Basic understanding of REST APIs',
                            'Good communication skills'
                        ],
                        niceToHave: [
                            'Experience with Alpine.js',
                            'Knowledge of Laravel Blade',
                            'Testing basics (unit/UI)',
                            'Animation/UX polish skills'
                        ],
                        companyInfo: {
                            industry: 'Web Development',
                            size: '20-50 employees',
                            founded: '2012',
                            about: 'WebSolutions builds custom web platforms for clients, focusing on performance, UX, and scalable architecture.'
                        },
                        benefits: [
                            'Health insurance',
                            'Performance bonus',
                            'Hybrid work options',
                            'Learning resources'
                        ]
                    }
                ],

                notifications: [
                    {
                        id: 1,
                        title: 'Interview Scheduled',
                        time: '2 hours ago',
                        body: 'TechFlow has scheduled an interview for Senior Product Designer role.',
                        icon: 'calendar-check',
                        iconWrap: 'bg-emerald-50 border-emerald-100',
                        iconColor: 'text-emerald-600'
                    },
                    {
                        id: 2,
                        title: 'Application Viewed',
                        time: '5 hours ago',
                        body: 'Creative Studio viewed your application for UX Researcher.',
                        icon: 'eye',
                        iconWrap: 'bg-blue-50 border-blue-100',
                        iconColor: 'text-blue-600'
                    },
                    {
                        id: 3,
                        title: 'New Job Alert',
                        time: '1 day ago',
                        body: '3 new jobs match your “Remote Designer” alert.',
                        icon: 'bell',
                        iconWrap: 'bg-amber-50 border-amber-100',
                        iconColor: 'text-amber-600'
                    }
                ],

                openJob(job) {
                    this.selectedJob = job;
                    this.jobModalOpen = true;

                    this.$nextTick(() => {
                        if (window.lucide) window.lucide.createIcons();
                    });
                },

                closeJob() {
                    this.jobModalOpen = false;
                },

                markAllRead() {
                    this.notifications = [];
                    this.$nextTick(() => {
                        if (window.lucide) window.lucide.createIcons();
                    });
                },

                init() {
                    this.$nextTick(() => {
                        if (window.lucide) window.lucide.createIcons();
                    });
                }
            }
        }
    </script>
@endsection