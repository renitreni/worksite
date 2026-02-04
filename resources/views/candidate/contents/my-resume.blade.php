@extends('candidate.layout')

@section('content')
<div class="space-y-6" x-data="resumePage()" x-init="init()">

    {{-- Header --}}
    <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">My Resume</h1>

        <button type="button"
            @click="openPreview()"
            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
            <i data-lucide="file-text" class="h-4 w-4"></i>
            Preview Resume
        </button>
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
        <div class="flex-1">
            <p class="font-semibold" x-text="toast.title"></p>
            <p class="mt-0.5" x-text="toast.message"></p>
        </div>
        <button type="button" class="text-xs underline opacity-80 hover:opacity-100" @click="toast.show=false">
            Close
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left column --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- CV Upload --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-gray-900">CV / Resume</h2>

                {{-- Hidden input --}}
                <input
                    x-ref="fileInput"
                    type="file"
                    class="hidden"
                    accept=".pdf,.doc,.docx"
                    @change="handleFileSelect($event)"
                >

                {{-- Dropzone --}}
                <div
                    class="mt-4 rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center cursor-pointer hover:bg-gray-100/60 transition"
                    :class="isDragging ? 'ring-2 ring-emerald-200 border-emerald-300 bg-emerald-50/40' : ''"
                    @click="$refs.fileInput.click()"
                    @dragover.prevent="isDragging=true"
                    @dragleave.prevent="isDragging=false"
                    @drop.prevent="handleDrop($event)"
                >
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white border border-gray-200">
                        <i data-lucide="upload-cloud" class="h-6 w-6 text-gray-500"></i>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-gray-800">Click to upload or drag and drop</p>
                    <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX (Max 5MB) — demo only</p>
                </div>

                {{-- Uploaded list --}}
                <div class="mt-5 space-y-3">
                    <template x-if="files.length === 0">
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 text-sm text-gray-600">
                            No uploaded resume yet. Upload one to see it here.
                        </div>
                    </template>

                    <template x-for="f in files" :key="f.id">
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="h-10 w-10 rounded-2xl bg-red-50 border border-red-100 flex items-center justify-center">
                                    <i data-lucide="file" class="h-5 w-5 text-red-500"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate" x-text="f.name"></p>
                                    <p class="text-xs text-gray-500">
                                        <span x-text="formatBytes(f.size)"></span>
                                        <span> • </span>
                                        <span x-text="f.meta"></span>
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="downloadFile(f)"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50"
                                    title="Download"
                                >
                                    <i data-lucide="download" class="h-5 w-5 text-gray-600"></i>
                                </button>
                                <button
                                    type="button"
                                    @click="removeFile(f.id)"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50"
                                    title="Delete"
                                >
                                    <i data-lucide="trash-2" class="h-5 w-5 text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Skills --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">Skills</h2>

                    <button type="button"
                        @click="openSkillsModal()"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900">
                        <i data-lucide="plus" class="h-4 w-4"></i> Add
                    </button>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <template x-if="selectedSkills.length === 0">
                        <span class="text-sm text-gray-500">No skills selected yet.</span>
                    </template>

                    <template x-for="s in selectedSkills" :key="s">
                        <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 border border-gray-200 px-3 py-1 text-xs font-semibold text-gray-700">
                            <span x-text="s"></span>
                            <button type="button" class="hover:text-gray-900" @click="removeSkill(s)" title="Remove">
                                ✕
                            </button>
                        </span>
                    </template>
                </div>

                <div class="mt-4">
                    <button type="button"
                        @click="openSkillsModal()"
                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Choose / Search Skills
                    </button>
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- Work Experience --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">Work Experience</h2>
                    <button type="button"
                        @click="openExpModal('create')"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        <i data-lucide="plus" class="h-4 w-4"></i> Add Experience
                    </button>
                </div>

                <div class="mt-5 space-y-6">
                    <template x-if="experiences.length === 0">
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 text-sm text-gray-600">
                            No experience added yet. Click <span class="font-semibold">Add Experience</span> to create one (demo).
                        </div>
                    </template>

                    <template x-for="(exp, idx) in experiences" :key="exp.id">
                        <div>
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900" x-text="exp.role"></p>
                                    <p class="text-sm font-semibold text-blue-600 mt-1" x-text="exp.company"></p>
                                    <p class="text-xs text-gray-500 mt-1" x-text="exp.period"></p>
                                    <p class="text-sm text-gray-600 mt-3 leading-relaxed" x-text="exp.description"></p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <button type="button"
                                        @click="openExpModal('edit', exp)"
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50"
                                        title="Edit">
                                        <i data-lucide="pencil" class="h-4 w-4 text-gray-600"></i>
                                    </button>
                                    <button type="button"
                                        @click="deleteExperience(exp.id)"
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50"
                                        title="Delete">
                                        <i data-lucide="trash-2" class="h-4 w-4 text-gray-600"></i>
                                    </button>
                                </div>
                            </div>

                            <template x-if="idx !== experiences.length - 1">
                                <div class="mt-6 h-px bg-gray-100"></div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Education --}}
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">Education</h2>
                    <button type="button"
                        @click="openEduModal('create')"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        <i data-lucide="plus" class="h-4 w-4"></i> Add Education
                    </button>
                </div>

                <div class="mt-5 space-y-5">
                    <template x-if="educations.length === 0">
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 text-sm text-gray-600">
                            No education added yet. Click <span class="font-semibold">Add Education</span> to create one (demo).
                        </div>
                    </template>

                    <template x-for="edu in educations" :key="edu.id">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900" x-text="edu.degree"></p>
                                <p class="text-sm font-semibold text-emerald-600 mt-1" x-text="edu.school"></p>
                                <p class="text-xs text-gray-500 mt-1" x-text="edu.year"></p>
                                <p class="text-sm text-gray-600 mt-3 leading-relaxed" x-text="edu.notes"></p>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button"
                                    @click="openEduModal('edit', edu)"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50"
                                    title="Edit">
                                    <i data-lucide="pencil" class="h-4 w-4 text-gray-600"></i>
                                </button>
                                <button type="button"
                                    @click="deleteEducation(edu.id)"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50"
                                    title="Delete">
                                    <i data-lucide="trash-2" class="h-4 w-4 text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>

    <div
        x-show="skillsModalOpen"
        x-transition.opacity
        class="fixed inset-0 z-[999] flex items-center justify-center p-3 sm:p-6"
        role="dialog"
        aria-modal="true"
        @keydown.escape.window="closeSkillsModal()"
        x-cloak
    >
        <div class="absolute inset-0 bg-gray-900/40" @click="closeSkillsModal()"></div>

        <div
            x-transition
            @click.stop
            class="relative w-full max-w-2xl rounded-2xl bg-white border border-gray-200 shadow-xl overflow-hidden"
        >
            <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-200">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900">Choose Skills</p>
                    <p class="text-xs text-gray-500">Search then click a skill to add/remove (demo).</p>
                </div>
                <button type="button" @click="closeSkillsModal()"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                    title="Close">
                    <i data-lucide="x" class="h-5 w-5 text-gray-700"></i>
                </button>
            </div>

            <div class="p-5 sm:p-6 space-y-4">
                {{-- Search --}}
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i data-lucide="search" class="h-4 w-4"></i>
                    </span>
                    <input
                        type="text"
                        x-model.trim="skillQuery"
                        placeholder="Search skills (e.g. Laravel, Figma, JavaScript)..."
                        class="w-full rounded-2xl border border-gray-200 bg-gray-50 pl-11 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                    >
                </div>

                {{-- Selected --}}
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold text-gray-700">Selected</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <template x-if="selectedSkills.length === 0">
                            <span class="text-sm text-gray-500">None yet.</span>
                        </template>

                        <template x-for="s in selectedSkills" :key="'sel-'+s">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white border border-gray-200 px-3 py-1 text-xs font-semibold text-gray-700">
                                <span x-text="s"></span>
                                <button type="button" class="hover:text-gray-900" @click="toggleSkill(s)" title="Remove">
                                    ✕
                                </button>
                            </span>
                        </template>
                    </div>
                </div>

                {{-- List --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-[360px] overflow-y-auto pr-1">
                    <template x-for="s in filteredSkills()" :key="'all-'+s">
                        <button type="button"
                            @click="toggleSkill(s)"
                            class="text-left rounded-xl border px-4 py-3 text-sm font-semibold transition"
                            :class="selectedSkills.includes(s)
                                ? 'border-emerald-200 bg-emerald-50 text-emerald-800'
                                : 'border-gray-200 bg-white text-gray-800 hover:bg-gray-50'">
                            <span x-text="s"></span>
                        </button>
                    </template>
                </div>

                {{-- Footer --}}
                <div class="pt-2 border-t border-gray-200 flex items-center justify-between gap-3">
                    <button type="button"
                        @click="clearSkills()"
                        class="rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Clear
                    </button>

                    <div class="flex items-center gap-3">
                        <button type="button"
                            @click="closeSkillsModal()"
                            class="rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="button"
                            @click="saveSkills()"
                            class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                            Save Skills
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
   
    <div
        x-show="previewOpen"
        x-transition.opacity
        class="fixed inset-0 z-[999] flex items-center justify-center p-3 sm:p-6"
        role="dialog"
        aria-modal="true"
        @keydown.escape.window="previewOpen=false"
        x-cloak
    >
        <div class="absolute inset-0 bg-gray-900/40" @click="previewOpen=false"></div>

        <div
            x-transition
            @click.stop
            class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white border border-gray-200 shadow-xl"
        >
            <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-200">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900">Resume Preview (Demo)</p>
                    <p class="text-xs text-gray-500">This preview uses the experiences + education + skills you add.</p>
                </div>
                <button type="button"
                    @click="previewOpen=false"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                    title="Close">
                    <i data-lucide="x" class="h-5 w-5 text-gray-700"></i>
                </button>
            </div>

            <div class="p-5 sm:p-6 space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
                    <p class="text-lg font-bold text-gray-900">Sarah Jenkins</p>
                    <p class="text-sm text-gray-600 mt-1">Product / UX • sarah@email.com • +63 900 000 0000</p>
                    <p class="text-sm text-gray-600 mt-3 leading-relaxed">
                        Summary: Passionate designer with strong UX skills. (Demo summary — you can customize later.)
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <p class="text-sm font-semibold text-gray-900">Skills</p>
                    <p class="text-sm text-gray-600 mt-2" x-text="selectedSkills.length ? selectedSkills.join(', ') : 'No skills selected yet.'"></p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <p class="text-sm font-semibold text-gray-900">Uploaded Resume File</p>
                    <p class="text-sm text-gray-600 mt-2" x-text="files.length ? files[0].name : 'No file uploaded yet.'"></p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <p class="text-sm font-semibold text-gray-900">Work Experience</p>
                    <div class="mt-4 space-y-4">
                        <template x-if="experiences.length === 0">
                            <p class="text-sm text-gray-600">No experience added yet.</p>
                        </template>
                        <template x-for="exp in experiences" :key="'p'+exp.id">
                            <div class="border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                <p class="text-sm font-semibold text-gray-900" x-text="exp.role"></p>
                                <p class="text-sm text-blue-600 font-semibold mt-1" x-text="exp.company"></p>
                                <p class="text-xs text-gray-500 mt-1" x-text="exp.period"></p>
                                <p class="text-sm text-gray-600 mt-2" x-text="exp.description"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <p class="text-sm font-semibold text-gray-900">Education</p>
                    <div class="mt-4 space-y-4">
                        <template x-if="educations.length === 0">
                            <p class="text-sm text-gray-600">No education added yet.</p>
                        </template>
                        <template x-for="edu in educations" :key="'e'+edu.id">
                            <div class="border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                                <p class="text-sm font-semibold text-gray-900" x-text="edu.degree"></p>
                                <p class="text-sm text-emerald-600 font-semibold mt-1" x-text="edu.school"></p>
                                <p class="text-xs text-gray-500 mt-1" x-text="edu.year"></p>
                                <p class="text-sm text-gray-600 mt-2" x-text="edu.notes"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        x-show="expModal.open"
        x-transition.opacity
        class="fixed inset-0 z-[999] flex items-center justify-center p-3 sm:p-6"
        role="dialog"
        aria-modal="true"
        @keydown.escape.window="closeExpModal()"
        x-cloak
    >
        <div class="absolute inset-0 bg-gray-900/40" @click="closeExpModal()"></div>

        <div
            x-transition
            @click.stop
            class="relative w-full max-w-2xl rounded-2xl bg-white border border-gray-200 shadow-xl overflow-hidden"
        >
            <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-200">
                <p class="text-sm font-semibold text-gray-900" x-text="expModal.mode === 'edit' ? 'Edit Experience' : 'Add Experience'"></p>
                <button type="button" @click="closeExpModal()"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                    title="Close">
                    <i data-lucide="x" class="h-5 w-5 text-gray-700"></i>
                </button>
            </div>

            <form class="p-5 sm:p-6 space-y-4" @submit.prevent="saveExperience()">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-900">Role</label>
                        <input type="text" x-model.trim="expModal.form.role"
                            placeholder="e.g. UI/UX Designer"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-900">Company</label>
                        <input type="text" x-model.trim="expModal.form.company"
                            placeholder="e.g. TechFlow Inc."
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-900">Start</label>
                        <input type="text" x-model.trim="expModal.form.start"
                            placeholder="e.g. Jan 2023"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-900">End</label>
                        <input type="text" x-model.trim="expModal.form.end"
                            placeholder="e.g. Present / Dec 2024"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-900">Description</label>
                    <textarea rows="4" x-model.trim="expModal.form.description"
                        placeholder="What did you do in this role?"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"></textarea>
                </div>

                <div class="pt-2 border-t border-gray-200 flex items-center justify-end gap-3">
                    <button type="button" @click="closeExpModal()"
                        class="rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
   
    <div
        x-show="eduModal.open"
        x-transition.opacity
        class="fixed inset-0 z-[999] flex items-center justify-center p-3 sm:p-6"
        role="dialog"
        aria-modal="true"
        @keydown.escape.window="closeEduModal()"
        x-cloak
    >
        <div class="absolute inset-0 bg-gray-900/40" @click="closeEduModal()"></div>

        <div
            x-transition
            @click.stop
            class="relative w-full max-w-2xl rounded-2xl bg-white border border-gray-200 shadow-xl overflow-hidden"
        >
            <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-200">
                <p class="text-sm font-semibold text-gray-900" x-text="eduModal.mode === 'edit' ? 'Edit Education' : 'Add Education'"></p>
                <button type="button" @click="closeEduModal()"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                    title="Close">
                    <i data-lucide="x" class="h-5 w-5 text-gray-700"></i>
                </button>
            </div>

            <form class="p-5 sm:p-6 space-y-4" @submit.prevent="saveEducation()">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-900">Degree</label>
                        <input type="text" x-model.trim="eduModal.form.degree"
                            placeholder="e.g. BS Computer Science"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-900">School</label>
                        <input type="text" x-model.trim="eduModal.form.school"
                            placeholder="e.g. University Name"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-900">Year</label>
                        <input type="text" x-model.trim="eduModal.form.year"
                            placeholder="e.g. 2020 - 2024"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-900">Notes</label>
                        <input type="text" x-model.trim="eduModal.form.notes"
                            placeholder="e.g. Dean's lister / Thesis"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300">
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-200 flex items-center justify-end gap-3">
                    <button type="button" @click="closeEduModal()"
                        class="rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{--  /EDUCATION MODAL  --}}

</div>

<script>
function resumePage() {
    return {
        
        skillsModalOpen: false,
        skillQuery: '',
        allSkills: [
            'Laravel','PHP','MySQL','REST API','Blade','Alpine.js','Tailwind CSS','JavaScript','TypeScript','React','Next.js',
            'HTML','CSS','Git','GitHub','Figma','UI Design','UX Design','User Research','Wireframing','Prototyping',
            'Node.js','Express','Python','Java','C#','C++','SQL','Firebase','Docker','Linux','AWS','Azure',
            'Problem Solving','Communication','Teamwork','Time Management','Debugging','Testing','Agile/Scrum'
        ],
        selectedSkills: ['Figma','React','TypeScript','User Research','Prototyping','Tailwind CSS','Next.js'],

        
        files: [],
        isDragging: false,

        
        experiences: [
            {
                id: 1,
                role: 'Senior Product Designer',
                company: 'TechFlow Inc.',
                period: 'Jan 2021 - Present • 2 yrs 9 mos',
                description: 'Leading the design system initiative and managing a team of 3 designers. Responsible for core product experience and user research.'
            }
        ],
        educations: [
            {
                id: 1,
                degree: 'Master of Interaction Design',
                school: 'California College of the Arts',
                year: '2018 - 2020',
                notes: 'Focus on human-computer interaction and user research methodologies.'
            }
        ],

        previewOpen: false,

        toast: { show:false, type:'success', title:'', message:'' },

        expModal: {
            open: false,
            mode: 'create',
            id: null,
            form: { role:'', company:'', start:'', end:'', description:'' }
        },

        eduModal: {
            open: false,
            mode: 'create',
            id: null,
            form: { degree:'', school:'', year:'', notes:'' }
        },

        init() {
            this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
        },

        
        toastMsg(type, title, message) {
            this.toast = { show:true, type, title, message };
            this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
        },

       
        openSkillsModal() {
            this.skillsModalOpen = true;
            this.skillQuery = '';
            this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
        },
        closeSkillsModal() {
            this.skillsModalOpen = false;
        },
        filteredSkills() {
            const q = (this.skillQuery || '').toLowerCase();
            if (!q) return this.allSkills;
            return this.allSkills.filter(s => s.toLowerCase().includes(q));
        },
        toggleSkill(skill) {
            if (this.selectedSkills.includes(skill)) {
                this.selectedSkills = this.selectedSkills.filter(s => s !== skill);
            } else {
                this.selectedSkills.push(skill);
            }
        },
        removeSkill(skill) {
            this.selectedSkills = this.selectedSkills.filter(s => s !== skill);
        },
        clearSkills() {
            this.selectedSkills = [];
            this.toastMsg('warn','Cleared','All selected skills were removed (demo).');
        },
        saveSkills() {
            this.toastMsg('success','Saved (demo)','Skills updated. Backend needed to save permanently.');
            this.closeSkillsModal();
        },

        
        handleFileSelect(e) {
            const file = e.target.files?.[0];
            if (!file) return;
            this.addDemoFile(file);
            e.target.value = '';
        },
        handleDrop(e) {
            this.isDragging = false;
            const file = e.dataTransfer.files?.[0];
            if (!file) return;
            this.addDemoFile(file);
        },
        addDemoFile(file) {
            const extOk = /\.(pdf|doc|docx)$/i.test(file.name);
            if (!extOk) {
                this.toastMsg('error','Invalid file','Please upload PDF, DOC, or DOCX only (demo).');
                return;
            }
            const max = 5 * 1024 * 1024;
            if (file.size > max) {
                this.toastMsg('error','File too large','Max size is 5MB (demo).');
                return;
            }

            const url = URL.createObjectURL(file);

            this.files.unshift({
                id: Date.now(),
                name: file.name,
                size: file.size,
                meta: 'Uploaded just now (demo)',
                url
            });

            this.toastMsg('success','Uploaded (demo)','Your file appears below. Backend is needed to save it permanently.');
            this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
        },
        downloadFile(f) {
            if (!f.url) {
                this.toastMsg('warn','Demo only','No downloadable URL available.');
                return;
            }
            const a = document.createElement('a');
            a.href = f.url;
            a.download = f.name || 'resume';
            document.body.appendChild(a);
            a.click();
            a.remove();
        },
        removeFile(id) {
            const f = this.files.find(x => x.id === id);
            if (f?.url && f.url.startsWith('blob:')) URL.revokeObjectURL(f.url);
            this.files = this.files.filter(x => x.id !== id);
            this.toastMsg('success','Deleted (demo)','The file was removed from the list.');
        },
        formatBytes(bytes) {
            if (!bytes && bytes !== 0) return '';
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), sizes.length - 1);
            const val = bytes / Math.pow(1024, i);
            return `${val.toFixed(i === 0 ? 0 : 1)} ${sizes[i]}`;
        },

        
        openPreview() {
            this.previewOpen = true;
            this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
        },

       
        openExpModal(mode, exp = null) {
            this.expModal.open = true;
            this.expModal.mode = mode;
            this.expModal.id = exp?.id ?? null;

            if (mode === 'edit' && exp) {
                const start = (exp.period || '').split('-')[0]?.trim() || '';
                const end = (exp.period || '').split('-')[1]?.split('•')[0]?.trim() || '';
                this.expModal.form = { role: exp.role, company: exp.company, start, end, description: exp.description };
            } else {
                this.expModal.form = { role:'', company:'', start:'', end:'', description:'' };
            }

            this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
        },
        closeExpModal() { this.expModal.open = false; },
        saveExperience() {
            const f = this.expModal.form;
            if (!f.role || !f.company || !f.start || !f.end) {
                this.toastMsg('error','Missing fields','Please fill Role, Company, Start, and End.');
                return;
            }
            const period = `${f.start} - ${f.end} • (demo)`;

            if (this.expModal.mode === 'edit') {
                const idx = this.experiences.findIndex(x => x.id === this.expModal.id);
                if (idx >= 0) this.experiences[idx] = { ...this.experiences[idx], role: f.role, company: f.company, period, description: f.description || '—' };
                this.toastMsg('success','Updated (demo)','Work experience was updated.');
            } else {
                this.experiences.unshift({ id: Date.now(), role: f.role, company: f.company, period, description: f.description || '—' });
                this.toastMsg('success','Added (demo)','Work experience was added.');
            }

            this.closeExpModal();
        },
        deleteExperience(id) {
            this.experiences = this.experiences.filter(x => x.id !== id);
            this.toastMsg('success','Deleted (demo)','Work experience removed.');
        },

    
        openEduModal(mode, edu = null) {
            this.eduModal.open = true;
            this.eduModal.mode = mode;
            this.eduModal.id = edu?.id ?? null;

            if (mode === 'edit' && edu) {
                this.eduModal.form = { degree: edu.degree, school: edu.school, year: edu.year, notes: edu.notes };
            } else {
                this.eduModal.form = { degree:'', school:'', year:'', notes:'' };
            }

            this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
        },
        closeEduModal() { this.eduModal.open = false; },
        saveEducation() {
            const f = this.eduModal.form;
            if (!f.degree || !f.school || !f.year) {
                this.toastMsg('error','Missing fields','Please fill Degree, School, and Year.');
                return;
            }

            if (this.eduModal.mode === 'edit') {
                const idx = this.educations.findIndex(x => x.id === this.eduModal.id);
                if (idx >= 0) this.educations[idx] = { ...this.educations[idx], degree: f.degree, school: f.school, year: f.year, notes: f.notes || '—' };
                this.toastMsg('success','Updated (demo)','Education updated.');
            } else {
                this.educations.unshift({ id: Date.now(), degree: f.degree, school: f.school, year: f.year, notes: f.notes || '—' });
                this.toastMsg('success','Added (demo)','Education added.');
            }

            this.closeEduModal();
        },
        deleteEducation(id) {
            this.educations = this.educations.filter(x => x.id !== id);
            this.toastMsg('success','Deleted (demo)','Education removed.');
        }
    }
}
</script>
@endsection
