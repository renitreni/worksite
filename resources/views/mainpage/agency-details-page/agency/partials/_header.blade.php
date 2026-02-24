@php
    $cover = $agency['cover_image'] ?? '';
    $logo  = $agency['logo_image'] ?? '';
@endphp

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    {{-- Cover --}}
    <div class="relative h-44 sm:h-56 lg:h-64 bg-gray-200">
        @if($cover)
            <img src="{{ $cover }}" alt="Cover" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-r from-green-700/30 via-gray-900/20 to-green-900/30"></div>
        @endif

        {{-- subtle cover overlay --}}
        <div class="absolute inset-0 bg-black/10"></div>

        {{-- Cover action button --}}
        
    </div>

    {{-- Profile row --}}
    <div class="relative px-4 sm:px-6 pb-6">
        {{-- Logo overlaps cover --}}
        <div class="absolute -top-10 sm:-top-12 left-4 sm:left-6">
            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white border border-gray-200 shadow overflow-hidden flex items-center justify-center">
                @if($logo)
                    <img src="{{ $logo }}" alt="Logo" class="w-full h-full object-cover">
                @else
                    <span class="font-extrabold text-green-700 text-xl">LOGO</span>
                @endif
            </div>
        </div>

        <div class="pt-16 sm:pt-18 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            {{-- Name + stats --}}
            <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 truncate">
                    {{ $agency['name'] ?? 'Agency Name' }}
                </h1>

                <div class="mt-2 flex flex-wrap items-center gap-2 text-sm">
                    <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 border border-green-200 font-semibold">
                        {{ $agency['jobs_available'] ?? 0 }} jobs available
                    </span>
                    <span class="text-gray-500">•</span>
                    <span class="text-gray-600">{{ $agency['location'] ?? '—' }}</span>
                </div>

                <p class="mt-3 text-gray-600 leading-relaxed max-w-3xl">
                    {{ $agency['description'] ?? '' }}
                </p>

                {{-- Tags --}}
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach(($agency['tags'] ?? []) as $tag)
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 sm:gap-3">
                <button class="px-4 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold">
                    Contact
                </button>
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
