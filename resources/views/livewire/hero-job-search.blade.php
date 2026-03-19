<div class="w-full max-w-4xl mx-auto">

    <!-- SEARCH CONTAINER -->
    <div class="bg-white/95 backdrop-blur-xl rounded-xl sm:rounded-2xl shadow-xl border border-white/40 p-1.5 sm:p-2">

        <div class="flex flex-col md:flex-row items-stretch gap-1.5 sm:gap-2">

            <!-- KEYWORD -->
            <div
                class="flex items-center gap-2 sm:gap-3 flex-1 px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl transition focus-within:bg-gray-50">

                <x-lucide-icon name="briefcase" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" />

                <input wire:model.live="keyword" type="text" placeholder="Job title or keyword"
                    class="w-full outline-none bg-transparent text-gray-800 placeholder-gray-400 text-base" />

            </div>

            <!-- DIVIDER -->
            <div class="hidden md:block w-px bg-gray-200"></div>

            <!-- COUNTRY -->
            <div
                class="flex items-center gap-2 sm:gap-3 flex-1 px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl transition focus-within:bg-gray-50">

                <x-lucide-icon name="globe" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" />

                <select wire:model.live="country"
                    class="w-full outline-none bg-transparent text-gray-700 text-xs sm:text-sm md:text-base">

                    <option value="">All Countries</option>

                    @foreach ($this->countries as $c)
                        <option value="{{ $c->name }}">{{ $c->name }}</option>
                    @endforeach

                </select>

            </div>

            <!-- SEARCH BUTTON -->
            <button wire:click="search"
                class="flex items-center justify-center gap-1.5 sm:gap-2 bg-green-600 hover:bg-green-700
                       text-white font-semibold px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-xl transition
                       shadow-md hover:shadow-lg text-xs sm:text-sm md:text-base">

                <x-lucide-icon name="search" class="w-4 h-4 sm:w-5 sm:h-5" />

                <span>Search</span>

            </button>

        </div>

    </div>

    <!-- QUICK FILTERS -->
    <div class="mt-4 sm:mt-6 flex flex-wrap justify-center gap-1.5 sm:gap-2">

        @php
            $chips = [
                ['key' => 'no_fee', 'icon' => 'shield-check', 'label' => 'No placement fee'],
                ['key' => 'hs_grad', 'icon' => 'graduation-cap', 'label' => 'High school diploma'],
                ['key' => 'no_exp', 'icon' => 'user-x', 'label' => 'No experience'],
                ['key' => 'college_grad', 'icon' => 'award', 'label' => 'College graduate'],
                ['key' => 'masteral', 'icon' => 'book-open', 'label' => 'Masteral degree'],
                ['key' => 'phd', 'icon' => 'graduation-cap', 'label' => 'PhD / Doctorate'],
            ];
        @endphp

        @foreach ($chips as $chip)
            <button wire:click="toggleQuick('{{ $chip['key'] }}')"
                class="flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium
                       border transition
                       {{ $quick[$chip['key']] ? 'bg-green-600 text-white border-green-600' : 'bg-white/90 text-gray-700 border-gray-200 hover:border-green-500 hover:text-green-600' }}">

                <x-lucide-icon :name="$chip['icon']" class="w-3.5 h-3.5 sm:w-4 sm:h-4" />

                {{ $chip['label'] }}

            </button>
        @endforeach

    </div>

</div>
