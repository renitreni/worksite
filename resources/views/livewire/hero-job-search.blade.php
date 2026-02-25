<div>
    <div
        class="mt-6 sm:mt-10 bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/30
                p-3 sm:p-4 flex flex-col md:flex-row gap-2 sm:gap-3 items-stretch animate-slide-up">

        {{-- Keyword --}}
        <div
            class="flex items-center gap-2 flex-1 border border-gray-200 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3
                    focus-within:border-green-600 transition transform hover:-translate-y-0.5 hover:shadow-md duration-200 bg-white">
            <i data-lucide="briefcase" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400"></i>
            <input wire:model.live="keyword" type="text" placeholder="Job title or keyword"
                class="w-full outline-none text-gray-700 placeholder-gray-400 text-sm sm:text-lg bg-transparent" />
        </div>

        {{-- Country --}}
        <div
            class="flex items-center gap-2 flex-1 border border-gray-200 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3
                    focus-within:border-green-600 transition transform hover:-translate-y-0.5 hover:shadow-md duration-200 bg-white">
            <i data-lucide="globe" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400"></i>
            <select wire:model.live="country" class="w-full outline-none text-gray-700 bg-transparent text-sm sm:text-lg">
                <option value="">All Countries</option>
                @foreach ($this->countries as $c)
                    <option value="{{ $c->name }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Search --}}
        <button type="button" wire:click="search"
            class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 transition
                   text-white font-semibold px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl
                   text-sm sm:text-lg transform hover:-translate-y-0.5 hover:shadow-md duration-200">
            <i data-lucide="search" class="w-4 h-4 sm:w-5 sm:h-5"></i>
            Search Jobs
        </button>
    </div>

    {{-- Chips --}}
    <div class="mt-5 sm:mt-6 grid grid-cols-2 sm:flex sm:flex-wrap justify-center gap-2 sm:gap-3">
        @php
            $chips = [
                ['key' => 'no_fee', 'icon' => 'shield-check', 'label' => 'No placement fee'],
                ['key' => 'hs_grad', 'icon' => 'graduation-cap', 'label' => 'High school diploma'],
                ['key' => 'no_exp', 'icon' => 'user-x', 'label' => 'No work experience'],
                ['key' => 'college_grad', 'icon' => 'award', 'label' => 'College graduate'],
                ['key' => 'masteral', 'icon' => 'book-open', 'label' => 'Masteral degree'],
                ['key' => 'phd', 'icon' => 'graduation-cap', 'label' => 'PhD / Doctorate'],
            ];
        @endphp

        @foreach ($chips as $chip)
            <button type="button" wire:click="toggleQuick('{{ $chip['key'] }}')"
                class="filter-card flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md border border-white/30
                       rounded-2xl px-3 sm:px-4 py-2 cursor-pointer transition transform hover:-translate-y-0.5
                       hover:shadow-lg duration-200
                       {{ $quick[$chip['key']] ? 'bg-green-50 border-green-600' : '' }}">
                <i data-lucide="{{ $chip['icon'] }}" class="w-4 h-4 text-green-700"></i>
                <span class="text-xs sm:text-sm font-medium {{ $quick[$chip['key']] ? 'text-green-700' : 'text-gray-800' }}">
                    {{ $chip['label'] }}
                </span>
            </button>
        @endforeach
    </div>
</div>