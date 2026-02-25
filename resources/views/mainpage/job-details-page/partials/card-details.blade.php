<div x-data="{
    saveSuccessOpen: {{ session()->has('success') ? 'true' : 'false' }},
    reportOpen: false,
    reportReason: '',
    reportDetails: '',
    applyOpen: false,
    loginApplyOpen: false,
    closeSaveModal() { this.saveSuccessOpen = false },
    openReport() { this.reportOpen = true },
    closeReport() {
        this.reportOpen = false;
        this.reportReason = '';
        this.reportDetails = '';
    },
}" x-init="if (saveSuccessOpen) setTimeout(() => saveSuccessOpen = false, 2500);

@if($errors->any())
applyOpen = true;
@endif

@if($errors->has('resume') || $errors->has('cover_letter_text') || $errors->has('cover_letter_file'))
// open uploading step if upload-related errors
step = 2;
@endif"
    class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
    {{-- Header --}}
    <div class="flex items-start gap-5">
        {{-- Logo --}}
        <div class="shrink-0">
            @if ($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="{{ $company }} logo"
                    class="h-16 w-16 rounded-xl object-cover border border-slate-200 bg-white">
            @else
                <div class="h-16 w-16 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center">
                    <span class="text-lg font-bold text-slate-700">
                        {{ strtoupper(mb_substr($company, 0, 1)) }}
                    </span>
                </div>
            @endif
        </div>

        <div class="min-w-0 flex-1">
            <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900 leading-tight">
                {{ $job->title }}
            </h1>
            <p class="mt-1 text-sm font-semibold text-slate-600 uppercase tracking-wide">
                {{ $company }}
            </p>
            <p class="mt-2 text-sm text-slate-600">
                <span class="font-semibold">Min. Experience:</span>
                {{ $job->min_experience_years !== null ? $job->min_experience_years . ' year(s)' : 'Not specified' }}
            </p>
        </div>
    </div>

    {{-- Key facts --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 border border-slate-200">
                    <i data-lucide="wallet" class="w-4 h-4 text-slate-600"></i>
                </span>
                <div>
                    <div class="font-semibold text-emerald-700">{{ $salaryText }}</div>
                    <div class="text-slate-500">per month</div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 border border-slate-200">
                    <i data-lucide="user" class="w-4 h-4 text-slate-600"></i>
                </span>
                <div class="font-semibold text-slate-700">
                    {{ ucfirst($job->gender ?? 'both') }}
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 border border-slate-200">
                    <i data-lucide="calendar" class="w-4 h-4 text-slate-600"></i>
                </span>
                <div class="font-semibold text-slate-700">
                    Date Posted: {{ $postedDate->format('M d, Y') }}
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 border border-slate-200">
                    <i data-lucide="map-pin" class="w-4 h-4 text-slate-600"></i>
                </span>
                <div class="font-semibold text-slate-700">
                    {{ $locationText ?: 'Not specified' }}
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 border border-slate-200">
                    <i data-lucide="badge-check" class="w-4 h-4 text-slate-600"></i>
                </span>
                <div class="font-semibold text-slate-700">
                    {{ $ageText }} yrs old
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 border border-slate-200">
                    <i data-lucide="clock" class="w-4 h-4 text-slate-600"></i>
                </span>
                <div class="text-slate-700">

                    @if ($applyUntil)
                        <span class="italic text-slate-600">Apply Until:</span>
                        <span class="font-semibold">{{ $applyUntil }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Skills --}}
    @if (!empty($job->skills))
        @php
            // supports: array, json string, or comma-separated string
            $skills = $job->skills;

            if (is_string($skills)) {
                $decoded = json_decode($skills, true);
                $skills = json_last_error() === JSON_ERROR_NONE ? $decoded : explode(',', $skills);
            }

            $skills = collect($skills)->map(fn($s) => trim($s))->filter()->unique()->values();
        @endphp

        @if ($skills->count())
            <div class="mt-6">
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($skills as $skill)
                        <span
                            class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                            {{ $skill }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    {{-- Buttons row --}}
    <div class="mt-6 flex flex-wrap gap-3">
        <button type="button" @click="{{ auth()->check() ? 'applyOpen=true' : 'loginApplyOpen=true' }}"
            class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition">
            Apply Now
        </button>

        {{-- SAVE --}}
        <form action="{{ route('candidate.jobs.save', $job->id) }}" method="POST">
            @csrf
            <button type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-white hover:bg-orange-600 transition">
                {{ $isSaved ? 'Saved' : 'Save Job' }}
            </button>
        </form>
    </div>



    {{-- Content sections --}}
    <div class="mt-10 space-y-8">
        <div>
            <h2 class="text-2xl font-semibold text-slate-700">Job Description</h2>
            @php $lines = lines_to_list($job->job_description); @endphp
            @if ($lines->count() > 1)
                <ul class="mt-4 list-disc pl-6 space-y-2 text-slate-700">
                    @foreach ($lines as $line)
                        <li>{{ ltrim($line, '-• ') }}</li>
                    @endforeach
                </ul>
            @else
                <p class="mt-4 text-slate-700 whitespace-pre-line">{{ $job->job_description }}</p>
            @endif
        </div>

        <div>
            <h2 class="text-2xl font-semibold text-slate-700">Job Qualifications</h2>
            @php $lines = lines_to_list($job->job_qualifications); @endphp
            @if (!empty($job->job_qualifications) && $lines->count() > 1)
                <ul class="mt-4 list-disc pl-6 space-y-2 text-slate-700">
                    @foreach ($lines as $line)
                        <li>{{ ltrim($line, '-• ') }}</li>
                    @endforeach
                </ul>
            @else
                <p class="mt-4 text-slate-700 whitespace-pre-line">{{ $job->job_qualifications ?: 'Not specified.' }}
                </p>
            @endif
        </div>

        <div>
            <h2 class="text-2xl font-semibold text-slate-700">Additional Information</h2>
            @php $lines = lines_to_list($job->additional_information); @endphp
            @if (!empty($job->additional_information) && $lines->count() > 1)
                <ul class="mt-4 list-disc pl-6 space-y-2 text-slate-700">
                    @foreach ($lines as $line)
                        <li>{{ ltrim($line, '-• ') }}</li>
                    @endforeach
                </ul>
            @else
                <p class="mt-4 text-slate-700 whitespace-pre-line">
                    {{ $job->additional_information ?: 'Not specified.' }}
                </p>
            @endif
        </div>
    </div>

    {{-- Principal / DMW / Fee --}}
    <div class="mt-10 rounded-2xl border border-slate-200 bg-white">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-6 border-b md:border-b-0 md:border-r border-slate-200">
                <p class="text-sm font-semibold text-slate-700">Principal / Employer</p>
                <p class="mt-2 text-sm text-slate-700">{{ $job->principal_employer ?: 'Not specified' }}</p>

                <p class="mt-6 text-sm font-semibold text-slate-700">DMW (formerly POEA) Registration / Accreditation
                    No.</p>
                <p class="mt-2 text-sm text-slate-700">{{ $job->dmw_registration_no ?: 'Not specified' }}</p>
            </div>

            <div class="p-6">
                <p class="text-sm font-semibold text-slate-700">Principal / Employer Address</p>
                <p class="mt-2 text-sm text-slate-700">{{ $job->principal_employer_address ?: 'Not specified' }}</p>

                <p class="mt-6 text-sm font-semibold text-slate-700">Placement Fee</p>
                <p class="mt-2 text-sm text-blue-600 font-semibold">{{ $placementFeeText }}</p>
            </div>
        </div>
    </div>

    {{-- Report bar --}}
    <div class="mt-6 rounded-xl bg-slate-100 border border-slate-200 px-4 py-3 text-center text-sm text-slate-600">
        Is this ad misleading?
        <button type="button" @click="openReport()" class="font-semibold text-slate-900 hover:underline">
            Report this job
        </button>
    </div>

    {{-- Agency Details --}}
    <div class="mt-10" id="apply">
        <h2 class="text-2xl font-semibold text-slate-700">Agency Details</h2>

        <div class="mt-5 space-y-3 text-sm text-slate-700">
            <div class="flex items-start gap-3">
                <i data-lucide="map-pin" class="w-5 h-5 text-slate-500 mt-0.5"></i>
                <div>
                    <p class="font-semibold">Address</p>
                    <p class="text-slate-600">{{ $ep->company_address ?? 'Not specified' }}</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <i data-lucide="globe" class="w-5 h-5 text-slate-500 mt-0.5"></i>
                <div>
                    <p class="font-semibold">Website</p>
                    @if (!empty($ep->company_website))
                        <a class="text-blue-600 hover:underline" href="{{ $ep->company_website }}" target="_blank">
                            {{ $ep->company_website }}
                        </a>
                    @else
                        <p class="text-slate-600">Not specified</p>
                    @endif
                </div>
            </div>

            <div class="flex items-start gap-3">
                <i data-lucide="phone" class="w-5 h-5 text-slate-500 mt-0.5"></i>
                <div>
                    <p class="font-semibold">Contact</p>
                    <p class="text-slate-600">{{ $ep->company_contact ?? 'Not specified' }}</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <i data-lucide="file-text" class="w-5 h-5 text-slate-500 mt-0.5"></i>
                <div>
                    <p class="font-semibold">About</p>
                    <p class="text-slate-600 whitespace-pre-line">{{ $ep->description ?? 'No description provided.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ SAVE SUCCESS MODAL --}}
    <div x-cloak x-show="saveSuccessOpen" x-transition.opacity
        class="fixed inset-0 z-[9999] flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/40" @click="closeSaveModal()"></div>

        <div class="relative w-full max-w-sm rounded-2xl bg-white p-5 shadow-xl border border-slate-200">
            <div class="flex items-start gap-3">
                <div
                    class="h-10 w-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center">
                    <i data-lucide="check" class="w-5 h-5 text-emerald-700"></i>
                </div>
                <div class="flex-1">
                    <div class="text-sm font-semibold text-slate-900">Success</div>
                    <div class="mt-1 text-sm text-slate-600">
                        {{ session('success') }}
                    </div>
                </div>
                <button type="button" @click="closeSaveModal()" class="rounded-lg p-2 hover:bg-slate-100">
                    <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ✅ REPORT MODAL --}}
    <div x-cloak x-show="reportOpen" x-transition.opacity
        class="fixed inset-0 z-[9999] flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/40" @click="closeReport()"></div>

        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl border border-slate-200">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="text-base font-semibold text-slate-900">Report this job</div>
                    <div class="mt-1 text-sm text-slate-600">Tell us what’s wrong so we can review it.</div>
                </div>
                <button type="button" @click="closeReport()" class="rounded-lg p-2 hover:bg-slate-100">
                    <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>
                </button>
            </div>

            <form class="mt-5 space-y-4" method="POST"
                action="{{ route('candidate.jobs.report.store', $job->id) }}">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700">Reason <span
                            class="text-red-500">*</span></label>
                    <select name="reason" x-model="reportReason"
                        class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500">
                        <option value="">Select a reason</option>
                        <option value="misleading">Misleading information</option>
                        <option value="scam">Possible scam</option>
                        <option value="fake">Fake job posting</option>
                        <option value="wrong_contact">Wrong contact/details</option>
                        <option value="other">Other</option>
                    </select>
                    @error('reason')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Details (optional)</label>
                    <textarea name="details" rows="4" x-model="reportDetails"
                        class="mt-1 w-full rounded-xl border-slate-300 focus:ring-emerald-200 focus:border-emerald-500"
                        placeholder="Add more details..."></textarea>
                    @error('details')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="closeReport()"
                        class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>

                    <button type="submit" :disabled="!reportReason"
                        class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('mainpage.job-details-page.partials.apply-now-modal')

</div>
