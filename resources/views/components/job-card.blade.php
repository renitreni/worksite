@props(['job'])

@php
    $skills = collect(explode(',', (string) $job->skills))->map(fn($s) => trim($s))->filter();

    $company = $job->employerProfile->company_name ?? 'Agency / Company';

    $cur = $job->salary_currency ?? 'PHP';

    $salaryText = 'Not specified';

    if (!is_null($job->salary_min) || !is_null($job->salary_max)) {
        if (!is_null($job->salary_min) && !is_null($job->salary_max)) {
            $salaryText =
                $cur . ' ' . number_format((float) $job->salary_min) . ' - ' . number_format((float) $job->salary_max);
        } elseif (!is_null($job->salary_min)) {
            $salaryText = $cur . ' ' . number_format((float) $job->salary_min) . ' (min)';
        } else {
            $salaryText = $cur . ' ' . number_format((float) $job->salary_max) . ' (max)';
        }
    }

    $locationText = $job->country ?? '—';
    $postedText = ($job->posted_at ?? $job->created_at)->diffForHumans();

    $isSaved = auth()->check()
        ? \App\Models\SavedJob::where('user_id', auth()->id())
            ->where('job_post_id', $job->id)
            ->exists()
        : false;
@endphp


<div
    class="group h-full flex flex-col bg-white border border-gray-200 rounded-2xl p-5 shadow-sm
hover:shadow-lg hover:-translate-y-1 hover:border-[#16A34A]/40
transition-all duration-300">

    <div class="flex items-start justify-between gap-3">

        <div class="min-w-0 flex-1">
            <a href="{{ route('jobs.show', $job->id) }}" class="block group">
                <h3
                    class="section-title text-base md:text-lg font-semibold tracking-tight text-gray-900 
                leading-snug line-clamp-2 group-hover:text-[#16A34A] transition">
                    {{ $job->title }}
                </h3>
            </a>

            <p class="text-sm text-gray-500 mt-1 truncate">
                {{ $company }} Recruitment Agency
            </p>
        </div>

        {{-- SAVE BUTTON (restore this if needed) --}}

        @auth
            <div x-data="{ saved: @js($isSaved) }">

                <button
                    @click.prevent="
            fetch('{{ route('candidate.jobs.save', $job->id) }}',{
                method:'POST',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                    'X-Requested-With':'XMLHttpRequest',
                    'Content-Type':'application/json',
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
                    class="transition" ::class="saved ? 'text-[#16A34A]' : 'text-gray-400 hover:text-[#16A34A]'">

                    <x-lucide-icon name="bookmark" class="w-5 h-5 transition" ::class="saved ? 'fill-[#16A34A]' : ''" />

                </button>

            </div>
        @endauth

    </div>


    {{-- INDUSTRY --}}
    <div class="mt-3 flex flex-wrap gap-2">

        <span class="inline-flex items-center rounded-full bg-green-100 text-green-800 px-3 py-1 text-xs font-semibold">

            {{ $job->industry ?? '—' }}

        </span>

    </div>


    {{-- JOB DETAILS --}}
    <div class="mt-4 space-y-2 text-sm text-gray-600 flex-grow">

        <div class="flex items-center gap-2">
            <x-lucide-icon name="wallet" class="w-4 h-4 text-gray-400" />
            <span class="font-semibold text-gray-900">{{ $salaryText }}</span>
        </div>

        <div class="flex items-center gap-2">
            <x-lucide-icon name="map-pin" class="w-4 h-4 text-gray-400" />
            <span>{{ $locationText }}</span>
        </div>

        <div class="flex items-center gap-2">
            <x-lucide-icon name="users" class="w-4 h-4 text-gray-400" />
            <span>{{ $job->applications()->count() }} applicants</span>
        </div>

        <div class="flex items-center gap-2">
            <x-lucide-icon name="calendar" class="w-4 h-4 text-gray-400" />
            <span>Posted {{ $postedText }}</span>
        </div>

    </div>
    <p class="text-xs text-gray-500 mt-3">
        Apply for this overseas job opportunity and work abroad with verified employers.
    </p>


    {{-- BUTTON --}}
    <div class="mt-5">

        <a href="{{ route('jobs.show', $job->id) }}"
            class="w-full inline-flex items-center justify-center rounded-xl bg-[#16A34A]
            px-4 py-2.5 text-sm font-semibold text-white
            hover:bg-green-700 transition">

            View Details

        </a>

    </div>

</div>
