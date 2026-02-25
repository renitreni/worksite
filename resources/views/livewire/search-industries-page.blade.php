<div>
    <header class="w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <div class="mb-6 sm:mb-8">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight">
                    Browse Job <span class="text-[#16A34A]">Industries</span>
                </h1>
                <p class="mt-2 text-gray-600 max-w-2xl">
                    Choose a category and discover available jobs from trusted
                    agencies.
                </p>
            </div>

            @include('mainpage.components.search-switcher', ['activeTab' => 'search-industries'])

            <div class="relative overflow-hidden rounded-3xl shadow-lg border border-emerald-900/10">
                <div class="absolute inset-0 bg-gradient-to-br from-[#0f5f2f] via-[#16A34A] to-[#22c55e]"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.22),transparent_55%)]">
                </div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,rgba(0,0,0,0.18),transparent_50%)]">
                </div>

                <div class="relative p-4 sm:p-6 lg:p-8 space-y-5">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4">
                        <div class="lg:col-span-12">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Industry name
                            </label>

                            <div
                                class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90 px-4 py-3">
                                <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                                <input wire:model.live="keyword" type="text" placeholder="Example: Healthcare"
                                    class="w-full outline-none bg-transparent text-gray-800 placeholder:text-gray-400">
                            </div>
                        </div>
                    </div>

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
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex items-center gap-2 text-sm text-gray-600">
            <i data-lucide="info" class="w-4 h-4"></i>
            <span>
                Showing <strong>{{ $industries->firstItem() ?? 0 }}</strong> to
                <strong>{{ $industries->lastItem() ?? 0 }}</strong> out of
                <strong>{{ $industries->total() }}</strong> industries
            </span>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($industries as $ind)
                @php
                    $item = [
                        'name' => $ind->name,
                        'image' => $ind->image ?? null,
                        'jobs' => (int) ($ind->jobs_count ?? 0),
                        'skills' => $skillsMap[$ind->name] ?? [],
                    ];

                    // optional: clicking industry leads to jobs page filtered by industry
                    $href = route('search-jobs', ['industry' => $ind->name]);
                @endphp

                <x-industry-card :item="$item" :href="$href" />
            @empty
                <div class="col-span-full bg-white border border-gray-200 rounded-2xl p-8 text-center">
                    <p class="text-gray-700 font-medium">No industries found.</p>
                    <p class="text-gray-500 text-sm mt-1">Try a different keyword.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $industries->links('components.pagination') }}
        </div>
    </main>
</div>
