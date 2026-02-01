<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Browse by Industry
            </h2>
            <p class="text-gray-600 mt-3 max-w-xl mx-auto">
                Explore the most in-demand industries and find verified job opportunities that match your skills and career goals.
            </p>
        </div>

        @php
            $industries = [
                ['image' => 'healthcare.jpg', 'name' => 'Healthcare', 'jobs' => 234],
                ['image' => 'technology.jpg', 'name' => 'Technology', 'jobs' => 456],
                ['image' => 'construction.jpg', 'name' => 'Construction', 'jobs' => 189],
                ['image' => 'hospitality.jpg', 'name' => 'Hospitality', 'jobs' => 312],
                ['image' => 'engineering.jpg', 'name' => 'Engineering', 'jobs' => 267],
                ['image' => 'education.jpg', 'name' => 'Education', 'jobs' => 145],
                ['image' => 'manufacturing.jpg', 'name' => 'Manufacturing', 'jobs' => 198],
                ['image' => 'finance.jpg', 'name' => 'Finance', 'jobs' => 320],
            ];
        @endphp

        <!-- Industry Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($industries as $industry)
                <a href="#"
                   class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg
                          transition-all duration-300 hover:-translate-y-1 overflow-hidden">

                    <!-- Image -->
                    <div class="h-36 sm:h-40 w-full overflow-hidden">
                        <img
                            src="{{ asset('images/' . $industry['image']) }}"
                            alt="{{ $industry['name'] }}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                        >
                    </div>

                    <!-- Content -->
                    <div class="p-5 text-center">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-[#16A34A] transition-colors">
                            {{ $industry['name'] }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $industry['jobs'] }} jobs available
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>
