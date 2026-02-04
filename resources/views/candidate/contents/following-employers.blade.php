@extends('candidate.layout')

@section('content')
<div class="space-y-6" x-data="followingEmployersPage()" x-init="init()">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="space-y-1">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Following Employers</h1>
            <p class="text-sm text-gray-500">Frontend demo only (search, follow/unfollow, view profile modal).</p>
        </div>

        {{-- Search (responsive) --}}
        <div class="relative w-full sm:max-w-md lg:w-96">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i data-lucide="search" class="h-4 w-4"></i>
            </span>
            <input
                type="text"
                placeholder="Search employers..."
                class="w-full rounded-xl border border-gray-200 bg-white pl-9 pr-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                x-model.trim="query"
            />
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
    <template x-if="filteredEmployers().length === 0">
        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6 sm:p-8 text-center">
            <div class="mx-auto h-12 w-12 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center">
                <i data-lucide="building-2" class="h-6 w-6 text-gray-500"></i>
            </div>
            <p class="mt-3 text-sm font-semibold text-gray-900">No employers found</p>
            <p class="mt-1 text-sm text-gray-600">Try another keyword.</p>
            <button
                type="button"
                class="mt-4 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                @click="query=''"
            >
                Clear search
            </button>
        </div>
    </template>

    {{-- Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
        <template x-for="emp in filteredEmployers()" :key="emp.id">
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-6">
                <div class="flex flex-col items-center text-center">
                    <div class="h-16 w-16 rounded-2xl text-white flex items-center justify-center text-2xl font-semibold"
                         :class="emp.badgeBg"
                         x-text="emp.badge">
                    </div>

                    <p class="mt-4 text-sm font-semibold text-gray-900 break-words" x-text="emp.name"></p>
                    <p class="text-xs text-gray-500 mt-1 break-words" x-text="emp.industry"></p>

                    <div class="mt-4 flex items-center justify-center gap-4 text-xs text-gray-500 flex-wrap">
                        <span class="inline-flex items-center gap-1">
                            <i data-lucide="map-pin" class="h-4 w-4"></i>
                            <span class="break-words" x-text="emp.location"></span>
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <i data-lucide="briefcase" class="h-4 w-4"></i>
                            <span x-text="emp.openJobs + ' Open Jobs'"></span>
                        </span>
                    </div>

                    {{-- Actions: stack on very small screens --}}
                    <div class="mt-5 flex flex-col sm:flex-row w-full gap-3">
                        <button
                            type="button"
                            class="w-full sm:flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                            @click="openProfile(emp)"
                        >
                            View Profile
                        </button>

                        <button
                            type="button"
                            class="w-full sm:flex-1 rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition"
                            :class="emp.following ? 'bg-blue-600 hover:bg-blue-700' : 'bg-emerald-600 hover:bg-emerald-700'"
                            @click="toggleFollow(emp.id)"
                            x-text="emp.following ? 'Unfollow' : 'Follow'"
                        ></button>
                    </div>

                    <template x-if="!emp.following">
                        <p class="mt-3 text-xs text-gray-500">
                            Not following. Click <span class="font-semibold">Follow</span> to see updates (demo).
                        </p>
                    </template>
                </div>
            </div>
        </template>
    </div>

    {{-- Profile Modal (responsive: bottom-sheet on phone) --}}
    <div
        x-show="profileModalOpen"
        x-transition.opacity
        x-cloak
        class="fixed inset-0 z-[999] flex items-end sm:items-center justify-center p-0 sm:p-4"
        role="dialog"
        aria-modal="true"
        @keydown.escape.window="closeProfile()"
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-gray-900/40" @click="closeProfile()"></div>

        {{-- Panel --}}
        <div
            class="relative w-full sm:max-w-3xl bg-white border border-gray-200 shadow-xl overflow-hidden
                   rounded-t-2xl sm:rounded-2xl max-h-[88vh] sm:max-h-[92vh] overflow-y-auto"
            @click.stop
        >
            {{-- Top bar --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-10 w-10 rounded-xl text-white flex items-center justify-center font-semibold shrink-0"
                         :class="selectedEmployer?.badgeBg"
                         x-text="selectedEmployer?.badge">
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="selectedEmployer?.name"></p>
                        <p class="text-xs text-gray-500 truncate" x-text="selectedEmployer?.industry"></p>
                    </div>
                </div>

                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50 shrink-0"
                    @click="closeProfile()"
                    title="Close"
                >
                    <i data-lucide="x" class="h-5 w-5 text-gray-700"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6 space-y-6 bg-gray-50">
                {{-- Quick stats --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="rounded-2xl bg-white border border-gray-200 p-4">
                        <p class="text-xs font-semibold text-gray-500">Location</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 break-words" x-text="selectedEmployer?.location"></p>
                    </div>
                    <div class="rounded-2xl bg-white border border-gray-200 p-4">
                        <p class="text-xs font-semibold text-gray-500">Open Jobs</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900" x-text="selectedEmployer?.openJobs"></p>
                    </div>
                    <div class="rounded-2xl bg-white border border-gray-200 p-4">
                        <p class="text-xs font-semibold text-gray-500">Company Size</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900" x-text="selectedEmployer?.size"></p>
                    </div>
                </div>

                {{-- About --}}
                <div class="rounded-2xl bg-white border border-gray-200 p-5">
                    <p class="text-sm font-semibold text-gray-900">About</p>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed break-words" x-text="selectedEmployer?.about"></p>
                </div>

                {{-- Sample open roles --}}
                <div class="rounded-2xl bg-white border border-gray-200 p-5">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-gray-900">Featured Roles</p>
                        <span class="text-xs text-gray-500">Demo list</span>
                    </div>

                    <div class="mt-4 space-y-3">
                        <template x-for="role in (selectedEmployer?.featuredRoles || [])" :key="role">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate" x-text="role"></p>
                                    <p class="text-xs text-gray-500">Click “Follow” to get updates (demo).</p>
                                </div>
                                <button
                                    type="button"
                                    class="w-full sm:w-auto rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                    @click="toastMsg('success','Saved (demo)','Role saved in UI only. Backend needed.')"
                                >
                                    Save
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Footer actions --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                    <button
                        type="button"
                        class="w-full sm:w-auto rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        @click="closeProfile()"
                    >
                        Close
                    </button>

                    <button
                        type="button"
                        class="w-full sm:w-auto rounded-xl px-5 py-3 text-sm font-semibold text-white transition"
                        :class="selectedEmployer?.following ? 'bg-blue-600 hover:bg-blue-700' : 'bg-emerald-600 hover:bg-emerald-700'"
                        @click="selectedEmployer && toggleFollow(selectedEmployer.id)"
                        x-text="selectedEmployer?.following ? 'Unfollow' : 'Follow'"
                    ></button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function followingEmployersPage() {
    return {
        query: '',
        profileModalOpen: false,
        selectedEmployer: null,

        toast: { show:false, type:'success', title:'', message:'' },

        employers: [
            {
                id: 1,
                name: 'TechFlow',
                industry: 'Software',
                location: 'San Francisco, CA',
                openJobs: 12,
                badge: 'TE',
                badgeBg: 'bg-blue-600',
                following: true,
                size: '200–500',
                about: 'TechFlow builds workflow automation tools for teams. The company focuses on scalable SaaS products and clean UX.',
                featuredRoles: ['Senior Product Designer', 'Frontend Developer', 'QA Engineer']
            },
            {
                id: 2,
                name: 'Creative Studio',
                industry: 'Design Agency',
                location: 'New York, NY',
                openJobs: 5,
                badge: 'CS',
                badgeBg: 'bg-emerald-500',
                following: true,
                size: '50–100',
                about: 'Creative Studio is a product and brand design agency working with startups and global companies.',
                featuredRoles: ['UX Researcher', 'UI Designer', 'Brand Designer']
            },
            {
                id: 3,
                name: 'Global Systems',
                industry: 'Enterprise IT',
                location: 'Chicago, IL',
                openJobs: 24,
                badge: 'GS',
                badgeBg: 'bg-slate-800',
                following: false,
                size: '1000+',
                about: 'Global Systems delivers enterprise IT solutions and managed services for large organizations.',
                featuredRoles: ['Systems Analyst', 'IT Support Specialist', 'Project Manager']
            }
        ],

        init() {
            this.$nextTick(() => { window.lucide?.createIcons(); });
        },

        toastMsg(type, title, message) {
            this.toast = { show:true, type, title, message };
            this.$nextTick(() => { window.lucide?.createIcons(); });
        },

        filteredEmployers() {
            const q = (this.query || '').toLowerCase().trim();
            if (!q) return this.employers;

            return this.employers.filter(e => (
                (e.name || '').toLowerCase().includes(q) ||
                (e.industry || '').toLowerCase().includes(q) ||
                (e.location || '').toLowerCase().includes(q)
            ));
        },

        openProfile(emp) {
            this.selectedEmployer = emp;
            this.profileModalOpen = true;
            this.toastMsg('success', 'Profile opened', `Viewing ${emp.name} (demo).`);
        },

        closeProfile() {
            this.profileModalOpen = false;
            this.selectedEmployer = null;
        },

        toggleFollow(id) {
            const idx = this.employers.findIndex(e => e.id === id);
            if (idx === -1) return;

            this.employers[idx].following = !this.employers[idx].following;

            if (this.selectedEmployer && this.selectedEmployer.id === id) {
                this.selectedEmployer = this.employers[idx];
            }

            const state = this.employers[idx].following ? 'Followed' : 'Unfollowed';
            this.toastMsg(
                this.employers[idx].following ? 'success' : 'warn',
                state,
                `${state} ${this.employers[idx].name} (frontend demo).`
            );
        }
    }
}
</script>
@endsection
