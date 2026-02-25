@props([
    'agency',
])

@php
    $companyName = $agency->company_name ?? 'Agency';
    $initial = strtoupper(mb_substr(trim($companyName), 0, 1));

    $bgClasses = [
        'bg-emerald-100 text-emerald-700 ring-emerald-200',
        'bg-lime-100 text-lime-700 ring-lime-200',
        'bg-green-100 text-green-700 ring-green-200',
        'bg-teal-100 text-teal-700 ring-teal-200',
    ];
    $pick = (ord($initial) ?: 0) % count($bgClasses);

    // safer logo check
    $logoExists = !empty($agency->logo_path) && file_exists(public_path('storage/' . $agency->logo_path));
@endphp

<div
    class="flex-none w-[390px] bg-white rounded-2xl border border-gray-200 shadow-sm
           hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

    <!-- Card Header -->
    <div class="p-5">
        <div class="flex items-start gap-4">
            {{-- LOGO / PLACEHOLDER --}}
            @if($logoExists)
                <img
                    src="{{ asset('storage/' . $agency->logo_path) }}"
                    alt="{{ $companyName }}"
                    class="w-16 h-16 rounded-xl object-cover border border-gray-200 bg-white shrink-0">
            @else
                <div class="w-16 h-16 rounded-xl grid place-items-center ring-1 {{ $bgClasses[$pick] }} shrink-0">
                    <span class="text-2xl font-extrabold tracking-tight">{{ $initial }}</span>
                </div>
            @endif

            <div class="min-w-0 flex-1">
                <!-- Company name: fixed 2 lines -->
                <h3 class="text-base font-semibold text-gray-900 leading-snug line-clamp-2 min-h-[2.6rem]">
                    {{ $companyName }}
                </h3>

                <p class="mt-1 text-sm font-medium text-green-700">
                    {{ $agency->open_jobs_count ?? 0 }} jobs available
                </p>
            </div>
        </div>

        <!-- Description: fixed 2 lines -->
        <p class="mt-4 text-sm text-gray-600 leading-relaxed line-clamp-2 min-h-[2.5rem]">
            {{ $agency->description ?? 'Trusted recruitment agency hiring now.' }}
        </p>

        <!-- Contact info (each 1 line, truncates with ...) -->
        <div class="mt-4 space-y-2 text-sm text-gray-600">
            <p class="flex items-center gap-2">
                <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 shrink-0"></i>
                <span class="truncate">
                    {{ $agency->company_address ?? 'Address not provided' }}
                </span>
            </p>

            <p class="flex items-center gap-2">
                <i data-lucide="mail" class="w-4 h-4 text-gray-400 shrink-0"></i>
                <span class="truncate">
                    {{ optional($agency->user)->email ?? 'Email not available' }}
                </span>
            </p>

            <p class="flex items-center gap-2">
                <i data-lucide="phone" class="w-4 h-4 text-gray-400 shrink-0"></i>
                <span class="truncate">
                    {{ $agency->company_contact ?? 'Contact not available' }}
                </span>
            </p>
        </div>
    </div>

    <!-- Card Footer -->
    <div class="px-5 pb-5 pt-0">
        <div class="flex items-center gap-3">
            <a
                href="{{ route('agency.details', $agency->id) }}"
                class="text-white bg-[#16A34A] px-4 py-2.5 rounded-xl font-semibold
                       hover:bg-green-700 transition text-center flex-1 text-sm">
                View Profile
            </a>

            <button
                type="button"
                class="w-11 h-11 rounded-xl border border-gray-200 grid place-items-center
                       text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition">
                <i data-lucide="bookmark" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

</div>