@php
    $companyName = $agency->company_name ?? 'Agency';
    $initial = strtoupper(mb_substr(trim($companyName), 0, 1));

    $coverExists = !empty($agency->cover_path) && file_exists(public_path('storage/' . $agency->cover_path));
    $logoExists  = !empty($agency->logo_path)  && file_exists(public_path('storage/' . $agency->logo_path));

    $bgClasses = [
        'bg-emerald-100 text-emerald-700 ring-emerald-200',
        'bg-lime-100 text-lime-700 ring-lime-200',
        'bg-green-100 text-green-700 ring-green-200',
        'bg-teal-100 text-teal-700 ring-teal-200',
    ];
    $pick = (ord($initial) ?: 0) % count($bgClasses);
@endphp

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    {{-- Cover --}}
    <div class="relative h-44 sm:h-56 lg:h-64 bg-gray-200">
        @if($coverExists)
            <img src="{{ asset('storage/' . $agency->cover_path) }}" alt="Cover" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-r from-green-700/25 via-gray-900/15 to-green-900/25"></div>
        @endif

        <div class="absolute inset-0 bg-black/10"></div>
    </div>

    {{-- Profile row --}}
    <div class="relative px-4 sm:px-6 pb-6">
        {{-- Logo overlaps cover --}}
        <div class="absolute -top-10 sm:-top-12 left-4 sm:left-6">
            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white border border-gray-200 shadow overflow-hidden flex items-center justify-center">
                @if($logoExists)
                    <img src="{{ asset('storage/' . $agency->logo_path) }}" alt="Logo" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full grid place-items-center ring-1 {{ $bgClasses[$pick] }}">
                        <span class="font-extrabold text-3xl">{{ $initial }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="pt-16 sm:pt-18 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 truncate">
                    {{ $companyName }}
                </h1>

                <div class="mt-2 flex flex-wrap items-center gap-2 text-sm">
                    <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 border border-green-200 font-semibold">
                        {{ $openJobsCount ?? 0 }} jobs available
                    </span>
                    <span class="text-gray-500">•</span>
                    <span class="text-gray-600">{{ $agency->company_address ?? '—' }}</span>
                </div>

                <p class="mt-3 text-gray-600 leading-relaxed max-w-3xl line-clamp-2">
                    {{ $agency->description ?? '' }}
                </p>
            </div>

            {{-- Actions (UI only for now) --}}
            <div class="flex gap-2 sm:gap-3">
                <a href="#jobs"
                   class="px-4 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold">
                    View Jobs
                </a>
                <button class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 font-semibold">
                    Follow
                </button>
                <button class="w-11 h-11 rounded-xl border border-gray-200 hover:bg-gray-50 flex items-center justify-center">
                    <i data-lucide="more-horizontal" class="w-5 h-5 text-gray-700"></i>
                </button>
            </div>
        </div>
    </div>
</div>