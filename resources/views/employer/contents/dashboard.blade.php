@extends('employer.layout')

@section('content')
@php
    $employer = auth()->user()->employerProfile;
@endphp

<script>
    window.Laravel = { userId: {{ auth()->id() }} };
</script>

<div class="space-y-6" x-data="employerDashboard()" x-init="init()">

    {{-- Header --}}
    <div class="space-y-1">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500">
            Welcome back, <span class="font-semibold text-gray-700">{{ auth()->user()->name }}</span>!
        </p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-2xl font-bold text-gray-900" x-text="stats.postedJobs"></p>
                <p class="text-sm text-gray-500 mt-1">Posted Jobs</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                <i data-lucide="briefcase" class="h-6 w-6 text-blue-600"></i>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-2xl font-bold text-gray-900" x-text="stats.applicants"></p>
                <p class="text-sm text-gray-500 mt-1">Applicants</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center">
                <i data-lucide="users" class="h-6 w-6 text-amber-600"></i>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-2xl font-bold text-gray-900" x-text="stats.interviews"></p>
                <p class="text-sm text-gray-500 mt-1">Interviews Scheduled</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center">
                <i data-lucide="calendar" class="h-6 w-6 text-purple-600"></i>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-2xl font-bold text-gray-900" x-text="stats.shortlisted"></p>
                <p class="text-sm text-gray-500 mt-1">Shortlisted</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                <i data-lucide="star" class="h-6 w-6 text-emerald-600"></i>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        {{-- Recently Posted Jobs --}}
        <section class="xl:col-span-8 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-900">Recently Posted Jobs</h2>
                <a href="{{ route('employer.job-postings.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900">
                    View All
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </div>

            <template x-for="job in jobs" :key="job.id">
                <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900" x-text="job.title"></p>
                                <p class="text-sm text-gray-500 mt-1" x-text="'Applicants: ' + job.applicants"></p>
                                <p class="mt-2 flex items-center gap-1 text-xs font-semibold" :class="job.statusPill" x-text="job.status"></p>
                            </div>
                        </div>

                        <div class="flex md:justify-end gap-2">
                            <button
                                type="button"
                                @click="openJob(job)"
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                            >
                                <i data-lucide="eye" class="h-4 w-4"></i> View Applicants
                            </button>
                            <a href="{{ route('employer.job-postings.create') }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
                            >
                                <i data-lucide="plus" class="h-4 w-4"></i> New Job
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </section>

        {{-- Notifications --}}
        <aside class="xl:col-span-4 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-900">Notifications</h2>
                <button type="button" @click="markAllRead()" class="text-sm font-semibold text-gray-500 hover:text-gray-700" :disabled="notifications.length === 0" :class="notifications.length === 0 ? 'opacity-50 cursor-not-allowed' : ''">
                    Mark all as read
                </button>
            </div>

            <template x-if="notifications.length === 0">
                <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6 text-center">
                    <div class="mx-auto h-12 w-12 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center">
                        <i data-lucide="bell-off" class="h-6 w-6 text-gray-500"></i>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-gray-900">All caught up!</p>
                    <p class="mt-1 text-sm text-gray-500">No new notifications right now.</p>
                </div>
            </template>

            <template x-if="notifications.length > 0">
                <div class="rounded-2xl bg-white border border-gray-200 shadow-sm divide-y divide-gray-100">
                    <template x-for="n in notifications" :key="n.id">
                        <div class="p-5 flex items-start gap-4">
                            <div class="h-11 w-11 rounded-2xl border flex items-center justify-center" :class="n.iconWrap">
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

    {{-- Applicants Modal --}}
    <div
        x-show="applicantsModalOpen"
        x-transition.opacity
        class="fixed inset-0 z-[999] flex items-start justify-center p-3 sm:p-6"
        aria-modal="true"
        role="dialog"
        @keydown.escape.window="closeApplicants()"
        x-cloak
    >
        <div class="absolute inset-0 bg-gray-900/40" @click="closeApplicants()"></div>

        <div
            x-transition
            @click.stop
            class="relative w-full max-w-6xl max-h-[92vh] overflow-y-auto rounded-2xl bg-gray-50 border border-gray-200 shadow-xl"
        >
            <div class="sticky top-0 z-10 bg-gray-50/95 backdrop-blur border-b border-gray-200 px-4 sm:px-6 py-3 flex items-center justify-between">
                <button type="button" @click="closeApplicants()" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-gray-900">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i> Back
                </button>
                <h2 class="text-lg font-semibold text-gray-900" x-text="selectedJob?.title ?? '' + ' Applicants'"></h2>
            </div>

            <div class="p-6 space-y-4">
                <template x-for="applicant in selectedJob?.applicantsList ?? []" :key="applicant.id">
                    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-4 flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-900" x-text="applicant.name"></p>
                            <p class="text-sm text-gray-500" x-text="applicant.email"></p>
                            <p class="text-sm text-gray-500" x-text="'Applied: ' + applicant.appliedDate"></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="rounded-xl border border-gray-200 px-3 py-1 text-sm font-semibold text-gray-700 hover:bg-gray-50">View Resume</button>
                            <button class="rounded-xl bg-emerald-600 px-3 py-1 text-sm font-semibold text-white hover:bg-emerald-700">Shortlist</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>

@php
// Prepare jobs array for Alpine.js
$jobsForJson = $jobs->map(function($job){
    return [
        'id' => $job->id,
        'title' => $job->title,
        'applicants' => $job->applications_count,
        'status' => $job->status,
        'statusPill' => match($job->status) {
            'Open' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'Interviewing' => 'bg-amber-50 text-amber-700 border-amber-100',
            'Closed' => 'bg-red-50 text-red-700 border-red-100',
            default => 'bg-gray-50 text-gray-700 border-gray-100',
        },
        'applicantsList' => $job->applications->map(fn($a) => [
            'id' => $a->id,
            'name' => $a->full_name,
            'email' => $a->email,
            'appliedDate' => $a->created_at->format('Y-m-d'),
        ])->values()->all(),
    ];
});

// Notifications already prepared
@endphp

<script>
function employerDashboard() {
    return {
        // Alpine.js data
        jobs: @json($jobsForJson),
        stats: @json($stats),
        notifications: @json($notificationsArray),

        applicantsModalOpen: false,
        selectedJob: null,

        openJob(job) {
            this.selectedJob = job;
            this.applicantsModalOpen = true;
            this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
        },
        closeApplicants() {
            this.applicantsModalOpen = false;
        },
        markAllRead() {
            this.notifications = [];
        },
        init() {
            if (window.lucide) window.lucide.createIcons();

            if (window.Echo) {
                window.Echo.private(`employer.${window.Laravel.userId}`)
                    .listen('.new-notification', (e) => {
                        this.notifications.unshift(e.notification);
                        window.toast('info', e.notification.title + ': ' + e.notification.body);
                    });
            }
        }
    }
}
</script>
@endsection