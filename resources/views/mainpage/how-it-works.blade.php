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

            {{-- DESKTOP (HORIZONTAL TIMELINE FIXED) --}}
            <div class="hidden lg:grid grid-cols-4 gap-10 relative items-start">

                {{-- CENTER LINE --}}
                <div class="absolute top-16 left-0 right-0 h-1 bg-gray-200 z-0"></div>

                @foreach ($steps as $step)
                    <div class="relative text-center z-10">

                        {{-- NUMBER (CONNECTED TO LINE) --}}
                        <div class="flex justify-center mb-6">
                            <div
                                class="w-14 h-14 flex items-center justify-center
                    bg-[#16A34A] text-white font-bold text-lg
                    rounded-full shadow-lg ring-4 ring-white">
                                {{ $step['num'] }}
                            </div>
                        </div>

                        {{-- CARD --}}
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6
                transition duration-300 hover:-translate-y-1 hover:shadow-lg">

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
            <div class="lg:hidden relative group">
                <button onclick="prevStep()"
                    class="absolute left-3 top-1/2 -translate-y-1/2 z-20
    w-10 h-10 flex items-center justify-center
    rounded-full
    bg-[#16A34A]/90 text-white text-xl
    shadow-lg
    transition duration-300
    opacity-0 group-hover:opacity-100
    hover:bg-[#15803D] hover:scale-110 active:scale-95">
                    ‹
                </button>

                <button onclick="nextStep()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 z-20
    w-10 h-10 flex items-center justify-center
    rounded-full
    bg-[#16A34A]/90 text-white text-xl
    shadow-lg
    transition duration-300
    opacity-0 group-hover:opacity-100
    hover:bg-[#15803D] hover:scale-110 active:scale-95">
                    ›
                </button>
                <div id="stepsSlider" class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar">
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


<script>
    const stepSlider = document.getElementById('stepsSlider');
    const stepDots = document.querySelectorAll('#stepDots .step-dot');

    let currentIndex = 0;

    function updateDots(index) {
        stepDots.forEach((dot, i) => {
            dot.classList.toggle('bg-green-600', i === index);
            dot.classList.toggle('bg-gray-300', i !== index);
        });
    }

    if (stepSlider) {

        const slides = stepSlider.children;
        const totalSlides = slides.length;

        // ✅ initial
        updateDots(currentIndex);

        // 🔥 BUTTON NAVIGATION (FIXED)
        window.nextStep = function() {
            if (currentIndex < totalSlides - 1) {
                currentIndex++;
                stepSlider.scrollTo({
                    left: stepSlider.clientWidth * currentIndex,
                    behavior: 'smooth'
                });
                updateDots(currentIndex); // ✅ FORCE UPDATE
            }
        }

        window.prevStep = function() {
            if (currentIndex > 0) {
                currentIndex--;
                stepSlider.scrollTo({
                    left: stepSlider.clientWidth * currentIndex,
                    behavior: 'smooth'
                });
                updateDots(currentIndex); // ✅ FORCE UPDATE
            }
        }

        // 🔥 SWIPE DETECTION (fallback sync)
        stepSlider.addEventListener('scroll', () => {
            const index = Math.round(stepSlider.scrollLeft / stepSlider.clientWidth);
            currentIndex = index;
            updateDots(currentIndex);
        });
    }
</script>
