<section class="py-16 bg-white ">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Browse by Industry
            </h2>
            <p class="text-gray-600 mt-3 max-w-xl mx-auto">
                Explore the most in-demand industries and find verified job opportunities that match your skills and career goals.
            </p>
        </div>

        <!-- Industry Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @php
                $industries = [
                    ['icon' => '🏥', 'name' => 'Healthcare', 'jobs' => 234],
                    ['icon' => '💻', 'name' => 'Technology', 'jobs' => 456],
                    ['icon' => '🏗️', 'name' => 'Construction', 'jobs' => 189],
                    ['icon' => '🏨', 'name' => 'Hospitality', 'jobs' => 312],
                    ['icon' => '⚙️', 'name' => 'Engineering', 'jobs' => 267],
                    ['icon' => '📚', 'name' => 'Education', 'jobs' => 145],
                    ['icon' => '🏭', 'name' => 'Manufacturing', 'jobs' => 198],
                    ['icon' => '💰', 'name' => 'Finance', 'jobs' => 320],
                ];
            @endphp

            @foreach ($industries as $industry)
                <a href="#"
                   class="group flex flex-col items-center p-6 bg-white rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition-transform transform hover:-translate-y-1 cursor-pointer text-center">
                    <div class="text-4xl mb-4 transition-transform duration-300 group-hover:scale-110">
                        {{ $industry['icon'] }}
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2 transition-colors group-hover:text-[#16A34A]">
                        {{ $industry['name'] }}
                    </h3>
                    <p class="text-gray-500 font-medium">
                        {{ $industry['jobs'] }} jobs
                    </p>
                </a>
            @endforeach
        </div>
    </div>
</section>
