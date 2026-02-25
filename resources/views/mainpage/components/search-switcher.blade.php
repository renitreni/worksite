@php
    $activeTab = $activeTab ?? '';
    $tabs = [
        ['key' => 'search-jobs', 'label' => 'Search Jobs', 'href' => route('search-jobs'), 'icon' => 'search'],
        [
            'key' => 'search-agency',
            'label' => 'Search Agency',
            'href' => route('search-agency'),
            'icon' => 'building-2',
        ],
        [
            'key' => 'search-industries',
            'label' => 'Search Industries',
            'href' => route('search-industries'),
            'icon' => 'layers',
        ],
        ['key' => 'search-country', 'label' => 'Search Country', 'href' => route('search-country'), 'icon' => 'globe'],
    ];
@endphp

<div class="mb-5 sm:mb-6">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
        @foreach ($tabs as $tab)
            @php $active = $tab['key'] === $activeTab; @endphp
            <a href="{{ $tab['href'] }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm sm:text-base transition
         {{ $active
             ? 'bg-[#16A34A] text-white shadow-md font-extrabold hover:bg-green-700'
             : 'bg-white border border-gray-200 text-gray-700 font-bold hover:bg-gray-50' }}">
                <i data-lucide="{{ $tab['icon'] }}" class="w-4 h-4"></i>
                <span class="whitespace-nowrap">{{ $tab['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>
