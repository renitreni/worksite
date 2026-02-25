<section class="py-16">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Featured Agencies</h2>
            <p class="text-gray-600 mt-3 max-w-xl mx-auto">
                Connect with trusted recruitment agencies hiring now
            </p>
        </div>

        @php $totalAgencies = $featuredAgencies->count(); @endphp

        @if ($totalAgencies === 0)
            <div class="max-w-2xl mx-auto bg-white border border-gray-200 rounded-2xl p-8 text-center">
                <p class="text-gray-700 font-medium">No featured agencies yet.</p>
                <p class="text-gray-500 text-sm mt-1">
                    Once agencies are approved and have open jobs, they will appear here.
                </p>
            </div>
        @else
            <!-- Carousel Wrapper -->
            <div x-data="carousel({{ $totalAgencies }})" x-init="init()" class="relative">

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

                <!-- TRACK -->
                <div x-ref="carousel" @mouseenter="pause=true" @mouseleave="pause=false"
                    class="flex overflow-x-hidden space-x-5 scrollbar-hide snap-x snap-mandatory">
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

                pause: false,
                speed: 0.6,

                rafId: null,
                cardWidth: 0,
                halfWidth: 0,
                el: null,

                perView: 4,
                showArrows: false,
                canLoop: false,

                originalCount: 0,
                onResize: null,

                init() {
                    this.el = this.$refs.carousel;
                    this.originalCount = this.el.children.length;

                    this.updateMode();

                    this.onResize = () => {
                        const prevCanLoop = this.canLoop;
                        this.updateMode();

                        if (prevCanLoop !== this.canLoop) {
                            this.syncClones();
                            this.measure();

                            if (this.canLoop) this.start();
                            else {
                                this.stop();
                                this.el.scrollLeft = 0;
                            }
                        } else {
                            this.measure();
                        }
                    };

                    window.addEventListener("resize", this.onResize);

                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                        this.syncClones();
                        this.measure();
                        if (this.canLoop) this.start(); // ✅ only start if should loop
                        else this.stop(); // ✅ ensure stopped
                    });
                },

                // ✅ single source of truth
                updateMode() {
                    const w = window.innerWidth;

                    // thresholds (your requested behavior)
                    // mobile: 1 card fits, so loop when total > 1
                    // tablet: 3 cards fits, so loop when total > 3
                    // desktop: 4 cards fits, so loop when total > 4
                    let threshold = 4;

                    if (w < 640) threshold = 1; // mobile
                    else if (w < 1024) threshold = 3; // tablet
                    else threshold = 4; // desktop

                    this.canLoop = this.total > threshold;
                    this.showArrows = this.canLoop;

                    if (!this.canLoop) this.stop();
                },

                syncClones() {
                    if (!this.el) return;

                    // remove clones
                    while (this.el.children.length > this.originalCount) {
                        this.el.removeChild(this.el.lastElementChild);
                    }

                    // add clones only when looping
                    if (this.canLoop) {
                        const originals = Array.from(this.el.children).slice(0, this.originalCount);
                        originals.forEach(node => this.el.appendChild(node.cloneNode(true)));
                    }
                },

                measure() {
                    if (!this.el || this.el.children.length === 0) return;
                    const first = this.el.children[0];
                    const styles = window.getComputedStyle(this.el);
                    const gap = parseFloat(styles.columnGap || styles.gap || 20);

                    this.cardWidth = first.offsetWidth + gap;
                    this.halfWidth = this.el.scrollWidth / 2;
                },

                start() {
                    if (!this.canLoop || this.rafId) return;

                    const animate = () => {
                        if (this.canLoop && !this.pause) {
                            this.el.scrollLeft += this.speed;
                            if (this.el.scrollLeft >= this.halfWidth) {
                                this.el.scrollLeft -= this.halfWidth;
                            }
                        }
                        this.rafId = requestAnimationFrame(animate);
                    };

                    this.rafId = requestAnimationFrame(animate);
                },

                stop() {
                    if (this.rafId) cancelAnimationFrame(this.rafId);
                    this.rafId = null;
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
                    this.el.scrollTo({
                        left: target,
                        behavior: "smooth"
                    });
                },

                next() {
                    if (!this.showArrows) return;
                    this.pause = true;
                    this.normalize();
                    this.el.scrollTo({
                        left: this.el.scrollLeft + this.cardWidth,
                        behavior: "smooth"
                    });
                    setTimeout(() => {
                        this.snapToNearest();
                        this.pause = false;
                    }, 350);
                },

                prev() {
                    if (!this.showArrows) return;
                    this.pause = true;
                    this.normalize();
                    this.el.scrollTo({
                        left: this.el.scrollLeft - this.cardWidth,
                        behavior: "smooth"
                    });
                    setTimeout(() => {
                        this.snapToNearest();
                        this.pause = false;
                    }, 350);
                },

                destroy() {
                    this.stop();
                    if (this.onResize) window.removeEventListener("resize", this.onResize);
                }
            }
        }
    </script>
</section>
