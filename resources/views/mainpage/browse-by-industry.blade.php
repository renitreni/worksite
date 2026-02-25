<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Browse by Industry
            </h2>
            <p class="text-gray-600 mt-3 max-w-xl mx-auto">
                Discover the most in-demand OFW job categories and opportunities.
            </p>
        </div>

        @if (($industryCards ?? collect())->count() === 0)
            <div class="max-w-2xl mx-auto bg-white border border-gray-200 rounded-2xl p-8 text-center">
                <p class="text-gray-700 font-medium">No industries yet.</p>
                <p class="text-gray-500 text-sm mt-1">Once industries and jobs are available, they will appear here.</p>
            </div>
        @else
            <div x-data="{
                total: {{ $industryCards->count() }},
                step: 4,
                shown: 4,
                init() {
                    // ✅ md+ show 8 initially, mobile show 4
                    this.shown = window.matchMedia('(min-width: 768px)').matches ? 8 : 4;
            
                    // ✅ keep correct when resizing
                    window.addEventListener('resize', () => {
                        const desired = window.matchMedia('(min-width: 768px)').matches ? 8 : 4;
                        // only increase if user hasn't loaded more yet
                        if (this.shown <= 8) this.shown = desired;
                    });
                },
                loadMore() {
                    this.shown = Math.min(this.shown + this.step, this.total);
                }
            }">

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($industryCards as $i => $item)
                        <x-industry-card :item="$item" :href="route('jobs.index', ['industry_id' => $item['id']])" x-show="{{ $i }} < shown"
                            x-transition.opacity.duration.200ms />
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center" x-show="shown < total" x-cloak>

                    <button type="button" @click="loadMore()"
                        class="inline-flex items-center gap-2
               rounded-xl border border-slate-300
               bg-white px-8 py-3
               text-sm font-semibold text-slate-700
               shadow-sm
               transition-all duration-200
               hover:border-slate-400
               hover:bg-slate-50
               active:scale-[0.98]
               focus:outline-none focus:ring-2 focus:ring-slate-300">

                        Load More

                        <!-- subtle arrow -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 transition-transform duration-200 group-hover:translate-y-0.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>

                    </button>

                </div>
            </div>
        @endif

    </div>
</section>
