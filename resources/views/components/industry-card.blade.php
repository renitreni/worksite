@props([
    'item' => [],
    'href' => '#',
])

@php
    $image = !empty($item['image'])
        ? asset('storage/' . $item['image'])
        : asset('images/industry-fallback.jpg');

    $skills = collect($item['skills'] ?? [])->take(3)->values();
@endphp

<a href="{{ $href }}"
   {{ $attributes->merge([
        'class' => "group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg
                   transition-all duration-300 hover:-translate-y-1 overflow-hidden"
   ]) }}>
    <div class="h-32 w-full overflow-hidden">
        <img src="{{ $image }}"
             alt="{{ $item['name'] ?? 'Industry' }}"
             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
             loading="lazy">
    </div>

    <div class="p-4">
        <div class="text-center">
            <h3 class="text-base font-semibold text-gray-900 group-hover:text-[#16A34A] transition-colors">
                {{ $item['name'] ?? '—' }}
            </h3>
            <p class="text-xs text-gray-500 mt-1">
                {{ number_format($item['jobs'] ?? 0) }} jobs
            </p>
        </div>

        @if ($skills->count())
            <div class="mt-3 space-y-1">
                @foreach ($skills as $skill)
                    <div class="rounded-md bg-green-50 border border-green-100 px-3 py-1
                                text-[11px] font-semibold text-green-700 text-center truncate"
                         title="{{ $skill }}">
                        {{ $skill }}
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</a>