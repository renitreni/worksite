@extends('candidate.layout')

@section('content')
<div class="space-y-6" x-data="shortlistPage()" x-init="init()">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="space-y-1">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Shortlisted Jobs</h1>
            <p class="text-sm text-gray-500">Search + filter + apply modal are frontend demo only (no backend).</p>
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
                    placeholder="Search saved jobs..."
                    class="w-full rounded-xl border border-gray-200 bg-white pl-9 pr-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                    x-model.trim="query"
                />
            </div>

            {{-- Sort/Filter --}}
            <select
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300 sm:min-w-[220px] lg:w-44"
                x-model="sortBy"
                @change="applySort()"
            >
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="company">Company</option>
                <option value="salary">Salary</option>
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
        <div class="mt-0.5 shrink-0">
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
                <i data-lucide="bookmark" class="h-6 w-6 text-gray-500"></i>
            </div>
            <p class="mt-3 text-sm font-semibold text-gray-900">No results found</p>
            <p class="mt-1 text-sm text-gray-600">Try a different search keyword or filter.</p>

            <button type="button"
                class="mt-4 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                @click="resetFilters()">
                Reset filters
            </button>
        </div>
    </template>

    {{-- List --}}
    <div class="space-y-4">
        <template x-for="job in filteredJobs()" :key="job.id">
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-4 sm:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    {{-- Left info --}}
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
                                    <i data-lucide="clock-3" class="h-3.5 w-3.5"></i>
                                    <span class="break-words" x-text="job.postedText"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions (responsive) --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 w-full md:w-auto">
                        <button
                            type="button"
                            @click="openApply(job.id)"
                            class="w-full sm:w-auto rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition"
                        >
                            Apply Now
                        </button>

                        <button
                            type="button"
                            @click="removeJob(job.id)"
                            class="w-full sm:w-10 inline-flex h-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50"
                            title="Remove"
                        >
                            <i data-lucide="trash-2" class="h-5 w-5 text-red-500"></i>
                        </button>
                    </div>

                </div>
            </div>
        </template>
    </div>

    {{-- APPLY MODAL (responsive: bottom sheet on phone) --}}
    <div
        x-show="applyModalOpen"
        x-transition.opacity
        x-cloak
        class="fixed inset-0 z-[999] flex items-end sm:items-center justify-center p-0 sm:p-6"
        role="dialog"
        aria-modal="true"
        @keydown.escape.window="closeApply()"
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-gray-900/40" @click="closeApply()"></div>

        {{-- Panel --}}
        <div
            class="relative w-full sm:max-w-2xl bg-white border border-gray-200 shadow-xl overflow-hidden
                   rounded-t-2xl sm:rounded-2xl max-h-[88vh] sm:max-h-[92vh] overflow-y-auto"
            @click.stop
        >
            {{-- Header --}}
            <div class="flex items-start justify-between gap-4 px-5 sm:px-6 py-5 border-b border-gray-100">
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-500">Apply (Demo)</p>
                    <h3 class="mt-1 text-lg sm:text-xl font-semibold text-gray-900 break-words" x-text="applyJobTitle"></h3>
                    <p class="mt-1 text-sm text-gray-500 break-words" x-text="applyJobCompany + ' • Review your resume details'"></p>
                </div>

                <button
                    type="button"
                    @click="closeApply()"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50 shrink-0"
                    title="Close"
                >
                    <i data-lucide="x" class="h-5 w-5 text-gray-700"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-5 sm:px-6 py-6 space-y-6 bg-gray-50">

                {{-- Resume preview card --}}
                <div class="rounded-2xl bg-white border border-gray-200 p-5">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="h-11 w-11 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0">
                                <i data-lucide="file-text" class="h-5 w-5 text-emerald-700"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900">Resume on file</p>
                                <p class="mt-1 text-sm text-gray-700 truncate" x-text="resume.fileName"></p>
                                <p class="mt-1 text-xs text-gray-500" x-text="resume.fileMeta"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 justify-end">
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50"
                                title="Download (demo)"
                                @click="toastMsg('success','Download (demo)','This is a demo button. Backend is needed to download real files.')"
                            >
                                <i data-lucide="download" class="h-5 w-5 text-gray-700"></i>
                            </button>

                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50"
                                title="Replace resume"
                                @click="$refs.resumeInput.click()"
                            >
                                <i data-lucide="refresh-cw" class="h-5 w-5 text-gray-700"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Hidden file input (demo) --}}
                    <input
                        x-ref="resumeInput"
                        type="file"
                        class="hidden"
                        accept=".pdf,.doc,.docx"
                        @change="handleResumeUpload($event)"
                    />

                    {{-- Candidate info preview --}}
                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                            <p class="text-xs font-semibold text-gray-500">Full Name</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900 break-words" x-text="resume.name"></p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                            <p class="text-xs font-semibold text-gray-500">Email</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900 break-words" x-text="resume.email"></p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                            <p class="text-xs font-semibold text-gray-500">Phone</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900 break-words" x-text="resume.phone"></p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                            <p class="text-xs font-semibold text-gray-500">Headline</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900 break-words" x-text="resume.headline"></p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-4">
                        <p class="text-sm font-semibold text-blue-900">Tip</p>
                        <p class="mt-1 text-sm text-blue-800">
                            Please check if your details are correct before submitting. This is demo only—no real application is sent.
                        </p>
                    </div>
                </div>

                {{-- Cover letter (optional) --}}
                <div class="rounded-2xl bg-white border border-gray-200 p-5">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-gray-900">Short message (optional)</p>
                        <span class="text-xs text-gray-500">Demo</span>
                    </div>
                    <textarea
                        rows="4"
                        class="mt-3 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                        placeholder="Write a short message to the employer..."
                        x-model="applyMessage"
                    ></textarea>
                </div>

                {{-- Footer --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                    <button
                        type="button"
                        class="w-full sm:w-auto rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        @click="closeApply()"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        class="w-full sm:w-auto rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700"
                        @click="submitApplication()"
                    >
                        Submit Application (Demo)
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function shortlistPage() {
    return {
        query: '',
        sortBy: 'newest',

        toast: { show:false, type:'success', title:'', message:'' },

        applyModalOpen: false,
        applyJobId: null,
        applyJobTitle: '',
        applyJobCompany: '',
        applyMessage: '',

        resume: {
            fileName: 'Keith_CV_2023.pdf',
            fileMeta: '2.4 MB • Uploaded 2 days ago',
            name: 'Sarah Jenkins',
            email: 'sarah.jenkins@email.com',
            phone: '+63 9xx xxx xxxx',
            headline: 'Product Designer • UX/UI • Figma'
        },

        jobs: [
            {
                id: 1,
                title: 'Senior Product Designer',
                company: 'TechFlow',
                badge: 'TE',
                badgeBg: 'bg-blue-600',
                location: 'Remote',
                type: 'Full-time',
                salaryMin: 120000,
                salaryMax: 150000,
                salaryText: '$120k - $150k',
                status: 'Interview',
                statusPill: 'bg-emerald-50 text-emerald-700 border-emerald-100',
                postedDays: 2,
                postedText: 'Posted: 2 days ago',
                createdAt: Date.now() - (2 * 24 * 60 * 60 * 1000)
            },
            {
                id: 2,
                title: 'UX Researcher',
                company: 'Creative Studio',
                badge: 'CS',
                badgeBg: 'bg-emerald-500',
                location: 'New York, NY',
                type: 'Contract',
                salaryMin: 90000,
                salaryMax: 110000,
                salaryText: '$90k - $110k',
                status: 'Saved',
                statusPill: 'bg-blue-50 text-blue-700 border-blue-100',
                postedDays: 5,
                postedText: 'Posted: 5 days ago',
                createdAt: Date.now() - (5 * 24 * 60 * 60 * 1000)
            }
        ],

        init() {
            this.applySort(false);
            this.$nextTick(() => { window.lucide?.createIcons(); });
        },

        toastMsg(type, title, message) {
            this.toast = { show:true, type, title, message };
            this.$nextTick(() => { window.lucide?.createIcons(); });
        },

        resetFilters() {
            this.query = '';
            this.sortBy = 'newest';
            this.applySort();
            this.toastMsg('success', 'Reset', 'Search and filter were reset (demo).');
        },

        filteredJobs() {
            const q = (this.query || '').toLowerCase().trim();
            return this.jobs.filter(j => {
                if (!q) return true;
                return (
                    (j.title || '').toLowerCase().includes(q) ||
                    (j.company || '').toLowerCase().includes(q) ||
                    (j.location || '').toLowerCase().includes(q)
                );
            });
        },

        applySort(showToast = true) {
            const key = this.sortBy;

            this.jobs.sort((a, b) => {
                if (key === 'newest') return (b.createdAt || 0) - (a.createdAt || 0);
                if (key === 'oldest') return (a.createdAt || 0) - (b.createdAt || 0);
                if (key === 'company') return (a.company || '').localeCompare(b.company || '');
                if (key === 'salary') return (b.salaryMax || 0) - (a.salaryMax || 0);
                return 0;
            });

            const labelMap = { newest:'Newest First', oldest:'Oldest First', company:'Company', salary:'Salary' };
            if (showToast) this.toastMsg('success', 'Filter applied', `Showing results by: ${labelMap[key] || 'Newest First'} (demo).`);
        },

        openApply(id) {
            const job = this.jobs.find(j => j.id === id);
            if (!job) return;

            this.applyJobId = id;
            this.applyJobTitle = job.title;
            this.applyJobCompany = job.company;
            this.applyMessage = '';
            this.applyModalOpen = true;

            this.$nextTick(() => { window.lucide?.createIcons(); });
        },

        closeApply() {
            this.applyModalOpen = false;
            this.applyJobId = null;
            this.applyJobTitle = '';
            this.applyJobCompany = '';
            this.applyMessage = '';
        },

        handleResumeUpload(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;

            const sizeMB = (file.size / (1024 * 1024));
            this.resume.fileName = file.name;
            this.resume.fileMeta = `${sizeMB.toFixed(2)} MB • Selected now (demo)`;

            this.toastMsg('success', 'Resume selected', 'Resume file updated in UI (demo). Backend needed to save it.');
        },

        submitApplication() {
            const job = this.jobs.find(j => j.id === this.applyJobId);

            this.jobs = this.jobs.filter(j => j.id !== this.applyJobId);
            this.closeApply();

            this.toastMsg(
                'success',
                'Applied (demo)',
                `${job ? job.title : 'Job'} removed from shortlist after submitting. Backend needed to actually send application.`
            );
        },

        removeJob(id) {
            const job = this.jobs.find(j => j.id === id);
            this.jobs = this.jobs.filter(j => j.id !== id);

            this.toastMsg('warn', 'Removed', `${job ? job.title : 'Job'} was deleted from shortlist (demo).`);
        }
    }
}
</script>
@endsection
