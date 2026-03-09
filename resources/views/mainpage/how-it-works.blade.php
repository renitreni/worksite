<section class="py-24 bg-gray-50">

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
                    'video' => 'images/steps/register.mp4',
                ],
                [
                    'num' => 2,
                    'title' => 'Complete Profile',
                    'desc' => 'Upload your resume and highlight your experience and skills.',
                    'video' => 'images/steps/profile.mp4',
                ],
                [
                    'num' => 3,
                    'title' => 'Apply for Jobs',
                    'desc' => 'Browse verified overseas job listings and submit applications.',
                    'video' => 'images/steps/apply.mp4',
                ],
                [
                    'num' => 4,
                    'title' => 'Get Hired',
                    'desc' => 'Attend interviews and secure your overseas opportunity.',
                    'video' => 'images/steps/hired.mp4',
                ],
            ];
        @endphp


        <div class="relative">

            {{-- Progress Line --}}
            <div class="hidden lg:block absolute top-28 left-0 right-0 h-1 bg-gray-200"></div>

            {{-- Steps --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 relative">

                @foreach ($steps as $step)
                    <div class="relative group">

                        {{-- Card --}}
                        <div
                            class="relative bg-white rounded-2xl shadow-sm border border-gray-200 pt-12 p-6 text-center
                       transition duration-300 hover:-translate-y-1 hover:shadow-lg">

                            {{-- Floating Step Number --}}
                            <div
                                class="absolute -top-6 left-1/2 -translate-x-1/2
                           flex items-center justify-center
                           w-14 h-14 rounded-full
                           bg-[#16A34A] text-white font-bold text-lg
                           shadow-lg ring-4 ring-white">

                                {{ $step['num'] }}

                            </div>

                            {{-- Video --}}
                            <video autoplay loop muted playsinline class="h-32 mx-auto object-contain mb-5 rounded-lg">

                                <source src="{{ asset($step['video']) }}" type="video/mp4">

                            </video>

                            {{-- Title --}}
                            <h3 class="section-title text-lg font-semibold text-gray-900">
                                {{ $step['title'] }}
                            </h3>

                            {{-- Description --}}
                            <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                                {{ $step['desc'] }}
                            </p>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

        

    </div>

</section>
