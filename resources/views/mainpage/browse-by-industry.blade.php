<section id="industries" class="py-16 bg-gradient-to-b from-white to-gray-50">

    <div class="max-w-7xl mx-auto px-6">

        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto">

            <h2 class="section-title text-3xl md:text-4xl font-semibold text-gray-900">
                Browse Jobs by Industry
            </h2>

            <p class="mt-4 text-gray-600 text-sm sm:text-base leading-relaxed">
                Explore the most in-demand overseas career opportunities across
                industries trusted by Filipino workers.
            </p>

        </div>

        @if (($industryCards ?? collect())->count())

            <!-- INDUSTRY CHIPS -->
            <div class="mt-10 overflow-x-auto">

                <div class="flex gap-2 w-max sm:flex-wrap sm:w-full sm:justify-center">

                    @foreach ($industryCards->take(6) as $item)
                        <a href="{{ route('industries.jobs', $item['id']) }}"
                            class="px-4 py-2 text-xs sm:text-sm
                            bg-white border border-gray-200
                            rounded-full
                            text-gray-700
                            whitespace-nowrap
                            hover:border-green-500 hover:text-green-600
                            transition">

                            {{ $item['name'] }}

                        </a>
                    @endforeach

                </div>

            </div>

        @endif


        <!-- Divider -->
        <div class="mt-12 border-t border-gray-100"></div>


        @if (($industryCards ?? collect())->count() === 0)

            <!-- Empty State -->
            <div class="max-w-xl mx-auto bg-white border border-gray-200 rounded-2xl p-10 text-center shadow-sm mt-12">

                <p class="text-gray-700 font-medium">
                    No industries available yet
                </p>

                <p class="text-gray-500 text-sm mt-2">
                    Industries will appear here once job categories are added.
                </p>

            </div>
        @else
            <!-- GRID + ALPINE -->
            <div class="mt-12" x-data="{
                total: {{ $industryCards->count() }},
                step: 4,
                shown: 4,
                initial: 4,
                init() {
                    this.initial = window.innerWidth >= 768 ? 8 : 4;
                    this.shown = this.initial;
                },
                loadMore() {
                    this.shown = Math.min(this.shown + this.step, this.total);
                },
                seeLess() {
                    this.shown = this.initial;
            
                    this.$nextTick(() => {
                        document.getElementById('industries')
                            .scrollIntoView({ behavior: 'smooth', block: 'start' });
                    });
                }
            }">

                <!-- Industry Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                    @foreach ($industryCards as $i => $item)
                        <x-industry-card x-cloak :item="$item" x-show="{{ $i }} < shown"
                            x-transition.opacity />
                    @endforeach

                </div>


                <!-- Buttons -->
                <div class="mt-16 flex justify-center gap-4">

                    <!-- LOAD MORE -->
                    <button x-show="shown < total" x-cloak @click="loadMore()"
                        class="inline-flex items-center gap-2
                        bg-green-600 hover:bg-green-700
                        text-white font-semibold
                        px-8 py-3 rounded-xl
                        shadow-md hover:shadow-lg
                        transition duration-200">

                        Load More

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />

                        </svg>

                    </button>


                    <!-- SEE LESS -->
                    <button x-show="shown >= total && total > initial" x-cloak @click="seeLess()"
                        class="inline-flex items-center gap-2
                        border border-green-600
                        text-green-700
                        px-8 py-3 rounded-xl
                        font-semibold
                        hover:bg-green-50
                        transition">

                        See Less

                    </button>

                </div>

            </div>

        @endif

    </div>

</section>
