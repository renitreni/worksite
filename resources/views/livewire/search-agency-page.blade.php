<div>
    {{-- HEADER --}}
    <header class="w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <div class="mb-6 sm:mb-8">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight">
                    Find Trusted <span class="text-[#16A34A]">Agencies</span>
                </h1>
                <p class="mt-2 text-gray-600 max-w-2xl">
                    Search by agency name and hiring industry.
                </p>
            </div>

            @include('mainpage.components.search-switcher', ['activeTab' => 'search-agency'])

            {{-- GREEN SEARCH CARD --}}
            <div class="relative overflow-hidden rounded-3xl shadow-lg border border-emerald-900/10">

                <div class="absolute inset-0 bg-gradient-to-br from-[#0f5f2f] via-[#16A34A] to-[#22c55e]"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.22),transparent_55%)]">
                </div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,rgba(0,0,0,0.18),transparent_50%)]">
                </div>

                <div class="relative p-4 sm:p-6 lg:p-8 space-y-5">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

                        {{-- Agency Name --}}
                        <div class="lg:col-span-6">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Agency Name
                            </label>
                            <div
                                class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90 px-4 py-3">
                                <i data-lucide="building-2" class="w-5 h-5 text-gray-400"></i>
                                <input wire:model.live="keyword" type="text" placeholder="Search agency name"
                                    class="w-full outline-none bg-transparent text-gray-800" />
                            </div>
                        </div>

                        {{-- Hiring Industry --}}
                        <div class="lg:col-span-6">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Hiring Industry
                            </label>
                            <div
                                class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90 px-4 py-3">
                                <i data-lucide="briefcase" class="w-5 h-5 text-gray-400"></i>
                                <select wire:model.live="industry"
                                    class="w-full outline-none bg-transparent text-gray-700">
                                    <option value="">All Industries</option>
                                    @foreach ($industries as $ind)
                                        <option value="{{ $ind->name }}">{{ $ind->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    {{-- Clear --}}
                    <div class="pt-2">
                        <button type="button" wire:click="clearFilters"
                            class="inline-flex items-center gap-2 rounded-2xl bg-white/90 px-4 py-2 text-sm font-bold text-[#0f5f2f]
           hover:bg-white transition">
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
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">

        <div class="flex items-center gap-2 text-sm text-gray-600">
            <i data-lucide="info" class="w-4 h-4"></i>
            <span>
                Showing <strong>{{ $agencies->firstItem() ?? 0 }}</strong>
                to <strong>{{ $agencies->lastItem() ?? 0 }}</strong>
                of <strong>{{ $agencies->total() }}</strong> agencies
            </span>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($agencies as $agency)
                <x-agency-card :agency="$agency" />
            @empty
                <div class="col-span-full bg-white border border-gray-200 rounded-2xl p-8 text-center">
                    <p class="text-gray-700 font-medium">No agencies found.</p>
                    <p class="text-gray-500 text-sm mt-1">
                        Try changing your search keyword or industry.
                    </p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $agencies->links('components.pagination') }}
        </div>

    </main>

</div>
