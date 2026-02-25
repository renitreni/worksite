<div>
    {{-- HEADER / FILTERS --}}
    <header class="w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <div class="mb-6 sm:mb-8">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight">
                    Find Overseas <span class="text-[#16A34A]">Jobs</span> here
                </h1>
                <p class="mt-2 text-gray-600 max-w-2xl">
                    Search by keyword, preferred country — then apply quick filters to match your needs.
                </p>
            </div>
            @include('mainpage.components.search-switcher', ['activeTab' => 'search-jobs'])

            <div class="relative overflow-hidden rounded-3xl shadow-lg border border-emerald-900/10">
                {{-- background --}}
                <div class="absolute inset-0 bg-gradient-to-br from-[#0f5f2f] via-[#16A34A] to-[#22c55e]"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.22),transparent_55%)]">
                </div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,rgba(0,0,0,0.18),transparent_50%)]">
                </div>

                <div class="relative p-4 sm:p-6 lg:p-8 space-y-5">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4">

                        {{-- Keywords --}}
                        <div class="lg:col-span-6">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Jobs by Keywords
                            </label>

                            <div
                                class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90 backdrop-blur-md px-4 py-3">
                                <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                                <input wire:model.live="keyword" type="text" placeholder="Search Jobs by Keywords"
                                    class="w-full outline-none bg-transparent text-gray-800 placeholder:text-gray-400" />
                            </div>
                        </div>

                        {{-- Country --}}
                        <div class="lg:col-span-6">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Preferred Country
                            </label>

                            <div
                                class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90 backdrop-blur-md px-4 py-3">
                                <i data-lucide="globe" class="w-5 h-5 text-gray-400"></i>
                                <select wire:model.live="country"
                                    class="w-full outline-none bg-transparent text-gray-700">
                                    <option value="">All Countries</option>
                                    @foreach ($this->countries as $c)
                                        <option value="{{ $c->name }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    {{-- CHIPS --}}
                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-3">
                        @php
                            $filters = [
                                ['key' => 'no_fee', 'icon' => 'shield-check', 'label' => 'No placement fee'],
                                ['key' => 'hs_grad', 'icon' => 'graduation-cap', 'label' => 'High school graduate'],
                                ['key' => 'no_exp', 'icon' => 'user-x', 'label' => 'No work experience'],
                                ['key' => 'college_grad', 'icon' => 'award', 'label' => 'College graduate'],
                                ['key' => 'masteral', 'icon' => 'book-open', 'label' => 'Masteral degree'],
                                ['key' => 'phd', 'icon' => 'graduation-cap', 'label' => 'PhD / Doctorate'],
                            ];
                        @endphp

                        @foreach ($filters as $f)
                            @php
                                $key = $f['key'];
                                $active = (bool) (${$key} ?? null);
                            @endphp

                            <button type="button" wire:click="toggle('{{ $key }}')"
                                class="w-full sm:w-auto inline-flex items-center gap-2 rounded-2xl px-3 sm:px-4 py-2
                                       border shadow-md transition select-none
                                       {{ $active
                                           ? 'bg-green-600 border-green-600 text-white'
                                           : 'bg-white/90 border-white/30 text-gray-800 hover:bg-white' }}
                                       hover:-translate-y-0.5 hover:shadow-lg duration-200">

                                <i data-lucide="{{ $f['icon'] }}"
                                    class="w-4 h-4 {{ $active ? 'text-white' : 'text-green-700' }}"></i>

                                <span class="text-xs sm:text-sm font-semibold">
                                    {{ $f['label'] }}
                                </span>

                                @if ($active)
                                    <i data-lucide="x" class="w-4 h-4 text-white/90"></i>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    {{-- Optional: Clear all --}}
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
                Showing <strong>{{ $jobs->firstItem() ?? 0 }}</strong> to <strong>{{ $jobs->lastItem() ?? 0 }}</strong>
                out of <strong>{{ $jobs->total() }}</strong> jobs
            </span>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($jobs as $job)
                {{-- ✅ Use your job card component --}}
                <x-job-card :job="$job" />
            @empty
                <div class="col-span-full bg-white border border-gray-200 rounded-2xl p-8 text-center">
                    <p class="text-gray-700 font-medium">No jobs found.</p>
                    <p class="text-gray-500 text-sm mt-1">Try changing your keyword or filters.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $jobs->links() }}
        </div>
    </main>
</div>
