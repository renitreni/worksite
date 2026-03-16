@php
    $u = auth()->user();
    $resume = $u?->candidateResume;

    $fullNameDefault = $u ? (trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?: $u->name ?? '') : '';
    $emailDefault = $u?->email ?? '';
    $phoneDefault = $u?->phone ?? '';
@endphp

<div x-cloak x-show="modal === 'apply'" x-transition.opacity
    class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-6">

    {{-- Background --}}
    <div class="absolute inset-0 bg-black/50" @click="closeModal()"></div>

    <div x-data="{
        step: 1,
    
        full_name: @js(old('full_name', $fullNameDefault)),
        email: @js(old('email', $emailDefault)),
        phone: @js(old('phone', $phoneDefault)),
    
        resumeName: '',
    
        next() {
            if (this.step === 1) {
                if (!this.full_name || !this.email) return
            }
            this.step = Math.min(3, this.step + 1)
        },
    
        back() {
            this.step = Math.max(1, this.step - 1)
        },
    
        canGoNext() {
            if (this.step === 1) {
                return !!this.full_name && !!this.email
            }
            return true
        }
    }" x-trap.noscroll="modal === 'apply'"
        class="relative w-full max-w-2xl rounded-3xl bg-white shadow-2xl ring-1 ring-black/5 overflow-hidden max-h-[92vh] flex flex-col">

        {{-- HEADER --}}
        <div class="px-5 sm:px-7 py-5 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-white shrink-0">

            <div class="flex items-start justify-between">

                <div>
                    <h3 class="text-lg sm:text-xl font-semibold text-slate-900">Apply Now</h3>
                    <p class="text-sm text-slate-600 mt-1">
                        Step <span x-text="step"></span> of 3 • Review before submitting
                    </p>
                </div>

                <button @click="closeModal()" class="rounded-xl p-2 hover:bg-slate-100">
                    <x-lucide-icon name="x" class="w-5 h-5 text-slate-500" />
                </button>

            </div>

            {{-- Stepper --}}
            <div class="mt-4 flex items-center gap-2">

                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-xl flex items-center justify-center text-sm font-semibold"
                        :class="step === 1 ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-800'">1</div>
                    <div class="text-sm font-semibold">Personal</div>
                </div>

                <div class="flex-1 h-[2px] bg-slate-200"></div>

                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-xl flex items-center justify-center text-sm font-semibold"
                        :class="step === 2 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600'">2</div>
                    <div class="text-sm font-semibold">Upload</div>
                </div>

                <div class="flex-1 h-[2px] bg-slate-200"></div>

                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-xl flex items-center justify-center text-sm font-semibold"
                        :class="step === 3 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600'">3</div>
                    <div class="text-sm font-semibold">Review</div>
                </div>

            </div>

            {{-- Job Info --}}
            <div class="mt-4 flex flex-wrap gap-2 text-xs">

                <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 border border-slate-200">
                    <x-lucide-icon name="briefcase" class="w-3.5 h-3.5" />
                    {{ $job->title }}
                </span>

                <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 border border-slate-200">
                    <x-lucide-icon name="building-2" class="w-3.5 h-3.5" />
                    {{ $company ?? 'Agency' }}
                </span>

            </div>

        </div>

        <form method="POST" action="{{ route('candidate.jobs.apply', $job->id) }}" enctype="multipart/form-data"
            class="flex-1 flex flex-col min-h-0">
            @csrf

            <div class="flex-1 overflow-y-auto px-5 sm:px-7 py-5 space-y-5">

                {{-- STEP 1 PERSONAL --}}
                <div x-show="step===1" x-transition.opacity>

                    <div class="rounded-2xl border border-slate-200 p-5">

                        <div class="grid sm:grid-cols-2 gap-4">

                            <div>
                                <label class="text-sm font-medium">Full name *</label>
                                <input name="full_name" x-model="full_name"
                                    class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Email *</label>
                                <input name="email" type="email" x-model="email"
                                    class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium">Phone</label>
                                <input name="phone" x-model="phone"
                                    class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500">
                            </div>

                        </div>

                    </div>

                </div>

                {{-- STEP 2 RESUME --}}
                <div x-show="step===2" x-transition.opacity>

                    <div class="rounded-2xl border border-slate-200 p-5">

                        <div class="flex justify-between items-start">

                            <div>
                                <div class="font-semibold text-slate-900">Resume</div>
                                <div class="text-xs text-slate-500">PDF / DOC / DOCX • max 5MB</div>
                            </div>

                            @if ($resume)
                                <div class="text-right">

                                    <div class="text-xs font-semibold text-emerald-700">
                                        Resume on file
                                    </div>

                                    <a href="{{ asset('storage/' . $resume->resume_path) }}" target="_blank"
                                        class="text-blue-600 text-sm hover:underline">
                                        View
                                    </a>

                                    <div class="text-xs text-slate-500">
                                        {{ $resume->original_name ?? 'resume' }}
                                    </div>

                                </div>
                            @else
                                <div class="text-xs text-rose-600 font-semibold">
                                    No resume uploaded
                                </div>
                            @endif

                        </div>

                        <label class="block text-sm mt-4 font-medium">
                            Upload / Replace Resume
                        </label>

                        <input type="file" name="resume" @change="resumeName = $event.target.files?.[0]?.name || ''"
                            class="mt-2 block w-full text-sm rounded-xl border border-slate-300
