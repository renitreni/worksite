<div x-data="{
    modal: null,

    openModal(name) {
        this.modal = name
    },

    closeModal() {
        this.modal = null
    },

    reportReason: '',
    reportDetails: '',

    closeReport() {
        this.reportReason = ''
        this.reportDetails = ''
        this.closeModal()
    }
}" x-init="@if ($errors->any()) modal = 'apply' @endif"
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
        </div>

    </div>

    {{-- HOLD WARNING --}}
    @if ($job->is_held)
        <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 shadow-sm">

            <div class="flex items-start gap-3">

                {{-- Icon --}}
                <div class="flex-shrink-0">
                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 border border-amber-200">
                        <x-lucide-icon name="alert-triangle" class="w-5 h-5 text-amber-600" />
                    </span>
                </div>

                {{-- Text --}}
                <div class="flex-1 text-sm text-amber-900">

                    <p class="font-semibold">
                        Administrative Review Notice
                    </p>

                    <p class="mt-1 text-amber-800">
                        This job posting is currently under administrative review by the JobAbroad team.
                        The listing remains visible to candidates while it is being reviewed.
                    </p>

                </div>

            </div>

        </div>
    @endif

    {{-- Key facts --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 border border-slate-200">
                    <x-lucide-icon name="wallet" class="w-4 h-4 text-slate-600" />
                </span>
                <div>
                    <div class="font-semibold text-emerald-700">{{ $salaryText }}</div>
                    <div class="text-slate-500">per month</div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 border border-slate-200">
                    <x-lucide-icon name="user" class="w-4 h-4 text-slate-600" />
                </span>
                <div class="font-semibold text-slate-700">
                    @if ($job->gender === 'both')
                        Male & Female
                    @elseif($job->gender === 'male')
                        Male
                    @elseif($job->gender === 'female')
                        Female
                    @else
                        Not specified
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 border border-slate-200">
                    <x-lucide-icon name="calendar" class="w-4 h-4 text-slate-600" />
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
                    <x-lucide-icon name="map-pin" class="w-4 h-4 text-slate-600" />
                </span>
                <div class="font-semibold text-slate-700">
                    {{ $locationText ?: 'Not specified' }}
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 border border-slate-200">
                    <x-lucide-icon name="badge-check" class="w-4 h-4 text-slate-600" />
                </span>
                <div class="font-semibold text-slate-700">
                    {{ $ageText }} yrs old
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 border border-slate-200">
                    <x-lucide-icon name="clock" class="w-4 h-4 text-slate-600" />
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
    <div class="mt-6 flex flex-wrap items-center gap-3">

        @if ($alreadyApplied)
            <button type="button"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-6 py-3 text-sm font-semibold text-emerald-700">

                <x-lucide-icon name="check-circle" class="w-4 h-4" />
                Applied

            </button>
        @else
            @auth
                <button type="button" @click="openModal('apply')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition shadow-sm">

                    <x-lucide-icon name="send" class="w-4 h-4" />
                    Apply Now

                </button>
            @else
                <button type="button" @click="openModal('login')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition shadow-sm">

                    <x-lucide-icon name="send" class="w-4 h-4" />
                    Apply Now

                </button>
            @endauth
        @endif


        {{-- SAVE --}}
        @auth
            <div x-data="{ saved: {{ $isSaved ? 'true' : 'false' }} }">

                <button
                    @click.prevent="
                fetch('{{ route('candidate.jobs.save', $job->id) }}',{
                    method:'POST',
                    headers:{
                        'X-CSRF-TOKEN':'{{ csrf_token() }}',
                        'X-Requested-With':'XMLHttpRequest',
                        'Accept':'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        saved = data.saved
                    }
                })
                "
                    class="inline-flex items-center justify-center gap-2 rounded-xl border px-6 py-3 text-sm font-semibold transition"
                    :class="saved
                        ?
                        'border-emerald-300 bg-emerald-50 text-emerald-700' :
                        'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'">

                    {{-- ICON --}}
                    <x-lucide-icon name="bookmark" class="w-4 h-4" :class="saved ? 'fill-emerald-600 text-emerald-600' : ''" />

                    <span x-text="saved ? 'Saved' : 'Save Job'"></span>

                </button>

            </div>
        @endauth

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
        @auth
            <button type="button" @click="openModal('report')" class="font-semibold text-slate-900 hover:underline">

                Report this job

            </button>
        @else
            <button type="button" @click="openModal('login')" class="font-semibold text-slate-900 hover:underline">

                Report this job

            </button>
        @endauth
    </div>

    {{-- Agency Details --}}
    <div class="mt-10" id="apply">

        <div class="flex items-center justify-between flex-wrap gap-3">
            <h2 class="text-2xl font-semibold text-slate-700">
                Agency Details
            </h2>

            <a href="{{ route('agency.details', $job->employerProfile->id) }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700">

                View Agency
                <x-lucide-icon name="arrow-right" class="w-4 h-4" />

            </a>
        </div>

        <div class="mt-5 space-y-4 text-sm text-slate-700">

            {{-- Address --}}
            <div class="flex items-start gap-3">
                <x-lucide-icon name="map-pin" class="w-5 h-5 text-slate-500 mt-0.5" />
                <div>
                    <p class="font-semibold">Address</p>
                    <p class="text-slate-600">
                        {{ $ep->company_address ?? 'Not specified' }}
                    </p>
                </div>
            </div>

            {{-- Website --}}
            <div class="flex items-start gap-3">
                <x-lucide-icon name="globe" class="w-5 h-5 text-slate-500 mt-0.5" />
                <div>
                    <p class="font-semibold">Website</p>

                    @if (!empty($ep->company_website))
                        <a class="text-blue-600 hover:underline break-all" href="{{ $ep->company_website }}"
                            target="_blank">
                            {{ $ep->company_website }}
                        </a>
                    @else
                        <p class="text-slate-600">Not specified</p>
                    @endif

                </div>
            </div>

            {{-- Contact --}}
            <div class="flex items-start gap-3">
                <x-lucide-icon name="phone" class="w-5 h-5 text-slate-500 mt-0.5" />
                <div>
                    <p class="font-semibold">Contact</p>
                    <p class="text-slate-600">
                        {{ $ep->company_contact ?? 'Not specified' }}
                    </p>
                </div>
            </div>

            {{-- Description --}}
            <div class="flex items-start gap-3">
                <x-lucide-icon name="file-text" class="w-5 h-5 text-slate-500 mt-0.5" />
                <div>
                    <p class="font-semibold">About Agency</p>
                    <p class="text-slate-600 whitespace-pre-line">
                        {{ $ep->description ?? 'No description provided.' }}
                    </p>
                </div>
            </div>

        </div>

    </div>


    @include('mainpage.job-details-page.partials.modals')
    @include('mainpage.job-details-page.partials.apply-now-modal')

</div>
