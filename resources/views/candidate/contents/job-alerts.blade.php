@extends('candidate.layout')

@section('content')
<div
    class="space-y-6"
    x-data="jobAlertsApp()"
    x-init="init()"
>
    {{-- Header (responsive) --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Job Alerts</h1>

        <button type="button"
            @click="openCreate()"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition"
        >
            <i data-lucide="bell-plus" class="h-5 w-5"></i>
            <span>Create New Alert</span>
        </button>
    </div>

    {{-- Alerts list --}}
    <div class="space-y-4">
        <template x-if="alerts.length === 0">
            <div class="rounded-2xl bg-white border border-gray-200 p-6 sm:p-8 text-center text-sm text-gray-600">
                No job alerts yet. Click <span class="font-semibold">Create New Alert</span> to add one.
            </div>
        </template>

        <template x-for="alert in alerts" :key="alert.id">
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm">
                <div class="p-4 sm:p-6 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">

                    {{-- Left --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 break-words" x-text="alert.title"></h2>

                            {{-- Status badge --}}
                            <template x-if="alert.active">
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-100">
                                    Active
                                </span>
                            </template>
                            <template x-if="!alert.active">
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 border border-gray-200">
                                    Paused
                                </span>
                            </template>
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                            <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">Criteria</p>
                                <p class="mt-1 font-medium text-gray-900 break-words" x-text="buildCriteria(alert)"></p>
                            </div>

                            <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">Frequency</p>
                                <p class="mt-1 font-medium text-gray-900 break-words" x-text="alert.frequency"></p>
                            </div>

                            <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">Created</p>
                                <p class="mt-1 font-medium text-gray-900" x-text="formatDate(alert.createdAt)"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Right actions (responsive) --}}
                    <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2 lg:justify-end w-full lg:w-auto">
                        <button type="button"
                            @click="toast('Show Jobs (demo only)', 'This is frontend-only. Connect to jobs page later.')"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 hover:bg-gray-50 transition"
                        >
                            <i data-lucide="search" class="h-4 w-4 text-gray-600"></i>
                            <span>Show Jobs</span>
                        </button>

                        <div class="flex items-center gap-2 justify-end">
                            {{-- Edit --}}
                            <button type="button"
                                @click="openEdit(alert.id)"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition"
                                title="Edit"
                            >
                                <i data-lucide="pencil" class="h-5 w-5 text-gray-600"></i>
                            </button>

                            {{-- Pause/Play --}}
                            <template x-if="alert.active">
                                <button type="button"
                                    @click="toggleActive(alert.id)"
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-amber-200 bg-amber-50 hover:bg-amber-100 transition"
                                    title="Pause"
                                >
                                    <i data-lucide="pause" class="h-5 w-5 text-amber-700"></i>
                                </button>
                            </template>

                            <template x-if="!alert.active">
                                <button type="button"
                                    @click="toggleActive(alert.id)"
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 transition"
                                    title="Resume"
                                >
                                    <i data-lucide="play" class="h-5 w-5 text-emerald-700"></i>
                                </button>
                            </template>

                            {{-- Delete --}}
                            <button type="button"
                                @click="removeAlert(alert.id)"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-red-200 bg-red-50 hover:bg-red-100 transition"
                                title="Delete"
                            >
                                <i data-lucide="trash-2" class="h-5 w-5 text-red-600"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </template>
    </div>

    {{-- MODAL: Create/Edit Alert (responsive: bottom sheet on phone) --}}
    <div
        x-show="modalOpen"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:px-4"
        style="display:none;"
        role="dialog"
        aria-modal="true"
        @keydown.escape.window="closeModal()"
    >
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/40" @click="closeModal()"></div>

        {{-- Modal Card --}}
        <div
            x-transition.scale
            class="relative w-full sm:max-w-3xl bg-white shadow-xl border border-gray-200 overflow-hidden
                   rounded-t-2xl sm:rounded-2xl max-h-[88vh] sm:max-h-[92vh] overflow-y-auto"
            @click.stop
        >
            {{-- Header --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-5 border-b border-gray-200">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-10 w-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0">
                        <i data-lucide="bell" class="h-5 w-5 text-emerald-600"></i>
                    </div>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 break-words" x-text="isEditing ? 'Edit Alert' : 'Create New Alert'"></h2>
                </div>

                <button type="button"
                    @click="closeModal()"
                    class="h-10 w-10 rounded-xl hover:bg-gray-100 flex items-center justify-center transition shrink-0"
                    aria-label="Close"
                >
                    <i data-lucide="x" class="h-5 w-5 text-gray-500"></i>
                </button>
            </div>

            {{-- Body --}}
            <form class="px-4 sm:px-6 py-6 space-y-6" @submit.prevent="saveAlert()">

                {{-- Job Title or Keywords --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-900">
                        Job Title or Keywords <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        x-model.trim="form.title"
                        placeholder="e.g. Senior UX Designer, Frontend Developer"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                    <p class="text-xs text-gray-500">Enter job titles or keywords you want to be notified about</p>
                    <template x-if="errors.title">
                        <p class="text-xs text-red-600" x-text="errors.title"></p>
                    </template>
                </div>

                {{-- Location --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-900">
                        Location <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        x-model.trim="form.location"
                        placeholder="e.g. Remote, New York, San Francisco"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                    <template x-if="errors.location">
                        <p class="text-xs text-red-600" x-text="errors.location"></p>
                    </template>
                </div>

                {{-- Salary Range --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-900">Salary Range</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input type="text"
                            x-model.trim="form.salaryMin"
                            placeholder="Min (e.g. $70k)"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                        <input type="text"
                            x-model.trim="form.salaryMax"
                            placeholder="Max (e.g. $120k)"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                    </div>
                </div>

                {{-- Job Type --}}
                <div class="space-y-3">
                    <label class="text-sm font-semibold text-gray-900">Job Type</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input type="checkbox" value="Full-time" x-model="form.jobTypes"
                                   class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-200">
                            Full-time
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input type="checkbox" value="Part-time" x-model="form.jobTypes"
                                   class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-200">
                            Part-time
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input type="checkbox" value="Contract" x-model="form.jobTypes"
                                   class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-200">
                            Contract
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input type="checkbox" value="Freelance" x-model="form.jobTypes"
                                   class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-200">
                            Freelance
                        </label>
                    </div>
                </div>

                {{-- Experience Level --}}
                <div class="space-y-3">
                    <label class="text-sm font-semibold text-gray-900">Experience Level</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input type="checkbox" value="Entry Level" x-model="form.expLevels"
                                   class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-200">
                            Entry Level
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input type="checkbox" value="Mid Level" x-model="form.expLevels"
                                   class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-200">
                            Mid Level
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input type="checkbox" value="Senior Level" x-model="form.expLevels"
                                   class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-200">
                            Senior Level
                        </label>
                        <label class="flex items-center gap-3 text-sm text-gray-700">
                            <input type="checkbox" value="Lead/Manager" x-model="form.expLevels"
                                   class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-200">
                            Lead/Manager
                        </label>
                    </div>
                </div>

                {{-- Frequency --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-900">
                        Notification Frequency <span class="text-red-500">*</span>
                    </label>
                    <select
                        x-model="form.frequency"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                    >
                        <option>Daily</option>
                        <option>Weekly</option>
                        <option>Monthly</option>
                    </select>
                    <p class="text-xs text-gray-500">How often would you like to receive job alerts?</p>
                    <template x-if="errors.frequency">
                        <p class="text-xs text-red-600" x-text="errors.frequency"></p>
                    </template>
                </div>

                {{-- Footer (responsive) --}}
                <div class="pt-2 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                    <button type="button"
                        @click="closeModal()"
                        class="w-full sm:w-auto rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition"
                    >
                        Cancel
                    </button>

                    <button type="submit"
                        class="w-full sm:w-auto rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition"
                        x-text="isEditing ? 'Save Changes' : 'Create Alert'"
                    ></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Toast Notification (responsive) --}}
    <div class="fixed left-4 right-4 sm:left-auto sm:right-4 top-4 z-[60] space-y-2">
        <template x-for="t in toasts" :key="t.id">
            <div
                x-show="t.show"
                x-transition.opacity
                class="w-full sm:w-80 rounded-2xl border border-gray-200 bg-white shadow-lg p-4"
            >
                <p class="text-sm font-semibold text-gray-900 break-words" x-text="t.title"></p>
                <p class="mt-1 text-xs text-gray-600 break-words" x-text="t.message"></p>
            </div>
        </template>
    </div>
</div>

<script>
function jobAlertsApp() {
    return {
        storageKey: 'worksite_job_alerts_v1',

        alerts: [],
        modalOpen: false,
        isEditing: false,
        editId: null,

        errors: {},
        toasts: [],

        form: {
            title: '',
            location: '',
            salaryMin: '',
            salaryMax: '',
            jobTypes: [],
            expLevels: [],
            frequency: 'Daily',
        },

        init() {
            this.load();

            if (this.alerts.length === 0) {
                this.alerts = [
                    {
                        id: crypto.randomUUID(),
                        title: 'Senior UX Designer',
                        location: 'Remote',
                        salaryMin: '',
                        salaryMax: '>$100k',
                        jobTypes: ['Full-time'],
                        expLevels: ['Senior Level'],
                        frequency: 'Daily',
                        active: true,
                        createdAt: new Date().toISOString()
                    },
                    {
                        id: crypto.randomUUID(),
                        title: 'Product Designer in SF',
                        location: 'San Francisco',
                        salaryMin: '',
                        salaryMax: '',
                        jobTypes: [],
                        expLevels: [],
                        frequency: 'Weekly',
                        active: false,
                        createdAt: new Date().toISOString()
                    },
                ];
                this.save();
            }

            this.refreshIcons();
        },

        refreshIcons() {
            this.$nextTick(() => {
                window.lucide?.createIcons();
            });
        },

        load() {
            try {
                const raw = localStorage.getItem(this.storageKey);
                this.alerts = raw ? JSON.parse(raw) : [];
            } catch (e) {
                this.alerts = [];
            }
        },
        save() {
            localStorage.setItem(this.storageKey, JSON.stringify(this.alerts));
            this.refreshIcons();
        },

        formatDate(iso) {
            try {
                const d = new Date(iso);
                return d.toLocaleDateString(undefined, { year:'numeric', month:'short', day:'numeric' });
            } catch {
                return '—';
            }
        },

        buildCriteria(a) {
            const parts = [];
            if (a.location) parts.push(a.location);
            if (a.jobTypes && a.jobTypes.length) parts.push(a.jobTypes.join(', '));

            const sal = [];
            if (a.salaryMin) sal.push(a.salaryMin);
            if (a.salaryMax) sal.push(a.salaryMax);
            if (sal.length) parts.push(sal.join(' - '));

            if (a.expLevels && a.expLevels.length) parts.push(a.expLevels.join(', '));
            return parts.length ? parts.join(', ') : '—';
        },

        resetForm() {
            this.form = {
                title: '',
                location: '',
                salaryMin: '',
                salaryMax: '',
                jobTypes: [],
                expLevels: [],
                frequency: 'Daily',
            };
            this.errors = {};
            this.editId = null;
            this.isEditing = false;
        },

        validate() {
            this.errors = {};
            if (!this.form.title) this.errors.title = 'Job title is required.';
            if (!this.form.location) this.errors.location = 'Location is required.';
            if (!this.form.frequency) this.errors.frequency = 'Frequency is required.';
            return Object.keys(this.errors).length === 0;
        },

        openCreate() {
            this.resetForm();
            this.modalOpen = true;
            this.refreshIcons();
        },

        openEdit(id) {
            const a = this.alerts.find(x => x.id === id);
            if (!a) return;

            this.resetForm();
            this.isEditing = true;
            this.editId = id;

            this.form.title = a.title || '';
            this.form.location = a.location || '';
            this.form.salaryMin = a.salaryMin || '';
            this.form.salaryMax = a.salaryMax || '';
            this.form.jobTypes = Array.isArray(a.jobTypes) ? [...a.jobTypes] : [];
            this.form.expLevels = Array.isArray(a.expLevels) ? [...a.expLevels] : [];
            this.form.frequency = a.frequency || 'Daily';

            this.modalOpen = true;
            this.refreshIcons();
        },

        closeModal() {
            this.modalOpen = false;
            this.errors = {};
        },

        saveAlert() {
            if (!this.validate()) return;

            if (this.isEditing && this.editId) {
                const idx = this.alerts.findIndex(x => x.id === this.editId);
                if (idx !== -1) {
                    this.alerts[idx] = {
                        ...this.alerts[idx],
                        title: this.form.title,
                        location: this.form.location,
                        salaryMin: this.form.salaryMin,
                        salaryMax: this.form.salaryMax,
                        jobTypes: this.form.jobTypes,
                        expLevels: this.form.expLevels,
                        frequency: this.form.frequency,
                    };
                    this.save();
                    this.toast('Alert updated', 'Your job alert was updated successfully.');
                }
            } else {
                this.alerts.unshift({
                    id: crypto.randomUUID(),
                    title: this.form.title,
                    location: this.form.location,
                    salaryMin: this.form.salaryMin,
                    salaryMax: this.form.salaryMax,
                    jobTypes: this.form.jobTypes,
                    expLevels: this.form.expLevels,
                    frequency: this.form.frequency,
                    active: true,
                    createdAt: new Date().toISOString(),
                });
                this.save();
                this.toast('Alert created', 'Your new job alert was saved successfully.');
            }

            this.modalOpen = false;
            this.resetForm();
        },

        toggleActive(id) {
            const a = this.alerts.find(x => x.id === id);
            if (!a) return;

            a.active = !a.active;
            this.save();

            if (a.active) this.toast('Alert On', 'This job alert is now active.');
            else this.toast('Alert Off', 'This job alert is now paused.');
        },

        removeAlert(id) {
            const a = this.alerts.find(x => x.id === id);
            if (!a) return;

            if (!confirm('Are you sure you want to delete this alert?')) return;

            this.alerts = this.alerts.filter(x => x.id !== id);
            this.save();
            this.toast('Alert deleted', 'The job alert was removed.');
        },

        toast(title, message) {
            const id = crypto.randomUUID();
            const t = { id, title, message, show: true };
            this.toasts.unshift(t);

            setTimeout(() => {
                const i = this.toasts.findIndex(x => x.id === id);
                if (i !== -1) this.toasts[i].show = false;
            }, 2200);

            setTimeout(() => {
                this.toasts = this.toasts.filter(x => x.id !== id);
            }, 2600);
        },
    }
}
</script>
@endsection