file:mr-3 file:rounded-lg file:border-0 file:bg-slate-900
file:px-4 file:py-2 file:text-white hover:file:bg-slate-800">

                        <template x-if="resumeName">
                            <p class="text-xs mt-2 text-slate-600">
                                Selected: <span x-text="resumeName"></span>
                            </p>
                        </template>

                    </div>

                </div>

                {{-- STEP 3 REVIEW --}}
                <div x-show="step===3" x-transition.opacity class="space-y-5">

                    {{-- Personal Info --}}
                    <div class="rounded-2xl border border-slate-200 p-5">

                        <div class="font-semibold text-slate-900 mb-3">
                            Personal Information
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4 text-sm">

                            <div>
                                <div class="text-slate-500 text-xs">Name</div>
                                <div class="font-semibold" x-text="full_name"></div>
                            </div>

                            <div>
                                <div class="text-slate-500 text-xs">Email</div>
                                <div class="font-semibold" x-text="email"></div>
                            </div>

                            <div class="sm:col-span-2">
                                <div class="text-slate-500 text-xs">Phone</div>
                                <div class="font-semibold" x-text="phone || '—'"></div>
                            </div>

                        </div>

                    </div>

                    {{-- Resume --}}
                    <div class="rounded-2xl border border-slate-200 p-5">

                        <div class="font-semibold mb-2">Resume</div>

                        @if ($resume)
                            <p class="text-sm text-slate-600">
                                Using uploaded resume:
                                <span class="font-semibold">{{ $resume->original_name }}</span>
                            </p>
                        @else
                            <p class="text-sm text-rose-600">
                                No resume uploaded
                            </p>
                        @endif

                    </div>

                    {{-- Work Experience --}}
                    <div class="rounded-2xl border border-slate-200 p-5">

                        <div class="font-semibold mb-3">Work Experience</div>

                        @if ($resume && $resume->experiences && $resume->experiences->count())

                            @foreach ($resume->experiences->take(5) as $exp)
                                <div class="mb-3 text-sm">

                                    <div class="font-semibold">
                                        {{ $exp->role }} — {{ $exp->company }}
                                    </div>

                                    <div class="text-xs text-slate-500">
                                        {{ $exp->start }} - {{ $exp->end ?? 'Present' }}
                                    </div>

                                    @if ($exp->description)
                                        <div class="text-slate-600 text-sm">
                                            {{ $exp->description }}
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-slate-600">
                                No work experience found in your profile.
                            </p>

                        @endif

                    </div>

                    {{-- Education --}}
                    <div class="rounded-2xl border border-slate-200 p-5">

                        <div class="font-semibold mb-3">Education</div>

                        @if ($resume && $resume->educations && $resume->educations->count())

                            @foreach ($resume->educations->take(5) as $edu)
                                <div class="mb-3 text-sm">

                                    <div class="font-semibold">
                                        {{ $edu->degree }}
                                    </div>

                                    <div class="text-slate-600">
                                        {{ $edu->school }}
                                    </div>

                                    <div class="text-xs text-slate-500">
                                        {{ $edu->year }}
                                    </div>

                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-slate-600">
                                No education found in your profile.
                            </p>

                        @endif

                    </div>

                    {{-- NOTE --}}
                    <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-800">

                        <strong>Note:</strong>
                        All files and information uploaded in your candidate dashboard
                        (such as resume, passport, valid ID, etc..) may be viewed by
                        employers when you submit an application.

                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="px-5 sm:px-7 py-4 border-t border-slate-100 bg-white">

                <div class="flex justify-between">

                    <button type="button" @click="closeModal()"
                        class="px-4 py-2 rounded-xl border border-slate-300 hover:bg-slate-50">
                        Cancel
                    </button>

                    <div class="flex gap-2">

                        <button type="button" x-show="step>1" @click="back()"
                            class="px-4 py-2 rounded-xl border border-slate-300 hover:bg-slate-50">
                            Back
                        </button>

                        <button type="button" x-show="step<3" @click="next()" :disabled="!canGoNext()"
                            class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-semibold">
                            Next
                        </button>

                        <button type="submit" x-show="step===3"
                            class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                            Submit Application
                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>
</div>
