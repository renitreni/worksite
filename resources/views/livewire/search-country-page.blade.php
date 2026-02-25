<div>
    <header class="w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <!-- Title -->
            <div class="mb-6 sm:mb-8">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight">
                    Find Jobs by <span class="text-[#16A34A]">Country</span>
                </h1>
                <p class="mt-2 text-gray-600 max-w-2xl">
                    Explore destinations and discover available opportunities from trusted agencies.
                </p>
            </div>

            @include('mainpage.components.search-switcher', ['activeTab' => 'search-country'])

            <!-- GREEN SEARCH CARD -->
            <div class="relative overflow-hidden rounded-3xl shadow-lg border border-emerald-900/10">

                <!-- background -->
                <div class="absolute inset-0 bg-gradient-to-br from-[#0f5f2f] via-[#16A34A] to-[#22c55e]"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.22),transparent_55%)]">
                </div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,rgba(0,0,0,0.18),transparent_50%)]">
                </div>

                <div class="relative p-4 sm:p-6 lg:p-8 space-y-5">


                        <!-- Country Name -->
                        <div class="lg:col-span-6">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Country Name
                            </label>
                            <div
                                class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90 px-4 py-3">
                                <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                                <input type="text" wire:model.live="keyword" placeholder="Example: Singapore"
                                    class="w-full outline-none bg-transparent text-gray-800">
                            </div>
                        </div>

                        
                   

                    <!-- Clear Filters -->
                    <div class="pt-2">
                        <button type="button" wire:click="clearFilters"
                            class="inline-flex items-center gap-2 rounded-2xl bg-white/90 px-4 py-2 text-sm font-bold text-[#0f5f2f] hover:bg-white transition">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            Clear filters
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </header>
    <div data-pagination-top></div>

    {{-- RESULTS --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex items-center gap-2 text-sm text-gray-600">
            <i data-lucide="info" class="w-4 h-4"></i>
            <span>
                Showing <strong>{{ $countries->firstItem() ?? 0 }}</strong>
                to <strong>{{ $countries->lastItem() ?? 0 }}</strong>
                of <strong>{{ $countries->total() }}</strong> countries
            </span>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse($countries as $country)
                @php
                    $flagFallback = $country->code
                        ? 'https://flagcdn.com/w640/' . strtolower($country->code) . '.png'
                        : 'https://placehold.co/640x400?text=Country';

                    $image = $country->image ? asset('storage/' . $country->image) : $flagFallback;
                @endphp

                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200 hover:shadow-md transition">

                    <div class="h-44 bg-cover bg-center" style="background-image:url('{{ $image }}')">
                    </div>

                    <div class="p-5">
                        <h3 class="text-xl font-extrabold text-gray-900">
                            {{ $country->name }}
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            <span class="font-semibold">{{ $country->jobs_count }}</span> Jobs
                        </p>

                        <p class="mt-3 text-sm text-gray-600">
                            Explore opportunities in {{ $country->name }}.
                        </p>

                        <a href="{{ route('search-jobs', ['country' => $country->name]) }}"
                            class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[#16A34A] hover:underline">
                            View Jobs
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>

                </div>

            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600">No countries found.</p>
                </div>
            @endforelse

        </div>

        <div class="mt-10">
            {{ $countries->links('components.pagination') }}
        </div>

    </main>

</div>
