<section class="py-10 bg-gray-50">

    <div class="max-w-7xl mx-auto px-6">

        {{-- DESKTOP --}}
        <div class="hidden lg:grid grid-cols-3 gap-8">

            <div class="relative lg:col-span-2 overflow-hidden rounded-3xl shadow-2xl">
                <video controls class="w-full h-full object-cover rounded-3xl">
                    <source src="{{ asset('videos/ads/STORY.mp4') }}">
                </video>
            </div>

            <div class="grid grid-rows-2 gap-8">

                <video autoplay muted loop playsinline preload="auto"
                    class="w-full h-64 object-cover rounded-2xl">
                    <source src="{{ asset('videos/ads/ADS-1.mp4') }}">
                </video>

                <video autoplay muted loop playsinline preload="auto"
                    class="w-full h-64 object-cover rounded-2xl">
                    <source src="{{ asset('videos/ads/ADS-2.mp4') }}">
                </video>

            </div>

        </div>

        {{-- MOBILE --}}
        <div class="lg:hidden relative group">

            <!-- BUTTONS -->
            <button onclick="prevSlide()"
                class="absolute left-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 flex items-center justify-center rounded-full bg-black/40 text-white text-xl shadow-lg transition opacity-0 group-hover:opacity-100">
                ‹
            </button>

            <button onclick="nextSlide()"
                class="absolute right-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 flex items-center justify-center rounded-full bg-black/40 text-white text-xl shadow-lg transition opacity-0 group-hover:opacity-100">
                ›
            </button>

            <!-- SLIDER -->
            <div id="videoSlider" class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth">

                <!-- STORY -->
                <div class="min-w-full snap-center">
                    <video controls class="w-full h-64 object-cover rounded-2xl">
                        <source src="{{ asset('videos/ads/STORY.mp4') }}">
                    </video>
                </div>

                <!-- ADS 1 -->
                <div class="min-w-full snap-center">
                    <video muted loop playsinline preload="auto"
                        class="w-full h-64 object-cover rounded-2xl">
                        <source src="{{ asset('videos/ads/ADS-1.mp4') }}">
                    </video>
                </div>

                <!-- ADS 2 -->
                <div class="min-w-full snap-center">
                    <video muted loop playsinline preload="auto"
                        class="w-full h-64 object-cover rounded-2xl">
                        <source src="{{ asset('videos/ads/ADS-2.mp4') }}">
                    </video>
                </div>

            </div>

            <!-- DOTS -->
            <div class="flex justify-center mt-4 gap-2" id="dots">
                <span class="dot w-2.5 h-2.5 bg-gray-300 rounded-full"></span>
                <span class="dot w-2.5 h-2.5 bg-gray-300 rounded-full"></span>
                <span class="dot w-2.5 h-2.5 bg-gray-300 rounded-full"></span>
            </div>

        </div>

    </div>

</section>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const slider = document.getElementById('videoSlider');
    const dots = document.querySelectorAll('#dots .dot');

    if (!slider) return;

    let currentIndex = 1;
    const total = dots.length;

    function updateDots() {
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-green-600', i === currentIndex);
            dot.classList.toggle('bg-gray-300', i !== currentIndex);
        });
    }

    function handleVideos() {
        const videos = slider.querySelectorAll('video');

        videos.forEach((video, i) => {
            if (i === currentIndex) {
                video.muted = true;
                video.play().catch(() => {});
            } else {
                video.pause();
                video.currentTime = 0;
            }
        });
    }

    function goTo(index) {
        currentIndex = index;

        slider.scrollTo({
            left: slider.offsetWidth * currentIndex,
            behavior: 'smooth'
        });

        updateDots();
        handleVideos();
    }

    window.nextSlide = function () {
        if (currentIndex < total - 1) goTo(currentIndex + 1);
    }

    window.prevSlide = function () {
        if (currentIndex > 0) goTo(currentIndex - 1);
    }

    slider.addEventListener('scroll', () => {
        const index = Math.round(slider.scrollLeft / slider.offsetWidth);

        if (index !== currentIndex) {
            currentIndex = index;
            updateDots();
            handleVideos();
        }
    });

    // ✅ INITIAL LOAD FIX (IMPORTANT)
    setTimeout(() => {
        goTo(1); // start at ADS-1
    }, 300);

});
</script>