@props([
    'item' => [],
    'href' => null,
])

@php
    $image = !empty($item['image']) ? asset('storage/' . $item['image']) : asset('images/industry-fallback.png');

    $skills = collect($item['skills'] ?? [])
        ->take(3)
        ->values();

    // generate route if href not provided
    $href = $href ?? route('industries.jobs', $item['id']);
@endphp

<a href="{{ $href }}"
    {{ $attributes->merge([
        'class' => "group bg-white rounded-2xl border border-gray-100
               shadow-sm hover:shadow-xl
               transition-all duration-300 hover:-translate-y-1
               overflow-hidden",
    ]) }}>

    <!-- Image -->
    <div class="relative h-36 w-full overflow-hidden">

        <img src="{{ $image }}" alt="{{ $item['name'] ?? 'Industry' }}"
            class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">

        <!-- Gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-black/10 to-transparent"></div>

        <!-- Job badge -->
        <div
            class="absolute top-3 right-3
               bg-white/90 backdrop-blur
               text-green-700 text-xs font-semibold
               px-3 py-1 rounded-full
               shadow-sm border border-green-100">

            {{ number_format($item['jobs'] ?? 0) }} jobs

        </div>

    </div>

    <!-- Content -->
    <div class="pt-6 pb-5 px-5">

        <div class="text-center">

            <h3
                class="section-title text-base font-semibold text-gray-900
                   group-hover:text-green-600 transition">

                {{ $item['name'] ?? '—' }}

            </h3>

        </div>

        @if ($skills->count())

            <div class="mt-4 flex flex-wrap justify-center gap-1.5">

                @foreach ($skills as $skill)
                    <span
                        class="text-[11px] font-medium
                   bg-green-50 text-green-700
                   border border-green-100
                   px-2.5 py-1 rounded-md
                   truncate max-w-[110px]">

                        {{ $skill }}

                    </span>
                @endforeach

            </div>

        @endif

    </div>

</a>
