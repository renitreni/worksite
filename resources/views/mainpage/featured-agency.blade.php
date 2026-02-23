<section class="py-16">
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

        <!-- Carousel Wrapper -->
        <div x-data="carousel()" class="relative">

            <!-- LEFT ARROW -->
            <button type="button" @click="prev()" x-show="canPrev" x-transition.opacity class="absolute z-20 top-1/2 -translate-y-1/2
           -left-3 sm:-left-6 lg:-left-10
           w-10 h-10 sm:w-12 sm:h-12
           rounded-full bg-white shadow-lg border border-gray-200
           flex items-center justify-center
           hover:shadow-xl transition
           text-[#16A34A]" aria-label="Previous">
                <i data-lucide="chevron-left" class="w-6 h-6 sm:w-7 sm:h-7"></i>
            </button>

            <!-- RIGHT ARROW -->
            <button type="button" @click="next()" x-show="canNext" x-transition.opacity class="absolute z-20 top-1/2 -translate-y-1/2
           -right-3 sm:-right-6 lg:-right-10
           w-10 h-10 sm:w-12 sm:h-12
           rounded-full bg-white shadow-lg border border-gray-200
           flex items-center justify-center
           hover:shadow-xl transition
           text-[#16A34A]" aria-label="Next">
                <i data-lucide="chevron-right" class="w-6 h-6 sm:w-7 sm:h-7"></i>
            </button>
            <div @mouseenter="pause=true" @mouseleave="pause=false"
                class="flex overflow-x-hidden space-x-4 scrollbar-hide" x-ref="carousel">



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
                    <div @mouseenter="pause=true" @mouseleave="pause=false"
                        class="flex-none w-96 bg-white rounded-2xl shadow-md hover:shadow-2xl transition-transform transform hover:-translate-y-1 p-4">
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
                                <span
                                    class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">{{ $industry }}</span>
                            @endforeach
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-between items-center">
                            <a href="{{ route('agency.details') }}"
                                class="text-white bg-[#16A34A] px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition text-center flex-1 text-sm mr-2">
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
    </div>

    <!-- Alpine.js Carousel -->
    <script>
        function carousel() {
            return {
                current: 0,
                total: {{ count($agencies) }},

                pause: false,
                speed: 0.6,

                rafId: null,
                cardWidth: 0,
                halfWidth: 0,
                el: null,

                
                canPrev: true,
                canNext: true,

                init() {
                    this.el = this.$refs.carousel;

                   
                    if (!this.el.dataset.duplicated) {
                        this.el.innerHTML += this.el.innerHTML;
                        this.el.dataset.duplicated = "1";
                    }

                    this.$nextTick(() => {
                        const gap = 16; // space-x-4
                        this.cardWidth = this.el.children[0].offsetWidth + gap;
                        this.halfWidth = this.el.scrollWidth / 2;

                        const animate = () => {
                            if (!this.pause) {
                                this.el.scrollLeft += this.speed;

                                if (this.el.scrollLeft >= this.halfWidth) {
                                    this.el.scrollLeft -= this.halfWidth;
                                }

                                this.current =
                                    Math.floor(this.el.scrollLeft / this.cardWidth) % this.total;
                            }

                            this.rafId = requestAnimationFrame(animate);
                        };

                        this.rafId = requestAnimationFrame(animate);
                    });
                },

                normalize() {
                    if (this.el.scrollLeft < 0)
                        this.el.scrollLeft += this.halfWidth;

                    if (this.el.scrollLeft >= this.halfWidth)
                        this.el.scrollLeft -= this.halfWidth;
                },

                snapToNearest() {
                    this.normalize();

                    const target =
                        Math.round(this.el.scrollLeft / this.cardWidth) *
                        this.cardWidth;

                    this.el.scrollTo({
                        left: target,
                        behavior: "smooth"
                    });

                    this.current =
                        (Math.round(target / this.cardWidth) % this.total + this.total) %
                        this.total;
                },

                next() {
                    this.pause = true; 
                    this.normalize();

                    this.el.scrollTo({
                        left: this.el.scrollLeft + this.cardWidth,
                        behavior: "smooth"
                    });

                    setTimeout(() => this.snapToNearest(), 300);
                },

                prev() {
                    this.pause = true; 
                    this.normalize();

                    this.el.scrollTo({
                        left: this.el.scrollLeft - this.cardWidth,
                        behavior: "smooth"
                    });

                    setTimeout(() => this.snapToNearest(), 300);
                },

                destroy() {
                    if (this.rafId) cancelAnimationFrame(this.rafId);
                }
            }
        }
    </script>


</section>