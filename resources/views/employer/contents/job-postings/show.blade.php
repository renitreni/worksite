@extends('employer.layout')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- Flash --}}
            <x-toast type="success" :message="session('success')" />

            {{-- Header --}}
            <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">{{ $job->title }}</h1>
                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold text-white">
                                {{ $job->industry }}
                            </span>

                            <span
                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                {{ $job->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ strtoupper($job->status) }}
                            </span>

                            <span class="text-xs text-slate-500">
                                Posted: {{ ($job->posted_at ?? $job->created_at)->format('M d, Y') }}
                            </span>

                            @if($job->apply_until)
                                <span class="text-xs text-slate-500">
                                    • Apply until: {{ \Carbon\Carbon::parse($job->apply_until)->format('M d, Y') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if($job->status === 'open')
                            <form action="{{ route('employer.job-postings.destroy', $job->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-200">
                                    Close Job
                                </button>
                            </form>
                        @else
                            <form action="{{ route('employer.job-postings.reopen', $job->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                                    Reopen Job
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('employer.job-postings.edit', $job->id) }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Edit
                        </a>

                        <a href="{{ url()->previous() }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Back
                        </a>
                    </div>
                </div>
            </div>

            @php
                $skills = collect(explode(',', (string) $job->skills))->map(fn($s) => trim($s))->filter()->values();
                $salaryText = 'Not specified';
                if (!is_null($job->salary_min) || !is_null($job->salary_max)) {
                    $cur = $job->salary_currency ?? 'PHP';

                    if (!is_null($job->salary_min) && !is_null($job->salary_max)) {
                        $salaryText = $cur . ' ' . number_format((float) $job->salary_min, 2) . ' - ' . number_format((float) $job->salary_max, 2);
                    } elseif (!is_null($job->salary_min)) {
                        $salaryText = $cur . ' ' . number_format((float) $job->salary_min, 2) . ' (min)';
                    } else {
                        $salaryText = $cur . ' ' . number_format((float) $job->salary_max, 2) . ' (max)';
                    }
                }
                $feeText = $job->placement_fee !== null
                    ? (($job->placement_fee_currency ?? 'PHP') . ' ' . number_format((float) $job->placement_fee, 2))
                    : 'Not specified';
            @endphp

            <div class="px-6 sm:px-8 py-8 space-y-8">

                {{-- Quick info cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-xs text-slate-500">Location</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $job->country }}
                            @if($job->city) • {{ $job->city }} @endif
                            @if($job->area) • {{ $job->area }} @endif
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-xs text-slate-500">Salary</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $salaryText }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-xs text-slate-500">Applications</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $job->applications()->count() }}</p>
                    </div>
                </div>

                {{-- Skills chips --}}
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Skills</h2>
                    @if($skills->count())
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($skills as $s)
                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-semibold text-emerald-800">
                                    {{ $s }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-2 text-sm text-slate-500">No skills listed.</p>
                    @endif
                </div>

                {{-- Descriptions --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="rounded-2xl border border-slate-200 p-6">
                        <h2 class="text-base font-semibold text-slate-900">Job Description</h2>
                        <p class="mt-3 text-sm text-slate-700 whitespace-pre-line">{{ $job->job_description }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-6 space-y-5">
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Qualifications</h2>
                            <p class="mt-3 text-sm text-slate-700 whitespace-pre-line">{{ $job->job_qualifications ?: '—' }}
                            </p>
                        </div>

                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Additional Information</h2>
                            <p class="mt-3 text-sm text-slate-700 whitespace-pre-line">
                                {{ $job->additional_information ?: '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- DMW / Fees --}}
                <div class="rounded-2xl border border-slate-200 p-6">
                    <h2 class="text-base font-semibold text-slate-900">Principal / DMW / Fees</h2>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs text-slate-500">Principal / Employer</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $job->principal_employer ?: '—' }}</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs text-slate-500">DMW Reg/Accreditation No.</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $job->dmw_registration_no ?: '—' }}</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 md:col-span-2">
                            <p class="text-xs text-slate-500">Principal / Employer Address</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $job->principal_employer_address ?: '—' }}</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs text-slate-500">Placement Fee</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $feeText }}</p>
                        </div>
                    </div>
                </div>

                {{-- Applicants --}}
                <div class="rounded-2xl border border-slate-200 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold text-slate-900">Applicants</h2>
                        <span class="text-xs text-slate-500">{{ $job->applications->count() }} total</span>
                    </div>

                    <div class="mt-4">
                        @if($job->applications()->count())
                            <ul class="divide-y divide-slate-200">
                                @foreach($job->applications as $application)
                                    <li class="py-4 flex items-center justify-between gap-4">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-slate-900 truncate">
                                                {{ $application->candidate->name ?? 'Unnamed Candidate' }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                Applied: {{ $application->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-slate-500">No applicants yet.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection