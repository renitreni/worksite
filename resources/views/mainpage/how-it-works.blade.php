<section class="pt-24 bg-gray-50">

    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-16">
            <h2 class="section-title text-3xl md:text-4xl font-bold text-gray-900">
                How JobAbroad Works
            </h2>
            <p class="text-gray-600 mt-3 text-sm sm:text-base leading-relaxed">
                Start your journey to working abroad in four simple steps.
            </p>
        </div>

        @php
            $steps = [
                [
                    'num' => 1,
                    'title' => 'Register',
                    'desc' => 'Create your free JobAbroad account in just a few minutes.',
                    'image' => 'images/how-it-works/1.jpg',
                ],
                [
                    'num' => 2,
                    'title' => 'Complete Profile',
                    'desc' => 'Upload your resume and highlight your experience and skills.',
                    'image' => 'images/how-it-works/2.jpg',
                ],
                [
                    'num' => 3,
                    'title' => 'Apply for Jobs',
                    'desc' => 'Browse verified overseas job listings and submit applications.',
                    'image' => 'images/how-it-works/3.jpg',
                ],
                [
                    'num' => 4,
                    'title' => 'Get Hired',
                    'desc' => 'Attend interviews and secure your overseas opportunity.',
                    'image' => 'images/how-it-works/4.jpg',
                ],
            ];
        @endphp

        <div class="relative">

            {{-- Progress Line --}}
            <div class="hidden lg:block absolute top-28 left-0 right-0 h-1 bg-gray-200"></div>

            {{-- DESKTOP --}}
            <div class="hidden lg:grid grid-cols-4 gap-10 relative">
                @foreach ($steps as $step)
                    <div class="relative group">

                        <div
                            class="relative bg-white rounded-2xl shadow-sm border border-gray-200 pt-12 p-6 text-center
                            transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                            <div
                                class="absolute -top-6 left-1/2 -translate-x-1/2 w-14 h-14 flex items-center justify-center bg-[#16A34A] text-white font-bold text-lg rounded-full shadow-lg ring-4 ring-white">
                                {{ $step['num'] }}
                            </div>

                            <img src="{{ asset($step['image']) }}" class="h-32 mx-auto object-contain mb-5 rounded-lg">

                            <h3 class="section-title text-lg font-semibold text-gray-900">
                                {{ $step['title'] }}
                            </h3>

                            <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                                {{ $step['desc'] }}
                            </p>

                        </div>

                    </div>
                @endforeach
            </div>

            {{-- MOBILE SWIPE (1 CARD ONLY, NO ARROWS) --}}
            <div class="lg:hidden">

                <div id="stepsSlider" class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth">

                    @foreach ($steps as $step)
                        <div class="w-full snap-center shrink-0 px-4">

                            {{-- spacing for floating number --}}
                            <div class="pt-6 pb-4">

                                <div
                                    class="relative bg-white rounded-2xl shadow-sm border border-gray-200 pt-12 p-6 text-center">

                                    {{-- FLOATING NUMBER --}}
                                    <div
                                        class="absolute -top-6 left-1/2 -translate-x-1/2
                            w-14 h-14 flex items-center justify-center
                            bg-[#16A34A] text-white font-bold text-lg
                            rounded-full shadow-lg ring-4 ring-white z-20">

                                        {{ $step['num'] }}

                                    </div>

                                    <img src="{{ asset($step['image']) }}"
                                        class="h-32 mx-auto object-contain mb-5 rounded-lg">

                                    <h3 class="section-title text-lg font-semibold text-gray-900">
                                        {{ $step['title'] }}
                                    </h3>

                                    <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                                        {{ $step['desc'] }}
                                    </p>

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

                {{-- DOT INDICATOR --}}
                <div class="flex justify-center mt-2 gap-2" id="stepDots">
                    @foreach ($steps as $i => $step)
                        <span class="step-dot w-2.5 h-2.5 bg-gray-300 rounded-full"></span>
                    @endforeach
                </div>

            </div>

        </div>

    </div>

</section>

<section class="py-10 bg-gray-50">

    <div class="max-w-7xl mx-auto px-6">

        {{-- DESKTOP (UNCHANGED) --}}
        <div class="hidden lg:grid grid-cols-3 gap-8">

            <div class="relative lg:col-span-2 group overflow-hidden rounded-3xl shadow-2xl">
                <video controls class="w-full h-full object-cover rounded-3xl">
                    <source src="{{ asset('videos/ads/STORY.mp4') }}">
                </video>
            </div>

            <div class="grid grid-rows-2 gap-8">

                <video autoplay muted loop class="rounded-3xl w-full h-full object-cover">
                    <source src="{{ asset('videos/ads/ADS-1.mp4') }}">
                </video>

                <video autoplay muted loop class="rounded-3xl w-full h-full object-cover">
                    <source src="{{ asset('videos/ads/ADS-2.mp4') }}">
                </video>

            </div>

        </div>

        {{-- MOBILE --}}
        <div class="lg:hidden relative group">

            {{-- ARROWS (HIDDEN UNTIL HOVER) --}}
            <button onclick="prevSlide()"
                class="absolute left-3 top-1/2 -translate-y-1/2 z-20
        w-10 h-10 flex items-center justify-center
        rounded-full
        bg-white/20 backdrop-blur-md
        border border-white/30
        text-white text-xl
        shadow-lg
        transition duration-300
        opacity-0 group-hover:opacity-100
        hover:bg-white/30 hover:scale-110 active:scale-95">

                ‹
            </button>

            <button onclick="nextSlide()"
                class="absolute right-3 top-1/2 -translate-y-1/2 z-20
        w-10 h-10 flex items-center justify-center
        rounded-full
        bg-white/20 backdrop-blur-md
        border border-white/30
        text-white text-xl
        shadow-lg
        transition duration-300
        opacity-0 group-hover:opacity-100
        hover:bg-white/30 hover:scale-110 active:scale-95">

                ›
            </button>

            {{-- SLIDER --}}
            <div id="videoSlider" class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth">

                <div class="min-w-full snap-center">
                    <video controls class="w-full h-64 object-cover rounded-2xl">
                        <source src="{{ asset('videos/ads/STORY.mp4') }}">
                    </video>
                </div>

                <div class="min-w-full snap-center">
                    <video autoplay muted loop class="w-full h-64 object-cover rounded-2xl">
                        <source src="{{ asset('videos/ads/ADS-1.mp4') }}">
                    </video>
                </div>

                <div class="min-w-full snap-center">
                    <video autoplay muted loop class="w-full h-64 object-cover rounded-2xl">
                        <source src="{{ asset('videos/ads/ADS-2.mp4') }}">
                    </video>
                </div>

            </div>

            {{-- DOTS --}}
            <div class="flex justify-center mt-4 gap-2" id="dots">
                <span class="dot w-2.5 h-2.5 bg-gray-300 rounded-full"></span>
                <span class="dot w-2.5 h-2.5 bg-gray-300 rounded-full"></span>
                <span class="dot w-2.5 h-2.5 bg-gray-300 rounded-full"></span>
            </div>

        </div>

    </div>

</section>

<script>
    const slider = document.getElementById('videoSlider');
    const dots = document.querySelectorAll('#dots .dot');

    let currentIndex = 0;

    function updateDots() {
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-green-600', i === currentIndex);
            dot.classList.toggle('bg-gray-300', i !== currentIndex);
        });
    }

    function nextSlide() {
        if (currentIndex < dots.length - 1) {
            currentIndex++;
            slider.scrollTo({
                left: slider.clientWidth * currentIndex,
                behavior: 'smooth'
            });
            updateDots();
        }
    }

    function prevSlide() {
        if (currentIndex > 0) {
            currentIndex--;
            slider.scrollTo({
                left: slider.clientWidth * currentIndex,
                behavior: 'smooth'
            });
            updateDots();
        }
    }

    slider.addEventListener('scroll', () => {
        currentIndex = Math.round(slider.scrollLeft / slider.clientWidth);
        updateDots();
    });

    // disable right click
    document.querySelectorAll("video").forEach(video => {
        video.addEventListener("contextmenu", e => e.preventDefault());
    });
</script>
<script>
    const stepSlider = document.getElementById('stepsSlider');
    const stepDots = document.querySelectorAll('#stepDots .step-dot');

    if (stepSlider) {
        stepSlider.addEventListener('scroll', () => {
            const index = Math.round(stepSlider.scrollLeft / stepSlider.clientWidth);

            stepDots.forEach((dot, i) => {
                dot.classList.toggle('bg-green-600', i === index);
                dot.classList.toggle('bg-gray-300', i !== index);
            });
        });
    }
</script>
