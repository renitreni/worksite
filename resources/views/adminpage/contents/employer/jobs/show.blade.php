@extends('adminpage.layout')

@section('content')

<div class="space-y-6">

    {{-- 🔷 HEADER CARD --}}
    <div class="bg-gradient-to-r from-indigo-50 to-white rounded-3xl p-6 shadow-sm">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">
                    {{ $job->title }}
                </h1>

                <div class="mt-2 flex flex-wrap items-center gap-2">

                    <span class="bg-slate-900 text-white px-3 py-1 rounded-full text-xs font-semibold">
                        {{ $job->industry ?? '—' }}
                    </span>

                    <span class="px-3 py-1 text-xs rounded-full font-semibold
                        {{ $job->status === 'open'
                            ? 'bg-emerald-100 text-emerald-700'
                            : 'bg-rose-100 text-rose-700' }}">
                        {{ strtoupper($job->status) }}
                    </span>

                    <span class="text-xs text-slate-500">
                        Posted {{ ($job->posted_at ?? $job->created_at)->format('M d, Y') }}
                    </span>

                    @if($job->apply_until)
                        <span class="text-xs text-slate-500">
                            • Until {{ \Carbon\Carbon::parse($job->apply_until)->format('M d, Y') }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-wrap gap-2">

                @if($job->status === 'open')
                    <form method="POST" action="{{ route('admin.admin-job-posts.close', $job->id) }}">
                        @csrf @method('PUT')
                        <button class="px-4 py-2.5 rounded-xl bg-rose-600 text-white text-sm font-semibold hover:bg-rose-700 shadow-sm">
                            Close
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.admin-job-posts.reopen', $job->id) }}">
                        @csrf @method('PUT')
                        <button class="px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 shadow-sm">
                            Reopen
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.admin-job-posts.edit', $job->id) }}"
                   class="px-4 py-2.5 rounded-xl bg-white text-slate-700 text-sm font-semibold shadow-sm hover:bg-slate-50">
                    Edit
                </a>

                <a href="{{ url()->previous() }}"
                   class="px-4 py-2.5 rounded-xl bg-white text-slate-700 text-sm font-semibold shadow-sm hover:bg-slate-50">
                    Back
                </a>

            </div>

        </div>
    </div>

    @php
        $skills = collect(explode(',', (string) $job->skills))->map(fn($s) => trim($s))->filter();

        $salary = $job->salary_min || $job->salary_max
            ? ($job->salary_currency ?? 'PHP') . ' ' . number_format($job->salary_min ?? 0) . ' - ' . number_format($job->salary_max ?? 0)
            : 'Not specified';

        $fee = $job->placement_fee
            ? ($job->placement_fee_currency ?? 'PHP') . ' ' . number_format($job->placement_fee)
            : 'Not specified';
    @endphp

    {{-- 🔷 QUICK STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <p class="text-xs text-slate-500">Location</p>
            <p class="mt-1 text-sm font-semibold text-slate-900">
                {{ $job->country }} • {{ $job->city }} • {{ $job->area }}
            </p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <p class="text-xs text-slate-500">Salary</p>
            <p class="mt-1 text-sm font-semibold text-slate-900">
                {{ $salary }}
            </p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm">
            <p class="text-xs text-slate-500">Applicants</p>
            <p class="mt-1 text-sm font-semibold text-slate-900">
                {{ $job->applications()->count() }}
            </p>
        </div>

    </div>

    {{-- 🔷 MAIN CONTENT --}}
    <div class="grid lg:grid-cols-3 gap-6">

        {{-- LEFT --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <h2 class="font-semibold text-slate-900">Job Description</h2>
                <p class="mt-3 text-sm text-slate-700 whitespace-pre-line">
                    {{ $job->job_description }}
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <h2 class="font-semibold text-slate-900">Qualifications</h2>
                <p class="mt-3 text-sm text-slate-700 whitespace-pre-line">
                    {{ $job->job_qualifications ?: '—' }}
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <h2 class="font-semibold text-slate-900">Additional Information</h2>
                <p class="mt-3 text-sm text-slate-700 whitespace-pre-line">
                    {{ $job->additional_information ?: '—' }}
                </p>
            </div>

        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="space-y-6">

            {{-- Employer --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <p class="text-xs text-slate-500">Employer</p>
                <p class="mt-1 font-semibold text-slate-900">
                    {{ $job->employerProfile->company_name }}
                </p>
                <p class="text-xs text-slate-500">
                    {{ $job->employerProfile->user->email ?? '' }}
                </p>
            </div>

            {{-- Skills --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <p class="text-xs text-slate-500 mb-2">Skills</p>

                <div class="flex flex-wrap gap-2">
                    @forelse($skills as $s)
                        <span class="bg-emerald-50 text-emerald-700 px-3 py-1 text-xs rounded-full">
                            {{ $s }}
                        </span>
                    @empty
                        <span class="text-xs text-slate-400">No skills</span>
                    @endforelse
                </div>
            </div>

            {{-- Fees --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <p class="text-xs text-slate-500">Placement Fee</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ $fee }}
                </p>
            </div>

            {{-- Principal --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <p class="text-xs text-slate-500">Principal Employer</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ $job->principal_employer ?: '—' }}
                </p>
            </div>

        </div>

    </div>

</div>

@endsection