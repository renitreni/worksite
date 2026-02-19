@props(['job'])

@php
    $skills = collect(explode(',', (string) $job->skills))
        ->map(fn($s) => trim($s))
        ->filter();

    $company = $job->employerProfile->company_name ?? 'Agency / Company';

    $cur = $job->salary_currency ?? 'PHP';

    $salaryText = 'Not specified';

    if (!is_null($job->salary_min) || !is_null($job->salary_max)) {
        if (!is_null($job->salary_min) && !is_null($job->salary_max)) {
            $salaryText = $cur . ' ' . number_format((float) $job->salary_min) . ' - ' . number_format((float) $job->salary_max);
        } elseif (!is_null($job->salary_min)) {
            $salaryText = $cur . ' ' . number_format((float) $job->salary_min) . ' (min)';
        } else {
            $salaryText = $cur . ' ' . number_format((float) $job->salary_max) . ' (max)';
        }
    }

    $locationText = $job->country ?? '—';
    $postedText = ($job->posted_at ?? $job->created_at)->diffForHumans();
@endphp

<div class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm
       hover:shadow-xl hover:-translate-y-2 hover:border-[#16A34A]/40
       transition-all duration-300">

    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-[#16A34A] transition-colors truncate">
                {{ $job->title }}
            </h3>
            <p class="text-sm text-gray-500 mt-1 truncate">{{ $company }}</p>
        </div>

        <button type="button"
            class="text-gray-400 group-hover:text-[#16A34A] group-hover:scale-110 transition-all duration-300">
            <i data-lucide="bookmark" class="w-5 h-5"></i>
        </button>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        <span class="inline-flex items-center rounded-full bg-green-100 text-green-800 px-3 py-1 text-xs font-semibold">
            {{ $job->industry ?? '—' }}
        </span>

        @if($skills->count())
            <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-700 px-3 py-1 text-xs font-semibold">
                {{ $skills->first() }}
            </span>
        @endif
    </div>

    <div class="mt-5 space-y-3 text-sm text-gray-600">
        <div class="flex items-center gap-2">
            <i data-lucide="wallet" class="w-4 h-4 text-gray-400"></i>
            <span class="font-semibold text-gray-900">{{ $salaryText }}</span>
        </div>

        <div class="flex items-center gap-2">
            <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
            <span>{{ $locationText }}</span>
        </div>

        <div class="flex items-center gap-2">
            <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
            <span>{{ $job->applications()->count() }} applicants</span>
        </div>

        <div class="flex items-center gap-2">
            <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
            <span>Posted {{ $postedText }}</span>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('jobs.show', $job->id) }}"
            class="w-full inline-flex items-center justify-center rounded-xl bg-[#16A34A] px-4 py-3 text-sm font-semibold text-white hover:bg-green-700 transition">
            View Details
        </a>
    </div>
</div>