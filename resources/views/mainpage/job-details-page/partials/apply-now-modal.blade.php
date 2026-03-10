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

    {{-- Modal --}}
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

            <div class="flex items-start justify-between gap-4">

                <div>
                    <h3 class="text-lg sm:text-xl font-semibold text-slate-900">
                        Apply Now
                    </h3>

                    <p class="mt-1 text-sm text-slate-600">
                        Step <span x-text="step"></span> of 3
                    </p>
                </div>

                <button type="button" @click="closeModal()" class="rounded-xl p-2 hover:bg-slate-100">

                    <i data-lucide="x" class="w-5 h-5 text-slate-500"></i>

                </button>

            </div>

        </div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('candidate.jobs.apply', $job->id) }}" enctype="multipart/form-data"
            class="flex-1 flex flex-col min-h-0">

            @csrf

            {{-- BODY --}}
            <div class="flex-1 overflow-y-auto px-5 sm:px-7 py-5 space-y-5">

                {{-- STEP 1 --}}
                <div x-show="step===1" x-transition.opacity>

                    <div class="space-y-4">

                        <div>
                            <label class="text-sm font-medium text-slate-700">
                                Full Name *
                            </label>

                            <input name="full_name" x-model="full_name"
                                class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500">

                            @error('full_name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror

                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">
                                Email *
                            </label>

                            <input name="email" type="email" x-model="email"
                                class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500">

                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror

                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">
                                Phone
                            </label>

                            <input name="phone" x-model="phone"
                                class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500">

                        </div>

                    </div>

                </div>

                {{-- STEP 2 --}}
                <div x-show="step===2" x-transition.opacity>

                    <label class="text-sm font-medium text-slate-700">
                        Resume *
                    </label>

                    <input type="file" name="resume" @change="resumeName = $event.target.files?.[0]?.name || ''"
                        class="mt-2 block w-full text-sm rounded-xl border border-slate-300
                file:mr-3 file:rounded-lg file:border-0 file:bg-slate-900
                file:px-4 file:py-2 file:text-white hover:file:bg-slate-800">

                    <template x-if="resumeName">
                        <p class="mt-2 text-xs text-slate-600">
                            Selected:
                            <span class="font-semibold" x-text="resumeName"></span>
                        </p>
                    </template>

                    @error('resume')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror

                </div>

                {{-- STEP 3 --}}
                <div x-show="step===3" x-transition.opacity>

                    <div class="space-y-3 text-sm">

                        <p>
                            <strong>Name:</strong>
                            <span x-text="full_name"></span>
                        </p>

                        <p>
                            <strong>Email:</strong>
                            <span x-text="email"></span>
                        </p>

                        <p>
                            <strong>Phone:</strong>
                            <span x-text="phone || '—'"></span>
                        </p>

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
                            class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-semibold disabled:opacity-50">

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


