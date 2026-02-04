@extends('candidate.layout')

@section('content')
<div class="space-y-6" x-data="appliedJobsPage()" x-init="init()">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="space-y-1">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">My Applied Jobs</h1>
            <p class="text-sm text-gray-500">Search + filter + view details modal are frontend demo only (no backend).</p>
        </div>

        {{-- Controls (responsive) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:items-center gap-3 w-full lg:w-auto">
            {{-- Search --}}
            <div class="relative w-full sm:min-w-[260px] lg:w-72">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i data-lucide="search" class="h-4 w-4"></i>
                </span>
                <input
                    type="text"
                    placeholder="Search by job title..."
                    class="w-full rounded-xl border border-gray-200 bg-white pl-9 pr-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                    x-model.trim="query"
                />
            </div>

            {{-- Sort / Filter --}}
            <select
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300 sm:min-w-[220px] lg:w-52"
                x-model="filterBy"
                @change="applyFilter()"
            >
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="status_interview">Status: Interview</option>
                <option value="status_applied">Status: Applied</option>
                <option value="status_rejected">Status: Rejected</option>
            </select>
        </div>
    </div>

    {{-- Toast --}}
    <div
        x-show="toast.show"
        x-transition.opacity
        x-cloak
        class="rounded-2xl border p-4 text-sm flex items-start gap-3"
        :class="toast.type === 'success'
            ? 'bg-emerald-50 border-emerald-200 text-emerald-700'
            : (toast.type === 'warn'
                ? 'bg-yellow-50 border-yellow-200 text-yellow-800'
                : 'bg-red-50 border-red-200 text-red-700')"
    >
        <div class="mt-0.5">
            <i data-lucide="info" class="h-4 w-4"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-semibold break-words" x-text="toast.title"></p>
            <p class="mt-0.5 break-words" x-text="toast.message"></p>
        </div>
        <button type="button" class="text-xs underline opacity-80 hover:opacity-100 shrink-0" @click="toast.show=false">
            Close
        </button>
    </div>

    {{-- Empty state --}}
    <template x-if="filteredJobs().length === 0">
        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6 sm:p-8 text-center">
            <div class="mx-auto h-12 w-12 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center">
                <i data-lucide="briefcase" class="h-6 w-6 text-gray-500"></i>
            </div>
            <p class="mt-3 text-sm font-semibold text-gray-900">No results found</p>
            <p class="mt-1 text-sm text-gray-600">Try a different keyword or filter.</p>

            <button type="button"
                class="mt-4 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                @click="resetFilters()">
                Reset
            </button>
        </div>
    </template>

    {{-- List --}}
    <div class="space-y-4">
        <template x-for="job in filteredJobs()" :key="job.id">
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-4 sm:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-start gap-4 min-w-0">
                        <div
                            class="h-14 w-14 rounded-2xl flex items-center justify-center text-white font-semibold shrink-0"
                            :class="job.badgeBg"
                            x-text="job.badge"
                        ></div>

                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 break-words" x-text="job.title"></p>
                            <p class="text-sm text-blue-600 font-semibold mt-1 break-words" x-text="job.company"></p>

                            <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="map-pin" class="h-4 w-4"></i>
                                    <span class="break-words" x-text="job.location"></span>
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="clock" class="h-4 w-4"></i>
                                    <span class="break-words" x-text="job.type"></span>
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="dollar-sign" class="h-4 w-4"></i>
                                    <span class="break-words" x-text="job.salaryText"></span>
                                </span>
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                <span class="inline-flex items-center rounded-full border px-2 py-0.5 font-semibold"
                                      :class="job.statusPill">
                                    <span x-text="job.status"></span>
                                </span>

                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="calendar" class="h-3.5 w-3.5"></i>
                                    <span class="break-words" x-text="'Applied: ' + job.appliedDate"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Button responsive --}}
                    <div class="flex md:justify-end">
                        <button
                            type="button"
                            @click="openDetails(job.id)"
                            class="w-full md:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            <i data-lucide="eye" class="h-4 w-4"></i> View Details
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Modal --}}
    <div
        x-show="detailsOpen"
        x-transition.opacity
        x-cloak
        class="fixed inset-0 z-[999] flex items-end sm:items-start justify-center p-0 sm:p-6"
        role="dialog"
        aria-modal="true"
        @keydown.escape.window="closeDetails()"
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-gray-900/40" @click="closeDetails()"></div>

        {{-- Panel (bottom-sheet on mobile, centered on desktop) --}}
        <div
            class="relative w-full sm:max-w-6xl bg-gray-50 border border-gray-200 shadow-xl
                   rounded-t-2xl sm:rounded-2xl
                   max-h-[88vh] sm:max-h-[92vh] overflow-y-auto"
            @click.stop
        >
            {{-- Top bar --}}
            <div class="sticky top-0 z-10 bg-gray-50/95 backdrop-blur border-b border-gray-200">
                <div class="flex items-center justify-between px-4 sm:px-6 py-3">
                    <button
                        type="button"
                        @click="closeDetails()"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-gray-900"
                    >
                        <i data-lucide="arrow-left" class="h-4 w-4"></i>
                        Back
                    </button>

                    <button
                        type="button"
                        @click="closeDetails()"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                        title="Close"
                    >
                        <i data-lucide="x" class="h-5 w-5 text-gray-700"></i>
                    </button>
                </div>
            </div>

            <div class="p-4 sm:p-6 space-y-6">
                {{-- Header card --}}
                <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-4 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">
                        <div class="flex items-start gap-4 min-w-0">
                            <div
                                class="h-16 w-16 rounded-full border border-gray-200 flex items-center justify-center font-semibold shrink-0"
                                :class="activeJob.headerBadgeBg"
                            >
                                <span class="text-gray-900" x-text="activeJob.badge"></span>
                            </div>

                            <div class="min-w-0">
                                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 break-words" x-text="activeJob.title"></h2>
                                <p class="mt-1 text-sm font-semibold text-blue-600 break-words" x-text="activeJob.company"></p>

                                <div class="mt-3 flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                    <span class="inline-flex items-center gap-1.5">
                                        <i data-lucide="map-pin" class="h-4 w-4"></i> <span class="break-words" x-text="activeJob.location"></span>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <i data-lucide="clock" class="h-4 w-4"></i> <span class="break-words" x-text="activeJob.type"></span>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <i data-lucide="dollar-sign" class="h-4 w-4"></i> <span class="break-words" x-text="activeJob.salaryText"></span>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <i data-lucide="calendar" class="h-4 w-4"></i> <span class="break-words" x-text="'Applied on ' + activeJob.appliedDate"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Buttons stack on mobile --}}
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:justify-end">
                            <button
                                type="button"
                                class="inline-flex h-11 w-full sm:w-11 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                                title="Download application (demo)"
                                @click="toastMsg('success','Download (demo)','Backend needed to download actual files.')"
                            >
                                <i data-lucide="download" class="h-5 w-5 text-gray-700"></i>
                                <span class="sm:hidden ml-2 text-sm font-semibold text-gray-700">Download</span>
                            </button>

                            <button
                                type="button"
                                class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-700 w-full sm:w-auto"
                                @click="toastMsg('success','Update (demo)','In real app, this could open messages or update application details.')"
                            >
                                Open Messages
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Content grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    {{-- Left --}}
                    <div class="lg:col-span-8 space-y-6">

                        {{-- Job description --}}
                        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Job Description</h3>
                            <div class="mt-4 space-y-4 text-sm leading-relaxed text-gray-600 break-words">
                                <p x-text="activeJob.description"></p>
                            </div>
                        </div>

                        {{-- Application timeline --}}
                        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Application Timeline</h3>

                            <div class="mt-4 space-y-3">
                                <template x-for="step in activeJob.timeline" :key="step.title">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1.5 h-3 w-3 rounded-full shrink-0"
                                             :class="step.done ? 'bg-emerald-500' : 'bg-gray-300'"></div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 break-words" x-text="step.title"></p>
                                            <p class="text-xs text-gray-500 mt-0.5 break-words" x-text="step.meta"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Submitted resume --}}
                        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Submitted Resume</h3>

                            <div class="mt-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="h-10 w-10 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0">
                                        <i data-lucide="file-text" class="h-5 w-5 text-emerald-700"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="activeJob.resume.fileName"></p>
                                        <p class="text-xs text-gray-500" x-text="activeJob.resume.meta"></p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 justify-end">
                                    <button
                                        type="button"
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-white"
                                        title="Download (demo)"
                                        @click="toastMsg('success','Download (demo)','Backend needed to download actual files.')"
                                    >
                                        <i data-lucide="download" class="h-5 w-5 text-gray-700"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-4">
                                <p class="text-sm font-semibold text-blue-900">Note</p>
                                <p class="mt-1 text-sm text-blue-800">
                                    This is demo data. In backend, the resume file and details will come from database.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Right --}}
                    <div class="lg:col-span-4 space-y-6">

                        {{-- Status card --}}
                        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Current Status</h3>

                            <div class="mt-4 flex items-start gap-3">
                                <div class="h-11 w-11 rounded-2xl border flex items-center justify-center shrink-0"
                                     :class="activeJob.statusIconBg">
                                    <i :data-lucide="activeJob.statusIcon" class="h-5 w-5" :class="activeJob.statusIconColor"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 break-words" x-text="activeJob.status"></p>
                                    <p class="mt-1 text-sm text-gray-600 break-words" x-text="activeJob.statusNote"></p>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                                    <p class="text-xs font-semibold text-gray-500">Applied</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900 break-words" x-text="activeJob.appliedDate"></p>
                                </div>
                                <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                                    <p class="text-xs font-semibold text-gray-500">Last Update</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900 break-words" x-text="activeJob.lastUpdate"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Company info --}}
                        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                            <h3 class="text-lg font-semibold text-gray-900 break-words" x-text="'About ' + activeJob.company"></h3>

                            <div class="mt-4 space-y-4 text-sm text-gray-700">
                                <div class="flex items-start gap-3">
                                    <i data-lucide="building-2" class="h-5 w-5 text-gray-500 shrink-0"></i>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900">Industry</p>
                                        <p class="text-gray-600 mt-0.5 break-words" x-text="activeJob.companyInfo.industry"></p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <i data-lucide="users" class="h-5 w-5 text-gray-500 shrink-0"></i>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900">Company Size</p>
                                        <p class="text-gray-600 mt-0.5 break-words" x-text="activeJob.companyInfo.size"></p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <i data-lucide="map" class="h-5 w-5 text-gray-500 shrink-0"></i>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900">HQ</p>
                                        <p class="text-gray-600 mt-0.5 break-words" x-text="activeJob.companyInfo.hq"></p>
                                    </div>
                                </div>

                                <p class="text-gray-600 leading-relaxed break-words" x-text="activeJob.companyInfo.about"></p>

                                <button
                                    type="button"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                                    @click="toastMsg('success','Company (demo)','This can open company profile page in real app.')"
                                >
                                    View Company Profile
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<script>
function appliedJobsPage() {
    return {
        query: '',
        filterBy: 'newest',
        toast: { show:false, type:'success', title:'', message:'' },
        detailsOpen: false,
        activeId: null,

        activeJob: {
            id: null, title: '', company: '', badge: '', badgeBg: '',
            headerBadgeBg: 'bg-white',
            location: '', type: '', salaryText: '',
            appliedDate: '', lastUpdate: '',
            status: '', statusPill: '',
            statusIcon: 'info',
            statusIconBg: 'bg-gray-50 border-gray-200',
            statusIconColor: 'text-gray-600',
            statusNote: '',
            description: '',
            timeline: [],
            resume: { fileName:'', meta:'' },
            companyInfo: { industry:'', size:'', hq:'', about:'' },
            createdAt: 0
        },

        //jobs: @json([]), // placeholder if you want backend later (keep demo data below)

        
        jobs: [
            {
                id: 1,
                title: 'Senior Product Designer',
                company: 'TechFlow',
                badge: 'TE',
                badgeBg: 'bg-blue-600',
                headerBadgeBg: 'bg-blue-50',
                location: 'Remote',
                type: 'Full-time',
                salaryText: '$120k - $150k',
                status: 'Interview',
                statusPill: 'bg-emerald-50 text-emerald-700 border-emerald-100',
                appliedDate: '2023-10-15',
                lastUpdate: '2023-10-18',
                statusIcon: 'calendar-check',
                statusIconBg: 'bg-emerald-50 border-emerald-100',
                statusIconColor: 'text-emerald-700',
                statusNote: 'Interview scheduled. Check your messages for time and link.',
                description: 'You applied for a Senior Product Designer role. This position focuses on designing end-to-end user experiences, building design systems, and collaborating with product + engineering.',
                timeline: [
                    { title:'Application submitted', meta:'Oct 15, 2023 • Resume sent', done:true },
                    { title:'Application viewed', meta:'Oct 16, 2023 • Recruiter reviewed', done:true },
                    { title:'Interview scheduled', meta:'Oct 18, 2023 • Waiting for interview', done:true },
                    { title:'Final decision', meta:'Pending', done:false }
                ],
                resume: { fileName:'Keith_CV_2023.pdf', meta:'2.4 MB • Submitted with application' },
                companyInfo: {
                    industry:'Technology / SaaS',
                    size:'200-500 employees',
                    hq:'San Francisco, CA',
                    about:'TechFlow builds workflow automation tools for teams. They focus on productivity, collaboration, and clean user experiences.'
                },
                createdAt: Date.now() - (2 * 24 * 60 * 60 * 1000)
            },
            {
                id: 2,
                title: 'UX Researcher',
                company: 'Creative Studio',
                badge: 'CS',
                badgeBg: 'bg-emerald-500',
                headerBadgeBg: 'bg-emerald-50',
                location: 'New York, NY',
                type: 'Contract',
                salaryText: '$90k - $110k',
                status: 'Applied',
                statusPill: 'bg-blue-50 text-blue-700 border-blue-100',
                appliedDate: '2023-10-12',
                lastUpdate: '2023-10-12',
                statusIcon: 'send',
                statusIconBg: 'bg-blue-50 border-blue-100',
                statusIconColor: 'text-blue-700',
                statusNote: 'Application sent. Waiting for employer response.',
                description: 'You applied for a UX Researcher contract role. Work includes interviews, surveys, usability testing, and turning insights into product recommendations.',
                timeline: [
                    { title:'Application submitted', meta:'Oct 12, 2023 • Resume sent', done:true },
                    { title:'Application viewed', meta:'Pending', done:false },
                    { title:'Interview', meta:'Pending', done:false },
                    { title:'Final decision', meta:'Pending', done:false }
                ],
                resume: { fileName:'Keith_CV_2023.pdf', meta:'2.4 MB • Submitted with application' },
                companyInfo: {
                    industry:'Design Agency',
                    size:'50-100 employees',
                    hq:'New York, NY',
                    about:'Creative Studio helps brands design better digital experiences. Their teams work on research, strategy, and UI design.'
                },
                createdAt: Date.now() - (5 * 24 * 60 * 60 * 1000)
            },
            {
                id: 3,
                title: 'Frontend Developer',
                company: 'WebSolutions',
                badge: 'WE',
                badgeBg: 'bg-red-500',
                headerBadgeBg: 'bg-red-50',
                location: 'Austin, TX',
                type: 'Full-time',
                salaryText: '$100k - $130k',
                status: 'Rejected',
                statusPill: 'bg-red-50 text-red-700 border-red-100',
                appliedDate: '2023-10-08',
                lastUpdate: '2023-10-11',
                statusIcon: 'x-circle',
                statusIconBg: 'bg-red-50 border-red-100',
                statusIconColor: 'text-red-700',
                statusNote: 'Not selected. You can apply to other roles and keep improving your profile.',
                description: 'You applied for a Frontend Developer role. The job focuses on building UI, integrating APIs, and collaborating with designers and backend engineers.',
                timeline: [
                    { title:'Application submitted', meta:'Oct 8, 2023 • Resume sent', done:true },
                    { title:'Application viewed', meta:'Oct 9, 2023 • Recruiter reviewed', done:true },
                    { title:'Decision', meta:'Oct 11, 2023 • Rejected', done:true }
                ],
                resume: { fileName:'Keith_CV_2023.pdf', meta:'2.4 MB • Submitted with application' },
                companyInfo: {
                    industry:'Web Development',
                    size:'100-200 employees',
                    hq:'Austin, TX',
                    about:'WebSolutions builds websites and web apps for clients. They focus on performance, accessibility, and modern frontend stacks.'
                },
                createdAt: Date.now() - (7 * 24 * 60 * 60 * 1000)
            }
        ],

        init() {
            this.applyFilter(false);
            this.$nextTick(() => { window.lucide?.createIcons(); });
        },

        toastMsg(type, title, message) {
            this.toast = { show:true, type, title, message };
            this.$nextTick(() => { window.lucide?.createIcons(); });
        },

        resetFilters() {
            this.query = '';
            this.filterBy = 'newest';
            this.applyFilter();
            this.toastMsg('success', 'Reset', 'Search and filter were reset (demo).');
        },

        filteredJobs() {
            const q = (this.query || '').toLowerCase().trim();

            let list = this.jobs.filter(j => {
                if (!q) return true;
                return (j.title || '').toLowerCase().includes(q) ||
                       (j.company || '').toLowerCase().includes(q);
            });

            if (this.filterBy === 'status_interview') list = list.filter(j => (j.status || '').toLowerCase() === 'interview');
            if (this.filterBy === 'status_applied') list = list.filter(j => (j.status || '').toLowerCase() === 'applied');
            if (this.filterBy === 'status_rejected') list = list.filter(j => (j.status || '').toLowerCase() === 'rejected');

            return list;
        },

        applyFilter(showToast = true) {
            const key = this.filterBy;

            if (key === 'newest') this.jobs.sort((a,b) => (b.createdAt||0) - (a.createdAt||0));
            if (key === 'oldest') this.jobs.sort((a,b) => (a.createdAt||0) - (b.createdAt||0));

            const labelMap = {
                newest: 'Newest First',
                oldest: 'Oldest First',
                status_interview: 'Status: Interview',
                status_applied: 'Status: Applied',
                status_rejected: 'Status: Rejected'
            };

            if (showToast) this.toastMsg('success', 'Filter applied', `Showing results by: ${labelMap[key] || 'Newest First'} (demo).`);
        },

        openDetails(id) {
            const job = this.jobs.find(j => j.id === id);
            if (!job) return;

            this.activeJob = JSON.parse(JSON.stringify(job));
            this.activeId = id;
            this.detailsOpen = true;

            this.$nextTick(() => { window.lucide?.createIcons(); });
        },

        closeDetails() {
            this.detailsOpen = false;
            this.activeId = null;
        }
    }
}
</script>
@endsection
