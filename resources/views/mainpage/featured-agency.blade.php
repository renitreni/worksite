<section class="py-16 bg-gray-50">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-14">

            <h2 class="section-title text-3xl md:text-4xl font-semibold text-gray-900">
                Featured Agencies
            </h2>

            <p class="mt-4 text-gray-600 text-sm sm:text-base leading-relaxed">
                Connect with trusted recruitment agencies actively hiring overseas workers.
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
                    <x-lucide-icon name="chevron-left" class="w-6 h-6 sm:w-7 sm:h-7" />
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
                    <x-lucide-icon name="chevron-right" class="w-6 h-6 sm:w-7 sm:h-7" />
                </button>

                <!-- TRACK -->
                <div x-ref="carousel" @mouseenter="pause = true" @mouseleave="pause = false"
                    class="flex gap-5
           overflow-x-auto sm:overflow-hidden
           snap-x sm:snap-none snap-mandatory
           px-4 sm:px-0
           scroll-smooth">

                    @foreach ($featuredAgencies as $agency)
                        <x-agency-card :agency="$agency" />
                    @endforeach

                </div>
            </div>
        @endif
    </div>
    <script>
        function carousel(total) {
            return {

                total,
                pause: false,
                speed: 0.5,

                rafId: null,
                cardWidth: 0,
                halfWidth: 0,
                el: null,

                showArrows: false,
                canLoop: false,
                isMobile: false,

                originalCount: 0,
                resumeTimer: null,

                init() {

                    this.el = this.$refs.carousel;
                    this.originalCount = this.el.children.length;

                    this.updateMode();

                    window.addEventListener("resize", () => {
                        this.updateMode();
                        this.syncClones();
                        this.measure();
                    });

                    this.$nextTick(() => {

                        if (window.lucide) lucide.createIcons();

                        this.syncClones();
                        this.measure();

                        if (this.canLoop && !this.isMobile) {
                            this.start();
                        }

                    });

                },

                updateMode() {

                    const w = window.innerWidth;

                    this.isMobile = w < 640;

                    let threshold = 3;

                    if (w < 640) threshold = 1;
                    else if (w < 1024) threshold = 2;
                    else threshold = 3;

                    this.canLoop = this.total > threshold;

                    this.showArrows = this.total > 1;

                },

                syncClones() {

                    if (!this.el) return;

                    while (this.el.children.length > this.originalCount) {
                        this.el.removeChild(this.el.lastElementChild);
                    }

                    if (this.canLoop && !this.isMobile) {

                        const originals = Array.from(this.el.children).slice(0, this.originalCount);

                        originals.forEach(node => {
                            this.el.appendChild(node.cloneNode(true));
                        });

                    }

                },

                measure() {

                    if (!this.el.children.length) return;

                    const first = this.el.children[0];
                    const gap = 20;

                    this.cardWidth = first.offsetWidth + gap;
                    this.halfWidth = this.el.scrollWidth / 2;

                },

                start() {

                    if (this.rafId || this.isMobile) return;

                    const animate = () => {

                        if (!this.pause && this.canLoop) {

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

                pauseAutoplay() {

                    if (this.isMobile) return;

                    this.pause = true;

                    clearTimeout(this.resumeTimer);

                    this.resumeTimer = setTimeout(() => {
                        this.pause = false;
                    }, 3000);

                },

                next() {

                    if (!this.showArrows) return;

                    this.pauseAutoplay();

                    const containerWidth = this.el.offsetWidth;
                    const target = this.el.scrollLeft + this.cardWidth;

                    this.el.scrollTo({
                        left: target - (containerWidth / 2 - this.cardWidth / 2),
                        behavior: "smooth"
                    });

                },

                prev() {

                    if (!this.showArrows) return;

                    this.pauseAutoplay();

                    const containerWidth = this.el.offsetWidth;
                    const target = this.el.scrollLeft - this.cardWidth;

                    this.el.scrollTo({
                        left: target - (containerWidth / 2 - this.cardWidth / 2),
                        behavior: "smooth"
                    });

                }
            }
        }
    </script>
</section>
