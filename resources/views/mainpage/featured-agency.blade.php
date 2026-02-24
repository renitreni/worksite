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

        @php
            $totalAgencies = $featuredAgencies->count();
        @endphp

        @if ($totalAgencies === 0)
            <div class="max-w-2xl mx-auto bg-white border border-gray-200 rounded-2xl p-8 text-center">
                <p class="text-gray-700 font-medium">No featured agencies yet.</p>
                <p class="text-gray-500 text-sm mt-1">Once agencies are approved and have open jobs, they will appear here.</p>
            </div>
        @else
            <!-- Carousel Wrapper -->
            <div x-data="carousel({{ $totalAgencies }})" class="relative">

                <!-- LEFT ARROW -->
                <button type="button" @click="prev()" x-show="showArrows" x-transition.opacity
                    class="absolute z-20 top-1/2 -translate-y-1/2
                           -left-3 sm:-left-6 lg:-left-10
                           w-10 h-10 sm:w-12 sm:h-12
                           rounded-full bg-white shadow-lg border border-gray-200
                           flex items-center justify-center
                           hover:shadow-xl transition
                           text-[#16A34A]"
                    aria-label="Previous">
                    <i data-lucide="chevron-left" class="w-6 h-6 sm:w-7 sm:h-7"></i>
                </button>

                <!-- RIGHT ARROW -->
                <button type="button" @click="next()" x-show="showArrows" x-transition.opacity
                    class="absolute z-20 top-1/2 -translate-y-1/2
                           -right-3 sm:-right-6 lg:-right-10
                           w-10 h-10 sm:w-12 sm:h-12
                           rounded-full bg-white shadow-lg border border-gray-200
                           flex items-center justify-center
                           hover:shadow-xl transition
                           text-[#16A34A]"
                    aria-label="Next">
                    <i data-lucide="chevron-right" class="w-6 h-6 sm:w-7 sm:h-7"></i>
                </button>

                <div @mouseenter="pause=true" @mouseleave="pause=false"
                    class="flex overflow-x-hidden space-x-5 scrollbar-hide" x-ref="carousel">

                    @foreach ($featuredAgencies as $agency)
                        <x-agency-card :agency="$agency" />
                    @endforeach

                </div>
            </div>
        @endif
    </div>

    <!-- Alpine.js Carousel -->
    <script>
        function carousel(total) {
            return {
                total,
                showArrows: total > 4,

                pause: false,
                speed: 0.6,

                rafId: null,
                cardWidth: 0,
                halfWidth: 0,
                el: null,

                init() {
                    this.el = this.$refs.carousel;

                    // 0-1 cards: no duplication / no animation
                    if (this.total <= 3) {
                        this.showArrows = false;
                        this.$nextTick(() => {
                            if (window.lucide) lucide.createIcons();
                        });
                        return;
                    }

                    // Duplicate safely
                    if (!this.el.dataset.duplicated) {
                        const children = Array.from(this.el.children);
                        children.forEach(node => this.el.appendChild(node.cloneNode(true)));
                        this.el.dataset.duplicated = "1";
                    }

                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();

                        const gap = 20; // space-x-5
                        this.cardWidth = this.el.children[0].offsetWidth + gap;
                        this.halfWidth = this.el.scrollWidth / 2;

                        const animate = () => {
                            if (!this.pause) {
                                this.el.scrollLeft += this.speed;
                                if (this.el.scrollLeft >= this.halfWidth) {
                                    this.el.scrollLeft -= this.halfWidth;
                                }
                            }
                            this.rafId = requestAnimationFrame(animate);
                        };

                        this.rafId = requestAnimationFrame(animate);
                    });
                },

                normalize() {
                    if (!this.halfWidth) return;
                    if (this.el.scrollLeft < 0) this.el.scrollLeft += this.halfWidth;
                    if (this.el.scrollLeft >= this.halfWidth) this.el.scrollLeft -= this.halfWidth;
                },

                snapToNearest() {
                    if (!this.cardWidth) return;
                    this.normalize();
                    const target = Math.round(this.el.scrollLeft / this.cardWidth) * this.cardWidth;
                    this.el.scrollTo({ left: target, behavior: "smooth" });
                },

                next() {
                    if (!this.showArrows) return;
                    this.pause = true;
                    this.normalize();
                    this.el.scrollTo({ left: this.el.scrollLeft + this.cardWidth, behavior: "smooth" });
                    setTimeout(() => { this.snapToNearest(); this.pause = false; }, 350);
                },

                prev() {
                    if (!this.showArrows) return;
                    this.pause = true;
                    this.normalize();
                    this.el.scrollTo({ left: this.el.scrollLeft - this.cardWidth, behavior: "smooth" });
                    setTimeout(() => { this.snapToNearest(); this.pause = false; }, 350);
                },

                destroy() {
                    if (this.rafId) cancelAnimationFrame(this.rafId);
                }
            }
        }
    </script>
</section>