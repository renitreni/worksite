@extends('employer.layout')

@section('title', 'Applicant Details')
@section('page_title', 'Applicant Details')

@section('content')
    @php
        $candidate = $application->candidateProfile;
        $user = $candidate?->user;

        $photoUrl = $candidate?->photo_path ? asset('storage/' . ltrim($candidate->photo_path, '/')) : null;

        $cityProv = $candidate?->address;

        $levelLabel = match ($access['level'] ?? 'default') {
            'default' => 'Basic',
            'basic_preview' => 'Standard',
            'expanded' => 'Gold',
            'full' => 'Platinum',
            default => ucfirst($access['level']),
        };
    @endphp

    <div class="w-full max-w-7xl mx-auto space-y-6">

        {{-- Top header --}}
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    @php
                        // Normalize status (para consistent)
                        $rawStatus = strtolower(trim($application->status ?? 'applied'));

                        $status = match ($rawStatus) {
                            'new', 'pending' => 'applied',
                            default => $rawStatus,
                        };

                        $steps = [
                            'applied' => 'Applied',
                            'shortlisted' => 'Shortlisted',
                            'interview' => 'Interview',
                            'hired' => 'Hired',
                        ];

                        $order = array_keys($steps);
                        $currentIndex = array_search($status, $order, true);
                        $currentIndex = $currentIndex === false ? 0 : $currentIndex;

                        $currentLabel = $steps[$order[$currentIndex]] ?? 'Applied';
                        $nextKey = $order[$currentIndex + 1] ?? null;
                        $nextLabel = $nextKey ? $steps[$nextKey] ?? null : null;

                        $isFinal = $status === 'hired';
                        $isRejected = $status === 'rejected';

                        $levelLabel = ucfirst($access['level'] ?? 'basic');
                    @endphp

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Applicant Details</h1>
                        <p class="mt-1 text-sm text-slate-600">
                            Applied for:
                            <span class="font-semibold text-slate-900">{{ $application->jobPost?->title ?? 'N/A' }}</span>
                            <span class="mx-2 text-slate-300">•</span>
                            Current Status:
                            <span class="font-semibold text-slate-900">
                                {{ $isRejected ? 'Rejected' : ucfirst($status) }}
                            </span>
                        </p>

                        {{-- Stepper --}}
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            @foreach ($order as $i => $key)
                                @php
                                    $done = $i < $currentIndex && !$isRejected;
                                    $active = $i === $currentIndex && !$isRejected;
                                    $locked = $i > $currentIndex || $isRejected;
                                @endphp

                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold
                                {{ $done ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $active ? 'bg-emerald-600 text-white' : '' }}
                                {{ $locked ? 'bg-slate-100 text-slate-500' : '' }}
                            ">
                                        <span
                                            class="h-2 w-2 rounded-full
                                    {{ $done ? 'bg-emerald-600' : '' }}
                                    {{ $active ? 'bg-white' : '' }}
                                    {{ $locked ? 'bg-slate-400' : '' }}
                                "></span>
                                        {{ $steps[$key] }}
                                    </div>

                                    @if (!$loop->last)
                                        <span class="text-slate-300">→</span>
                                    @endif
                                </div>
                            @endforeach

                            @if ($isRejected)
                                <span class="ml-2 rounded-full bg-rose-600 px-3 py-1 text-xs font-semibold text-white">
                                    Rejected
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <a href="{{ route('employer.applicants.index') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                            Back
                        </a>

                        @if ($access['level'] !== 'platinum')
                            <a href="{{ route('employer.subscription.dashboard') }}"
                                class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                Upgrade to Unlock More
                            </a>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Actions (Step-by-step) --}}
            <div class="px-6 sm:px-8 py-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-sm text-slate-600">
                        @if ($isRejected)
                            This applicant is already <span class="font-semibold text-rose-600">Rejected</span>.
                        @elseif ($isFinal)
                            This applicant is already <span class="font-semibold text-emerald-700">Hired</span>.
                        @elseif ($nextKey)
                            Next step: <span class="font-semibold text-slate-900">{{ $nextLabel }}</span>
                        @else
                            No further steps.
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2">

                        {{-- Move to next step (only one main button) --}}
                        @if (!$isRejected && !$isFinal && $nextKey)
                            @php
                                $routeName = match ($nextKey) {
                                    'shortlisted' => 'employer.applicants.shortlist',
                                    'interview' => 'employer.applicants.interview',
                                    'hired' => 'employer.applicants.hire',
                                    default => null,
                                };
                                $btnText = "Move to {$nextLabel}";
                            @endphp

                            @if ($routeName)
                                <form method="POST" action="{{ route($routeName, $application->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <button
                                        class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                        {{ $btnText }}
                                    </button>
                                </form>
                            @endif
                        @endif

                        {{-- Reject is always visible (but disabled if already rejected/hired) --}}
                        <form method="POST" action="{{ route('employer.applicants.reject', $application->id) }}">
                            @csrf
                            @method('PUT')
                            <button
                                class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                @disabled($isRejected || $isFinal)>
                                Reject
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        {{-- @if (!$canViewToday)
            <div class="rounded-3xl border border-rose-200 bg-rose-50 shadow-sm p-6 text-sm text-rose-800">
                <div class="font-semibold">
                    Daily Candidate View Limit Reached
                </div>
                <div class="mt-1">
                    You've reached your daily candidate profile view limit.
                    @if (!is_null($dailyLimit))
                        ({{ $usedToday }}/{{ $dailyLimit }} used today)
                    @endif
                </div>

                <div class="mt-4">
                    <a href="{{ route('employer.subscription.dashboard') }}"
                        class="inline-flex items-center rounded-xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                        Upgrade Plan
                    </a>
                </div>
            </div>
        @endif --}}

        {{-- Main grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: Candidate Card --}}
            <div class="lg:col-span-2 space-y-6">

                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-base font-semibold text-slate-900">Candidate Profile</h2>
                        <p class="mt-1 text-sm text-slate-600">
                            Information visibility depends on your subscription level.
                        </p>
                    </div>

                    <div class="px-6 py-6 space-y-6">

                        {{-- Top identity row --}}
                        <div class="flex items-start gap-4">
                            @if ($access['can_view_profile_picture'])
                                <div
                                    class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-100 ring-1 ring-slate-200 shrink-0">
                                    @if ($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="Profile Photo"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">
                                            No Photo</div>
                                    @endif
                                </div>
                            @else
                                <div
                                    class="w-16 h-16 rounded-2xl bg-slate-100 ring-1 ring-slate-200 flex items-center justify-center text-slate-400 text-xs shrink-0">
                                    Locked
                                </div>
                            @endif

                            <div class="flex-1">
                                <div class="text-lg font-semibold text-slate-900">
                                    {{-- Basic: can show name only --}}
                                    @if ($access['can_view_full_name'])
                                        {{ $user?->name ?? 'Unknown Candidate' }}
                                    @else
                                        Candidate
                                    @endif
                                </div>

                                <div class="mt-1 text-sm text-slate-600 flex flex-wrap gap-x-4 gap-y-1">
                                    {{-- Years exp --}}
                                    @if ($access['can_view_years_experience'])
                                        <span><span class="text-slate-500">Experience:</span>
                                            <span
                                                class="font-semibold text-slate-900">{{ $candidate?->experience_years ?? '—' }}</span>
                                            year(s)
                                        </span>
                                    @endif

                                    {{-- Address city/province only --}}
                                    @if ($access['can_view_address_city_province_only'])
                                        <span><span class="text-slate-500">Address:</span>
                                            <span class="font-semibold text-slate-900">{{ $cityProv ?? '—' }}</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Info grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Birthdate --}}
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <div class="text-xs font-semibold text-slate-500">Birthdate</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900">
                                    @if ($access['can_view_birthdate'])
                                        {{ optional($candidate?->birth_date)->format('M d, Y') ?? '—' }}
                                    @else
                                        <span class="text-slate-400">Locked</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Highest Education --}}
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <div class="text-xs font-semibold text-slate-500">Highest Education</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900">
                                    @if ($access['can_view_highest_education'])
                                        {{ $candidate?->highest_qualification ?? '—' }}
                                    @else
                                        <span class="text-slate-400">Locked</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Short Bio --}}
                            <div class="sm:col-span-2 rounded-2xl border border-slate-200 p-4">
                                <div class="text-xs font-semibold text-slate-500">Professional Summary</div>
                                <div class="mt-2 text-sm text-slate-700 leading-relaxed">
                                    @if ($access['can_view_short_bio'])
                                        {{ $candidate?->bio ?: '—' }}
                                    @else
                                        <span class="text-slate-400">Locked — upgrade to view candidate bio.</span>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- Gold/Platinum-only sections placeholder --}}
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-base font-semibold text-slate-900">Expanded Details</h2>
                        <p class="mt-1 text-sm text-slate-600">
                            Work history, education history, social links, and CV access.
                        </p>
                    </div>

                    <div class="px-6 py-6 space-y-4">

                        {{-- Work history --}}
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">Work Experience History</div>
                                    <div class="text-xs text-slate-500">Full experience records</div>
                                </div>
                                @if (!$access['can_view_work_history'])
                                    <a href="{{ route('employer.subscription.dashboard') }}"
                                        class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                                        Upgrade
                                    </a>
                                @endif
                            </div>

                            <div class="mt-3 space-y-3">
                                @if ($access['can_view_work_history'])
                                    @php $exps = $application->candidateProfile?->resume?->experiences ?? collect(); @endphp

                                    @if ($exps->count() === 0)
                                        <div class="text-sm text-slate-500">No work experience records.</div>
                                    @else
                                        @foreach ($exps as $exp)
                                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                                <div class="flex flex-wrap items-center justify-between gap-2">
                                                    <div class="font-semibold text-slate-900">
                                                        {{ $exp->role ?? '—' }} <span
                                                            class="text-slate-400 font-medium">•</span>
                                                        {{ $exp->company ?? '—' }}
                                                    </div>
                                                    <div class="text-xs text-slate-600">
                                                        {{ $exp->start ?? '—' }} — {{ $exp->end ?? 'Present' }}
                                                    </div>
                                                </div>
                                                @if (!empty($exp->description))
                                                    <div class="mt-2 text-sm text-slate-700 leading-relaxed">
                                                        {{ $exp->description }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                @else
                                    <div class="text-sm text-slate-400">
                                        Locked — available on <span class="font-semibold">Gold</span> and <span
                                            class="font-semibold">Platinum</span>.
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Education history --}}
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">Education History</div>
                                    <div class="text-xs text-slate-500">Full education records</div>
                                </div>
                                @if (!$access['can_view_education_history'])
                                    <a href="{{ route('employer.subscription.dashboard') }}"
                                        class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                                        Upgrade
                                    </a>
                                @endif
                            </div>

                            <div class="mt-3 space-y-3">
                                @if ($access['can_view_education_history'])
                                    @php $edus = $application->candidateProfile?->resume?->educations ?? collect(); @endphp

                                    @if ($edus->count() === 0)
                                        <div class="text-sm text-slate-500">No education records.</div>
                                    @else
                                        @foreach ($edus as $edu)
                                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                                <div class="flex flex-wrap items-center justify-between gap-2">
                                                    <div class="font-semibold text-slate-900">
                                                        {{ $edu->degree ?? '—' }}
                                                    </div>
                                                    <div class="text-xs text-slate-600">
                                                        {{ $edu->year ?? '—' }}
                                                    </div>
                                                </div>
                                                <div class="mt-1 text-sm text-slate-700">
                                                    {{ $edu->school ?? '—' }}
                                                </div>
                                                @if (!empty($edu->notes))
                                                    <div class="mt-2 text-sm text-slate-600">
                                                        {{ $edu->notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                @else
                                    <div class="text-sm text-slate-400">
                                        Locked — available on <span class="font-semibold">Gold</span> and <span
                                            class="font-semibold">Platinum</span>.
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Social links --}}
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">Social Links</div>
                                    <div class="text-xs text-slate-500">LinkedIn / Facebook, etc.</div>
                                </div>
                                @if (!$access['can_view_social_links'])
                                    <a href="{{ route('employer.subscription.dashboard') }}"
                                        class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                                        Upgrade
                                    </a>
                                @endif
                            </div>

                            <div class="mt-3 text-sm text-slate-700">
                                @if ($access['can_view_social_links'])
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                            <div class="text-xs font-semibold text-slate-500">LinkedIn</div>
                                            <div class="mt-1 text-sm text-slate-900 break-all">
                                                {{ $candidate?->linkedin ?: '—' }}
                                            </div>
                                        </div>
                                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                            <div class="text-xs font-semibold text-slate-500">Facebook</div>
                                            <div class="mt-1 text-sm text-slate-900 break-all">
                                                {{ $candidate?->facebook ?: '—' }}
                                            </div>
                                        </div>
                                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                            <div class="text-xs font-semibold text-slate-500">WhatsApp</div>
                                            <div class="mt-1 text-sm text-slate-900 break-all">
                                                {{ $candidate?->whatsapp ?: '—' }}
                                            </div>
                                        </div>
                                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                            <div class="text-xs font-semibold text-slate-500">Telegram</div>
                                            <div class="mt-1 text-sm text-slate-900 break-all">
                                                {{ $candidate?->telegram ?: '—' }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-slate-400">
                                        Locked — available on <span class="font-semibold">Gold</span> and <span
                                            class="font-semibold">Platinum</span>.
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- CV Access --}}
                        @php
                            $resume = $application->candidateProfile?->resume;
                        @endphp

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <div class="text-sm font-semibold text-slate-900">Curriculum Vitae (CV)</div>

                            <div class="mt-2">
                                @if (!$resume || !$resume->resume_path)
                                    <div class="text-sm text-slate-500">No CV uploaded.</div>
                                @else
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 py-2">
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-slate-900 truncate">
                                                {{ $resume->resume_original_name ?? 'Resume' }}
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                {{ $resume->resume_mime ?? 'file' }}
                                                @if (!empty($resume->resume_size))
                                                    • {{ number_format($resume->resume_size / 1024, 1) }} KB
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex gap-2">
                                            @if ($access['can_preview_cv'])
                                                <a href="{{ route('employer.applicants.cv.preview', $application->id) }}"
                                                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-900 hover:bg-slate-50">
                                                    Preview CV
                                                </a>
                                            @endif

                                            @if ($access['can_download_cv'])
                                                <a href="{{ route('employer.applicants.cv.download', $application->id) }}"
                                                    class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                                                    Download CV
                                                </a>
                                            @endif

                                            @if (!($access['can_preview_cv'] || $access['can_download_cv']))
                                                <span class="text-xs text-slate-400">Locked</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($access['can_preview_cv'] && !$access['can_download_cv'])
                                        <div class="mt-2 text-xs text-slate-500">
                                            Gold plan allows <span class="font-semibold">preview only</span> (no download).
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Documents download --}}
                        {{-- Documents download --}}
                        @php
                            $resume = $application->candidateProfile?->resume;
                            $attachments = $resume?->attachments ?? collect();

                            // CV is handled in CandidateResume.resume_path so we exclude category cv here
                            $docs = $attachments->filter(fn($a) => strtolower($a->category ?? '') !== 'cv');
                        @endphp

                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">Uploaded Documents</div>
                                    <div class="text-xs text-slate-500">Passport, diploma, certificates</div>
                                </div>

                                @if (!$access['can_download_documents'])
                                    <a href="{{ route('employer.subscription.dashboard') }}"
                                        class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                                        Upgrade
                                    </a>
                                @endif
                            </div>

                            <div class="mt-3 space-y-2">
                                @if ($docs->count() === 0)
                                    <div class="text-sm text-slate-500">No documents uploaded.</div>
                                @else
                                    @foreach ($docs as $doc)
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 rounded-xl border border-slate-200 bg-slate-50 p-3">
                                            <div class="min-w-0">
                                                <div class="text-sm font-semibold text-slate-900 truncate">
                                                    {{ ucfirst($doc->category ?? 'document') }} —
                                                    {{ $doc->original_name ?? 'File' }}
                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $doc->mime ?? 'file' }}
                                                    @if (!empty($doc->size))
                                                        • {{ number_format($doc->size / 1024, 1) }} KB
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex gap-2">
                                                @if ($access['can_download_documents'])
                                                    <a href="{{ route('employer.applicants.docs.preview', [$application->id, $doc->id]) }}"
                                                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-900 hover:bg-slate-50">
                                                        Preview
                                                    </a>

                                                    <a href="{{ route('employer.applicants.docs.download', [$application->id, $doc->id]) }}"
                                                        class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                                                        Download
                                                    </a>
                                                @else
                                                    <span class="text-xs text-slate-400">Locked (Platinum)</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            @if (!$access['can_download_documents'])
                                <div class="mt-3 text-xs text-slate-500">
                                    Documents download is available on <span class="font-semibold">Platinum</span>.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right: Contact card (Platinum only full contact) --}}
            <div class="space-y-6">

                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-base font-semibold text-slate-900">Contact Information</h2>
                        <p class="mt-1 text-sm text-slate-600">
                            Full contact access is Platinum-only.
                        </p>
                    </div>

                    <div class="px-6 py-6 space-y-4">

                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="text-xs font-semibold text-slate-500">Email</div>
                            <div class="mt-1 text-sm font-semibold text-slate-900">
                                @if ($access['can_view_full_contact_info'])
                                    {{ $user?->email ?? '—' }}
                                @else
                                    <span class="text-slate-400">Locked</span>
                                @endif
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="text-xs font-semibold text-slate-500">Contact Number</div>
                            <div class="mt-1 text-sm font-semibold text-slate-900">
                                @if ($access['can_view_full_contact_info'])
                                    {{ $candidate?->contact_number ?? '—' }}
                                @else
                                    <span class="text-slate-400">Locked</span>
                                @endif
                            </div>
                            @if (!$access['can_view_full_contact_info'])
                                <div class="mt-2 text-xs text-slate-500">
                                    Upgrade to Platinum to access full contact details.
                                </div>
                            @endif
                        </div>

                        @if ($access['level'] !== 'platinum')
                            <a href="{{ route('employer.subscription.dashboard') }}"
                                class="block rounded-2xl bg-emerald-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-emerald-700">
                                Upgrade to Unlock More
                            </a>
                        @endif

                    </div>
                </div>

                {{-- Application notes / meta --}}
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-base font-semibold text-slate-900">Application Details</h2>
                    </div>
                    <div class="px-6 py-6 space-y-3 text-sm text-slate-700">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Applied at</span>
                            <span class="font-semibold text-slate-900">
                                {{ optional($application->created_at)->format('M d, Y h:i A') ?? '—' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Status</span>
                            <span
                                class="font-semibold text-slate-900">{{ ucfirst($application->status ?? 'applied') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
