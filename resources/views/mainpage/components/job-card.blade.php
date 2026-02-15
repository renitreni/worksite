@php
    $saved = $saved ?? false;
@endphp

@php
    $saved = $saved ?? false;
@endphp

<div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
    
    {{-- TOP ROW --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">
                {{ $title }}
            </h3>
            <p class="text-sm text-gray-500 mt-1">
                {{ $company }}
            </p>
        </div>

        {{-- Bookmark --}}
        <button class="text-gray-400 hover:text-gray-600 transition">
            <i data-lucide="bookmark"
               class="w-5 h-5 {{ $saved ? 'text-green-600' : '' }}"></i>
        </button>
    </div>

    {{-- TAG --}}
    <div class="mt-4">
        <span class="inline-flex items-center rounded-full bg-green-100 text-green-800 px-3 py-1 text-xs font-semibold">
            {{ $tag }}
        </span>
    </div>

    {{-- INFO --}}
    <div class="mt-5 space-y-3 text-sm text-gray-600">
        <div class="flex items-center gap-2">
            <i data-lucide="wallet" class="w-4 h-4 text-gray-400"></i>
            <span class="font-semibold text-gray-900">{{ $salary }}</span>
        </div>

        <div class="flex items-center gap-2">
            <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
            <span>{{ $location }}</span>
        </div>

        <div class="flex items-center gap-2">
            <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
            <span>{{ $vacancies }}</span>
        </div>

        <div class="flex items-center gap-2">
            <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
            <span>{{ $posted }}</span>
        </div>
    </div>

    {{-- BUTTON --}}
    <div class="mt-6">
        <a href="{{ $applyUrl }}"
           class="w-full inline-flex items-center justify-center rounded-xl bg-[#16A34A] px-4 py-3 text-sm font-semibold text-white hover:bg-green-700 transition">
            Apply Now
        </a>
    </div>

</div>
