<section class="py-16 bg-white">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Featured Jobs</h2>
            <p class="text-gray-600 mt-2">Top opportunities from leading employers</p>
        </div>

        @php
            // ✅ Updated categories to your 10 specializations
            $featuredJobs = [
                [
                    'title' => 'Domestic Helper',
                    'company' => 'Golden Home Staffing',
                    'category' => 'Domestic',
                    'salary' => '$450 - $650/month',
                    'location' => 'Riyadh, Saudi Arabia',
                    'vacancies' => '30 vacancies',
                    'posted' => 'Posted 2 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Caregiver',
                    'company' => 'Golden Heart Care Home',
                    'category' => 'Caregiver',
                    'salary' => '$2,800 - $3,600/month',
                    'location' => 'Toronto, Canada',
                    'vacancies' => '9 vacancies',
                    'posted' => 'Posted 3 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Construction Laborer',
                    'company' => 'Qatar Construction Group',
                    'category' => 'Construction',
                    'salary' => '$700 - $1,000/month',
                    'location' => 'Doha, Qatar',
                    'vacancies' => '18 vacancies',
                    'posted' => 'Posted 3 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Factory Worker',
                    'company' => 'Sunrise Manufacturing Co.',
                    'category' => 'Factory',
                    'salary' => '$1,900 - $2,600/month',
                    'location' => 'Osaka, Japan',
                    'vacancies' => '20 vacancies',
                    'posted' => 'Posted 6 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Delivery Driver',
                    'company' => 'FastTrack Logistics',
                    'category' => 'Driver',
                    'salary' => '$1,200 - $1,700/month',
                    'location' => 'Dubai, UAE',
                    'vacancies' => '12 vacancies',
                    'posted' => 'Posted 1 day ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Hotel Front Desk',
                    'company' => 'Marina Bay Hotels',
                    'category' => 'Hospitality',
                    'salary' => '$1,800 - $2,300/month',
                    'location' => 'Dubai, UAE',
                    'vacancies' => '6 vacancies',
                    'posted' => 'Posted 4 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Barista',
                    'company' => 'BrewCraft Café',
                    'category' => 'Food',
                    'salary' => '$900 - $1,300/month',
                    'location' => 'Kuwait City, Kuwait',
                    'vacancies' => '10 vacancies',
                    'posted' => 'Posted 2 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Administrative Assistant',
                    'company' => 'Prime Office Solutions',
                    'category' => 'Admin',
                    'salary' => '$1,400 - $1,900/month',
                    'location' => 'Singapore, Singapore',
                    'vacancies' => '5 vacancies',
                    'posted' => 'Posted 5 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Hair Stylist',
                    'company' => 'Glow Beauty Lounge',
                    'category' => 'Beauty',
                    'salary' => '$1,300 - $1,900/month',
                    'location' => 'Doha, Qatar',
                    'vacancies' => '7 vacancies',
                    'posted' => 'Posted 4 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Seafarer (Deck Crew)',
                    'company' => 'OceanLine Maritime',
                    'category' => 'Maritime',
                    'salary' => '$1,800 - $2,800/month',
                    'location' => 'International Waters',
                    'vacancies' => '15 vacancies',
                    'posted' => 'Posted 1 week ago',
                    'badge' => 'Featured',
                ],
            ];
        @endphp

        {{-- ✅ MOBILE: show 6, LARGE: show 9 --}}
        {{-- We duplicate slices with responsive visibility to keep it simple (frontend-only). --}}

        {{-- MOBILE / TABLET (up to md): 6 cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 lg:hidden">
            @foreach (array_slice($featuredJobs, 0, 6) as $job)
                <div class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm
                       hover:shadow-xl hover:-translate-y-2 hover:border-[#16A34A]/40
                       transition-all duration-300">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-[#16A34A] transition-colors">
                                {{ $job['title'] }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $job['company'] }}</p>
                        </div>
                        <button class="text-gray-400 group-hover:text-[#16A34A]
                   group-hover:scale-110 transition-all duration-300">

                            <i data-lucide="bookmark" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="mt-4">
                        <span
                            class="inline-flex items-center rounded-full bg-green-100 text-green-800 px-3 py-1 text-xs font-semibold">
                            {{ $job['category'] }}
                        </span>
                    </div>

                    <div class="mt-5 space-y-3 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="wallet" class="w-4 h-4 text-gray-400"></i>
                            <span class="font-semibold text-gray-900">{{ $job['salary'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                            <span>{{ $job['location'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                            <span>{{ $job['vacancies'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                            <span>{{ $job['posted'] }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="#"
                            class="w-full inline-flex items-center justify-center rounded-xl bg-[#16A34A] px-4 py-3 text-sm font-semibold text-white hover:bg-green-700 transition">
                            Apply Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- LARGE (lg and above): 9 cards --}}
        <div class="hidden lg:grid grid-cols-3 gap-6">
            @foreach (array_slice($featuredJobs, 0, 9) as $job)
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $job['title'] }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $job['company'] }}</p>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 transition">
                            <i data-lucide="bookmark" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="mt-4">
                        <span
                            class="inline-flex items-center rounded-full bg-green-100 text-green-800 px-3 py-1 text-xs font-semibold">
                            {{ $job['category'] }}
                        </span>
                    </div>

                    <div class="mt-5 space-y-3 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="wallet" class="w-4 h-4 text-gray-400"></i>
                            <span class="font-semibold text-gray-900">{{ $job['salary'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                            <span>{{ $job['location'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                            <span>{{ $job['vacancies'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                            <span>{{ $job['posted'] }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="#"
                            class="w-full inline-flex items-center justify-center rounded-xl bg-[#16A34A] px-4 py-3 text-sm font-semibold text-white hover:bg-green-700 transition">
                            Apply Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- View all button --}}
        <div class="mt-10 flex justify-center">
            <a href="#"
                class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-900 hover:bg-gray-50 transition">
                View all jobs
                <i data-lucide="arrow-right" class="w-4 h-4 ml-2 text-gray-600"></i>
            </a>
        </div>
    </div>
</section>