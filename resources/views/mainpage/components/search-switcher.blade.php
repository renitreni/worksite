@php
    // current path checker
    $is = fn($path) => request()->is($path);

    // you can customize routes/urls here
    $tabs = [
        ['label' => 'Search Jobs', 'href' => url('/search-jobs'), 'icon' => 'search', 'active' => $is('search-jobs')],
        ['label' => 'Search Agency', 'href' => url('/search-agency'), 'icon' => 'building-2', 'active' => $is('search-agency')],
        ['label' => 'Search Industries', 'href' => url('/search-industries'), 'icon' => 'layers', 'active' => $is('search-industries')],
        ['label' => 'Search Country', 'href' => url('/search-country'), 'icon' => 'globe', 'active' => $is('search-country')],
    ];
@endphp

<div class="mb-5 sm:mb-6">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
        @foreach ($tabs as $tab)
            <a href="{{ $tab['href'] }}" class="inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm sm:text-base transition
                          {{ $tab['active']
            ? 'bg-[#16A34A] text-white shadow-md font-extrabold hover:bg-green-700'
            : 'bg-white border border-gray-200 text-gray-700 font-bold hover:bg-gray-50' }}">
                <i data-lucide="{{ $tab['icon'] }}" class="w-4 h-4"></i>
                <span class="whitespace-nowrap">{{ $tab['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>