@php
  $u = auth()->user();
  $resume = $u?->candidateResume; // should be eager-loaded with experiences/educations

  $fullNameDefault = $u ? (trim(($u->first_name ?? '').' '.($u->last_name ?? '')) ?: ($u->name ?? '')) : '';
  $emailDefault = $u?->email ?? '';
  $phoneDefault = $u?->phone ?? '';
@endphp

<div x-cloak x-show="applyOpen" x-transition.opacity
     class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-6">
  <div class="absolute inset-0 bg-black/50" @click="applyOpen=false"></div>

  <div
    x-data="{
      step: 1,

      full_name: @js(old('full_name', $fullNameDefault)),
      email: @js(old('email', $emailDefault)),
      phone: @js(old('phone', $phoneDefault)),

      resumeName: '',
      coverFileName: '',

      next() {
        if (this.step === 1) {
          if (!this.full_name || !this.email) return;
        }
        this.step = Math.min(3, this.step + 1);
      },
      back() { this.step = Math.max(1, this.step - 1); },

      canGoNext() {
        if (this.step === 1) return !!this.full_name && !!this.email;
        return true;
      }
    }"
    class="relative w-full max-w-2xl rounded-3xl bg-white shadow-2xl ring-1 ring-black/5 overflow-hidden
           max-h-[92vh] flex flex-col"
    x-trap.noscroll="applyOpen"
  >

    {{-- HEADER (fixed) --}}
    <div class="px-5 sm:px-7 py-5 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-white shrink-0">
      <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
          <h3 class="text-lg sm:text-xl font-semibold text-slate-900">Apply Now</h3>
          <p class="mt-1 text-sm text-slate-600">
            Step <span x-text="step"></span> of 3 • Review before submitting.
          </p>
        </div>

        <button type="button" @click="applyOpen=false" class="shrink-0 rounded-xl p-2 hover:bg-white/70 transition">
          <i data-lucide="x" class="w-5 h-5 text-slate-500"></i>
        </button>
      </div>

      {{-- stepper --}}
      <div class="mt-4 flex items-center gap-2">
        <div class="flex items-center gap-2">
          <div class="h-8 w-8 rounded-xl flex items-center justify-center text-sm font-semibold"
               :class="step===1 ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-800'">1</div>
          <div class="text-sm font-semibold" :class="step===1 ? 'text-slate-900' : 'text-slate-600'">Personal</div>
        </div>

        <div class="flex-1 h-[2px] rounded bg-slate-200"></div>

        <div class="flex items-center gap-2">
          <div class="h-8 w-8 rounded-xl flex items-center justify-center text-sm font-semibold"
               :class="step===2 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600'">2</div>
          <div class="text-sm font-semibold" :class="step===2 ? 'text-slate-900' : 'text-slate-600'">Uploading</div>
        </div>

        <div class="flex-1 h-[2px] rounded bg-slate-200"></div>

        <div class="flex items-center gap-2">
          <div class="h-8 w-8 rounded-xl flex items-center justify-center text-sm font-semibold"
               :class="step===3 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600'">3</div>
          <div class="text-sm font-semibold" :class="step===3 ? 'text-slate-900' : 'text-slate-600'">Review</div>
        </div>
      </div>

      {{-- job chips --}}
      <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
        <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 border border-slate-200 text-slate-700">
          <i data-lucide="briefcase" class="w-3.5 h-3.5"></i> {{ $job->title }}
        </span>
        <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 border border-slate-200 text-slate-700">
          <i data-lucide="building-2" class="w-3.5 h-3.5"></i> {{ $company ?? 'Agency' }}
        </span>
      </div>
    </div>

    <form method="POST" action="{{ route('candidate.jobs.apply', $job->id) }}" enctype="multipart/form-data"
          class="flex-1 flex flex-col min-h-0">
      @csrf

      {{-- BODY (scrollable only) --}}
      <div class="flex-1 min-h-0 overflow-y-auto px-5 sm:px-7 py-5 space-y-5">

        {{-- STEP 1: PERSONAL --}}
        <div x-show="step===1" x-transition.opacity class="space-y-5">
          <div class="rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center gap-2">
              <div class="h-9 w-9 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center">
                <i data-lucide="user" class="w-4 h-4 text-emerald-700"></i>
              </div>
              <div>
                <div class="text-sm font-semibold text-slate-900">Personal information</div>
                <div class="text-xs text-slate-600">Preloaded but editable.</div>
              </div>
            </div>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700">Full name <span class="text-red-500">*</span></label>
                <input name="full_name" type="text" x-model="full_name"
                       class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500">
                @error('full_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700">Email <span class="text-red-500">*</span></label>
                <input name="email" type="email" x-model="email"
                       class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500">
                @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              </div>

              <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Phone</label>
                <input name="phone" type="text" x-model="phone"
                       class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500">
                @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>
        </div>

        {{-- STEP 2: UPLOADING --}}
        <div x-show="step===2" x-transition.opacity class="space-y-5">
          {{-- Resume --}}
          <div class="rounded-2xl border border-slate-200 p-5">
            <div class="flex items-start justify-between gap-3">
              <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center">
                  <i data-lucide="file-text" class="w-4 h-4 text-slate-700"></i>
                </div>
                <div>
                  <div class="text-sm font-semibold text-slate-900">Resume</div>
                  <div class="text-xs text-slate-600">PDF/DOC/DOCX • max 5MB</div>
                </div>
              </div>

              @if($resume)
                <div class="text-right">
                  <div class="text-xs font-semibold text-emerald-700">Resume on file</div>
                  <a href="{{ asset('storage/'.$resume->resume_path) }}" target="_blank"
                     class="inline-flex items-center gap-1 text-sm text-blue-600 hover:underline">
                    <i data-lucide="external-link" class="w-4 h-4"></i> View
                  </a>
                  <div class="text-[11px] text-slate-500 mt-1">
                    {{ $resume->original_name ?? 'resume' }}
                  </div>
                </div>
              @else
                <div class="text-xs font-semibold text-rose-700">No resume uploaded yet</div>
              @endif
            </div>

            <div class="mt-4">
              <label class="block text-sm font-medium text-slate-700">
                Upload / Replace resume @if(!$resume) <span class="text-red-500">*</span> @endif
              </label>

              <input type="file" name="resume"
                     @change="resumeName = $event.target.files?.[0]?.name || ''"
                     class="mt-2 block w-full text-sm rounded-xl border border-slate-300
                            file:mr-3 file:rounded-lg file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-white hover:file:bg-slate-800">

              <template x-if="resumeName">
                <p class="mt-2 text-xs text-slate-600">
                  Selected: <span class="font-semibold" x-text="resumeName"></span>
                </p>
              </template>

              @error('resume') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
          </div>

          {{-- Cover letter --}}
          <div class="rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center gap-2">
              <div class="h-9 w-9 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center">
                <i data-lucide="message-square" class="w-4 h-4 text-slate-700"></i>
              </div>
              <div>
                <div class="text-sm font-semibold text-slate-900">Cover Letter (optional)</div>
                <div class="text-xs text-slate-600">Write a message or upload a file.</div>
              </div>
            </div>

            <div class="mt-4 space-y-4">
              <div>
                <label class="block text-sm font-medium text-slate-700">Message</label>
                <textarea name="cover_letter_text" rows="4"
                          class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500"
                          placeholder="Introduce yourself briefly...">{{ old('cover_letter_text') }}</textarea>
                @error('cover_letter_text') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700">Upload cover letter file</label>
                <input type="file" name="cover_letter_file"
                       @change="coverFileName = $event.target.files?.[0]?.name || ''"
                       class="mt-2 block w-full text-sm rounded-xl border border-slate-300
                              file:mr-3 file:rounded-lg file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-white hover:file:bg-slate-800">

                <template x-if="coverFileName">
                  <p class="mt-2 text-xs text-slate-600">
                    Selected: <span class="font-semibold" x-text="coverFileName"></span>
                  </p>
                </template>

                @error('cover_letter_file') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                <p class="mt-2 text-xs text-slate-500">PDF/DOC/DOCX • max 5MB</p>
              </div>
            </div>
          </div>
        </div>

        {{-- STEP 3: REVIEW --}}
        <div x-show="step===3" x-transition.opacity class="space-y-5">
          <div class="rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center gap-2">
              <div class="h-9 w-9 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-700"></i>
              </div>
              <div>
                <div class="text-sm font-semibold text-slate-900">Review your application</div>
                <div class="text-xs text-slate-600">Confirm details and files before submitting.</div>
              </div>
            </div>

            {{-- Entered data --}}
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
              <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                <div class="text-xs text-slate-500">Full name</div>
                <div class="font-semibold text-slate-900" x-text="full_name"></div>
              </div>

              <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                <div class="text-xs text-slate-500">Email</div>
                <div class="font-semibold text-slate-900" x-text="email"></div>
              </div>

              <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 sm:col-span-2">
                <div class="text-xs text-slate-500">Phone</div>
                <div class="font-semibold text-slate-900" x-text="phone || '—'"></div>
              </div>
            </div>

            {{-- Selected files --}}
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
              <div class="rounded-xl border border-slate-200 p-4">
                <div class="flex items-center justify-between gap-3">
                  <div class="font-semibold text-slate-900">Resume</div>
                  <span class="text-xs px-2 py-1 rounded-full"
                        :class="resumeName ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-50 text-slate-600 border border-slate-200'">
                    <span x-text="resumeName ? 'Selected' : '{{ $resume ? 'On file' : 'Required' }}'"></span>
                  </span>
                </div>

                <div class="mt-2 text-xs text-slate-600">
                  <template x-if="resumeName">
                    <span>File: <span class="font-semibold" x-text="resumeName"></span></span>
                  </template>

                  <template x-if="!resumeName">
                    <span>
                      @if($resume)
                        Using uploaded resume: <span class="font-semibold">{{ $resume->original_name ?? 'resume' }}</span>
                      @else
                        Please go back and upload your resume.
                      @endif
                    </span>
                  </template>
                </div>
              </div>

              <div class="rounded-xl border border-slate-200 p-4">
                <div class="flex items-center justify-between gap-3">
                  <div class="font-semibold text-slate-900">Cover letter file</div>
                  <span class="text-xs px-2 py-1 rounded-full"
                        :class="coverFileName ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-50 text-slate-600 border border-slate-200'">
                    <span x-text="coverFileName ? 'Selected' : 'Optional'"></span>
                  </span>
                </div>

                <div class="mt-2 text-xs text-slate-600">
                  <template x-if="coverFileName">
                    <span>File: <span class="font-semibold" x-text="coverFileName"></span></span>
                  </template>
                  <template x-if="!coverFileName">
                    <span>No file selected</span>
                  </template>
                </div>
              </div>
            </div>
          </div>

          {{-- Experience/Education (if available on resume) --}}
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="rounded-2xl border border-slate-200 p-5">
              <div class="flex items-center gap-2">
                <i data-lucide="briefcase" class="w-4 h-4 text-slate-700"></i>
                <div class="text-sm font-semibold text-slate-900">Work experience</div>
              </div>

              <div class="mt-3 space-y-3 text-sm">
                @if($resume && $resume->experiences && $resume->experiences->count())
                  @foreach($resume->experiences->take(5) as $exp)
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                      <div class="font-semibold text-slate-900">{{ $exp->role }} @if($exp->company) — {{ $exp->company }} @endif</div>
                      <div class="text-xs text-slate-500 mt-0.5">
                        {{ $exp->start ?? '—' }} - {{ $exp->end ?? 'Present' }}
                      </div>
                      @if($exp->description)
                        <div class="text-sm text-slate-700 mt-2 whitespace-pre-line">{{ $exp->description }}</div>
                      @endif
                    </div>
                  @endforeach
                  @if($resume->experiences->count() > 5)
                    <div class="text-xs text-slate-500">Showing 5 of {{ $resume->experiences->count() }} experiences.</div>
                  @endif
                @else
                  <div class="text-sm text-slate-600">No experience found in your resume profile.</div>
                @endif
              </div>
            </div>

            <div class="rounded-2xl border border-slate-200 p-5">
              <div class="flex items-center gap-2">
                <i data-lucide="graduation-cap" class="w-4 h-4 text-slate-700"></i>
                <div class="text-sm font-semibold text-slate-900">Education</div>
              </div>

              <div class="mt-3 space-y-3 text-sm">
                @if($resume && $resume->educations && $resume->educations->count())
                  @foreach($resume->educations->take(5) as $edu)
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                      <div class="font-semibold text-slate-900">{{ $edu->degree ?? '—' }}</div>
                      <div class="text-sm text-slate-700">{{ $edu->school ?? '—' }}</div>
                      <div class="text-xs text-slate-500 mt-0.5">{{ $edu->year ?? '' }}</div>
                      @if($edu->notes)
                        <div class="text-sm text-slate-700 mt-2 whitespace-pre-line">{{ $edu->notes }}</div>
                      @endif
                    </div>
                  @endforeach
                  @if($resume->educations->count() > 5)
                    <div class="text-xs text-slate-500">Showing 5 of {{ $resume->educations->count() }} education entries.</div>
                  @endif
                @else
                  <div class="text-sm text-slate-600">No education found in your resume profile.</div>
                @endif
              </div>
            </div>
          </div>

          @error('apply') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

      </div>

      {{-- FOOTER (sticky) --}}
      <div class="px-5 sm:px-7 py-4 border-t border-slate-100 bg-white shrink-0">
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-2">
          <div class="text-xs text-slate-500">
            <span x-show="step===1">Next: upload your resume</span>
            <span x-show="step===2">Next: review your details</span>
            <span x-show="step===3">Ready to submit</span>
          </div>

          <div class="flex flex-col-reverse sm:flex-row gap-2 sm:justify-end">
            <button type="button" @click="applyOpen=false"
                    class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
              Cancel
            </button>

            <button type="button" x-show="step>1" @click="back()"
                    class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
              Back
            </button>

            <button type="button" x-show="step<3" @click="next()"
                    :disabled="!canGoNext()"
                    class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed">
              Next
            </button>

            <button type="submit" x-show="step===3"
                    class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 shadow-sm">
              Submit Application
            </button>
          </div>
        </div>
      </div>

    </form>
  </div>
</div>