<section class="py-16 bg-gray-50">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Featured Agencies
            </h2>
            <p class="text-gray-600 mt-3 max-w-xl mx-auto">
                Connect with trusted recruitment agencies hiring now
            </p>
        </div>

        <!-- Slider Wrapper -->
        <div x-data="agencySlider()" x-init="init()" class="relative">
            <!-- LEFT ARROW (hide when first) -->
            <button type="button" @click="prev()" x-show="canPrev" x-transition.opacity class="absolute z-20 top-1/2 -translate-y-1/2
                       -left-3 sm:-left-6 lg:-left-10
                       w-10 h-10 sm:w-12 sm:h-12
                       rounded-full bg-white shadow-lg border border-gray-200
                       flex items-center justify-center
                       hover:shadow-xl transition
                       text-[#16A34A]" aria-label="Previous">
                <i data-lucide="chevron-left" class="w-6 h-6 sm:w-7 sm:h-7"></i>
            </button>

            <!-- RIGHT ARROW (hide when last) -->
            <button type="button" @click="next()" x-show="canNext" x-transition.opacity class="absolute z-20 top-1/2 -translate-y-1/2
                       -right-3 sm:-right-6 lg:-right-10
                       w-10 h-10 sm:w-12 sm:h-12
                       rounded-full bg-white shadow-lg border border-gray-200
                       flex items-center justify-center
                       hover:shadow-xl transition
                       text-[#16A34A]" aria-label="Next">
                <i data-lucide="chevron-right" class="w-6 h-6 sm:w-7 sm:h-7"></i>
            </button>

            <!-- TRACK -->
            <div class="overflow-hidden">
                <div class="flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-hide" x-ref="track"
                    @scroll.throttle.50ms="syncFromScroll()">

                    @php
                        $agencies = [
                            [
                                'name' => 'Global Workforce Solutions',
                                'jobs' => 145,
                                'description' => 'Leading recruitment agency specializing in healthcare and tech placements nationwide.',
                                'location' => 'New York, USA',
                                'email' => 'contact@globalworkforce.com',
                                'phone' => '+1 (555) 123-4567',
                                'industries' => ['Healthcare', 'IT', 'Manufacturing'],
                                'image' => 'images/1.jpg',
                            ],
                            [
                                'name' => 'Prime Talent Agency',
                                'jobs' => 98,
                                'description' => 'Trusted partner for construction and hospitality staffing solutions.',
                                'location' => 'Los Angeles, USA',
                                'email' => 'info@primetalent.com',
                                'phone' => '+1 (555) 234-5678',
                                'industries' => ['Construction', 'Hospitality'],
                                'image' => 'images/2.jpg',
                            ],
                            [
                                'name' => 'TechStaff Recruiters',
                                'jobs' => 210,
                                'description' => 'Premier IT recruitment agency connecting top tech talent with leading companies.',
                                'location' => 'San Francisco, USA',
                                'email' => 'hr@techstaff.com',
                                'phone' => '+1 (555) 345-6789',
                                'industries' => ['IT', 'Tech Support'],
                                'image' => 'images/3.jpg',
                            ],
                            // duplicates sample
                            [
                                'name' => 'Global Workforce Solutions',
                                'jobs' => 145,
                                'description' => 'Leading recruitment agency specializing in healthcare and tech placements nationwide.',
                                'location' => 'New York, USA',
                                'email' => 'contact@globalworkforce.com',
                                'phone' => '+1 (555) 123-4567',
                                'industries' => ['Healthcare', 'IT', 'Manufacturing'],
                                'image' => 'images/1.jpg',
                            ],
                            [
                                'name' => 'Prime Talent Agency',
                                'jobs' => 98,
                                'description' => 'Trusted partner for construction and hospitality staffing solutions.',
                                'location' => 'Los Angeles, USA',
                                'email' => 'info@primetalent.com',
                                'phone' => '+1 (555) 234-5678',
                                'industries' => ['Construction', 'Hospitality'],
                                'image' => 'images/2.jpg',
                            ],
                            [
                                'name' => 'TechStaff Recruiters',
                                'jobs' => 210,
                                'description' => 'Premier IT recruitment agency connecting top tech talent with leading companies.',
                                'location' => 'San Francisco, USA',
                                'email' => 'hr@techstaff.com',
                                'phone' => '+1 (555) 345-6789',
                                'industries' => ['IT', 'Tech Support'],
                                'image' => 'images/3.jpg',
                            ],
                        ];
                    @endphp

                    @foreach ($agencies as $agency)
                        <div class="flex-none w-80 sm:w-96 snap-center sm:snap-start
                                                       bg-white rounded-2xl shadow-md hover:shadow-2xl
                                                       transition-transform transform hover:-translate-y-1 p-4">
                            <!-- Top Row: Image + Name + Jobs -->
                            <div class="flex items-center mb-3">
                                <img src="{{ $agency['image'] }}" alt="{{ $agency['name'] }}"
                                    class="w-20 h-20 rounded-lg object-cover mr-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $agency['name'] }}</h3>
                                    <p class="text-green-700 font-medium">{{ $agency['jobs'] }} jobs available</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-3">{{ $agency['description'] }}</p>

                            <!-- Location, Email, Phone with Lucide icons -->
                            <div class="space-y-1 text-gray-500 text-sm mb-3">
                                <p class="flex items-center gap-2">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                    {{ $agency['location'] }}
                                </p>
                                <p class="flex items-center gap-2">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                    {{ $agency['email'] }}
                                </p>
                                <p class="flex items-center gap-2">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                    {{ $agency['phone'] }}
                                </p>
                            </div>

                            <!-- Industries -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($agency['industries'] as $industry)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $industry }}
                                    </span>
                                @endforeach
                            </div>

                            <!-- Buttons -->
                            <div class="flex justify-between items-center">
                                <a href="#" class="text-white bg-[#16A34A] px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition
                                                              text-center flex-1 text-sm mr-2">
                                    View Profile
                                </a>
                                <button class="text-gray-500 hover:text-gray-700 transition text-2xl">
                                    <i data-lucide="bookmark" class="w-6 h-6"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination Dots (by pages) -->
            <div class="flex justify-center mt-6 space-x-2">
                <template x-for="i in totalPages" :key="i">
                    <button type="button" @click="goToPage(i-1)"
                        class="w-3 h-3 rounded-full transition border border-gray-300" :style="page === (i-1)
                ? 'background-color:#16A34A; border-color:#16A34A;'
                : 'background-color:#D1D5DB; border-color:#D1D5DB;'" aria-label="Go to page"></button>
                </template>
            </div>


        </div>
    </div>

    <!-- Alpine Slider Logic -->
    <script>
        function agencySlider() {
            return {
                total: {{ count($agencies) }},

                cardWidth: 0,
                perPage: 1,

                page: 0,          // current page index (0..totalPages-1)
                totalPages: 1,    // dots count
                canPrev: false,
                canNext: true,

                init() {
                    this.$nextTick(() => {
                        this.measureAndUpdate(true);

                        window.addEventListener('resize', () => {
                            const oldPage = this.page;
                            this.measureAndUpdate(false);
                            this.goToPage(Math.min(oldPage, this.totalPages - 1));
                        });
                    });
                },

                measureAndUpdate(centerOnMobile = false) {
                    const track = this.$refs.track;
                    const firstCard = track?.children?.[0];
                    if (!track || !firstCard) return;

                    const gap = 16; // gap-4
                    this.cardWidth = firstCard.offsetWidth + gap;

                    // how many cards fit
                    this.perPage = Math.max(1, Math.floor((track.clientWidth + gap) / this.cardWidth));

                    // total pages (dots)
                    this.totalPages = Math.max(1, Math.ceil(this.total / this.perPage));

                    // ✅ ONLY MOBILE (below sm)
                    const isMobile = window.innerWidth < 640;

                    if (isMobile) {
                        const pad = Math.max(16, Math.floor((track.clientWidth - firstCard.offsetWidth) / 2));
                        track.style.paddingLeft = pad + "px";
                        track.style.paddingRight = pad + "px";
                        track.style.scrollPaddingLeft = pad + "px";
                        track.style.scrollPaddingRight = pad + "px";
                    } else {
                        track.style.paddingLeft = "";
                        track.style.paddingRight = "";
                        track.style.scrollPaddingLeft = "";
                        track.style.scrollPaddingRight = "";
                    }

                    this.updateButtons();

                    if (centerOnMobile && isMobile) {
                        this.goToPage(0);
                    }
                },


                updateButtons() {
                    this.canPrev = this.page > 0;
                    this.canNext = this.page < (this.totalPages - 1);
                },

                goToPage(p) {
                    const track = this.$refs.track;
                    if (!track) return;

                    this.page = Math.max(0, Math.min(p, this.totalPages - 1));

                    // scroll by "page width" (perPage cards)
                    const pageSizePx = this.cardWidth * this.perPage;

                    track.scrollTo({
                        left: this.page * pageSizePx,
                        behavior: 'smooth'
                    });

                    this.updateButtons();
                },

                next() {
                    if (!this.canNext) return;
                    this.goToPage(this.page + 1);
                },

                prev() {
                    if (!this.canPrev) return;
                    this.goToPage(this.page - 1);
                },

                syncFromScroll() {
                    const track = this.$refs.track;
                    if (!track || !this.cardWidth) return;

                    const pageSizePx = this.cardWidth * this.perPage;
                    const rawPage = Math.round(track.scrollLeft / pageSizePx);

                    this.page = Math.max(0, Math.min(rawPage, this.totalPages - 1));
                    this.updateButtons();
                }
            }
        }
    </script>


    <style>
        /* hide scrollbar (optional) */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</section>