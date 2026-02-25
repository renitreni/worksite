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
                        <a x-show="{{ $i }} < shown" x-transition.opacity.duration.200ms
                            href="{{ route('jobs.index', ['industry_id' => $item['id']]) }}"
                            class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg
                                   transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                            <div class="h-32 w-full overflow-hidden">
                                <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('images/industry-fallback.jpg') }}"
                                    alt="{{ $item['name'] }}"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                    loading="lazy">
                            </div>

                            <div class="p-4">
                                <div class="text-center">
                                    <h3
                                        class="text-base font-semibold text-gray-900 group-hover:text-[#16A34A] transition-colors">
                                        {{ $item['name'] }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ number_format($item['jobs']) }} jobs
                                    </p>
                                </div>

                                @php
                                    $skills = collect($item['skills'] ?? [])
                                        ->take(3)
                                        ->values();
                                @endphp

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
                    @endforeach
                </div>

                <div class="mt-10 flex justify-center" x-show="shown < total" x-cloak>
                    <button type="button" @click="loadMore()"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#16A34A] px-5 py-3 text-sm font-semibold text-white
                               shadow-sm hover:bg-green-700 transition">
                        Load more
                        <span class="text-white/80 text-xs" x-text="`(${shown} / ${total})`"></span>
                    </button>
                </div>

            </div>
        @endif

    </div>
</section>
