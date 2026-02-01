<section class="py-16 bg-white">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Featured Jobs</h2>
            <p class="text-gray-600 mt-2">Top opportunities from leading employers</p>
        </div>

        @php
            $featuredJobs = [
                [
                    'title' => 'Registered Nurse',
                    'company' => 'Global Workforce Solutions',
                    'category' => 'Healthcare',
                    'salary' => '$65,000 - $85,000',
                    'location' => 'New York, USA',
                    'vacancies' => '15 vacancies',
                    'posted' => 'Posted 2 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Senior Software Engineer',
                    'company' => 'Tech Innovations Inc.',
                    'category' => 'Technology',
                    'salary' => '$6,000 - $8,500/month',
                    'location' => 'Singapore, Singapore',
                    'vacancies' => '8 vacancies',
                    'posted' => 'Posted 1 day ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Civil Engineer',
                    'company' => 'Qatar Construction Group',
                    'category' => 'Construction',
                    'salary' => '$4,200 - $6,000/month',
                    'location' => 'Doha, Qatar',
                    'vacancies' => '10 vacancies',
                    'posted' => 'Posted 3 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Hotel Front Desk Officer',
                    'company' => 'Marina Bay Hotels',
                    'category' => 'Hospitality',
                    'salary' => '$1,800 - $2,300/month',
                    'location' => 'Dubai, UAE',
                    'vacancies' => '6 vacancies',
                    'posted' => 'Posted 4 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Welder (TIG/MIG)',
                    'company' => 'Gulf Industrial Works',
                    'category' => 'Manufacturing',
                    'salary' => '$2,200 - $3,200/month',
                    'location' => 'Riyadh, Saudi Arabia',
                    'vacancies' => '12 vacancies',
                    'posted' => 'Posted 2 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Mechanical Engineer',
                    'company' => 'Metro Engineering Ltd.',
                    'category' => 'Engineering',
                    'salary' => '$4,800 - $6,500/month',
                    'location' => 'Abu Dhabi, UAE',
                    'vacancies' => '5 vacancies',
                    'posted' => 'Posted 5 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Accountant',
                    'company' => 'Blue River Finance',
                    'category' => 'Finance',
                    'salary' => '$3,000 - $4,200/month',
                    'location' => 'Singapore, Singapore',
                    'vacancies' => '4 vacancies',
                    'posted' => 'Posted 1 week ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Factory Production Staff',
                    'company' => 'Sunrise Manufacturing Co.',
                    'category' => 'Manufacturing',
                    'salary' => '$1,900 - $2,600/month',
                    'location' => 'Osaka, Japan',
                    'vacancies' => '20 vacancies',
                    'posted' => 'Posted 6 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Caregiver',
                    'company' => 'Golden Heart Care Home',
                    'category' => 'Healthcare',
                    'salary' => '$2,800 - $3,600/month',
                    'location' => 'Toronto, Canada',
                    'vacancies' => '9 vacancies',
                    'posted' => 'Posted 3 days ago',
                    'badge' => 'Featured',
                ],
            ];
        @endphp

        {{-- Grid: 3 columns, max 3 rows (9 items) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach (array_slice($featuredJobs, 0, 9) as $job)
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    {{-- Top: Title + Save --}}
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $job['title'] }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $job['company'] }}</p>
                        </div>

                        {{-- Save icon --}}
                        <button class="text-gray-400 hover:text-gray-600 transition">
                            <i data-lucide="bookmark" class="w-5 h-5"></i>
                        </button>
                    </div>

                    {{-- Category pill --}}
                    <div class="mt-4">
                        <span class="inline-flex items-center rounded-full bg-green-100 text-green-800 px-3 py-1 text-xs font-semibold">
                            {{ $job['category'] }}
                        </span>
                    </div>

                    {{-- Details --}}
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

                    {{-- Apply button --}}
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
                View all featured jobs
                <i data-lucide="arrow-right" class="w-4 h-4 ml-2 text-gray-600"></i>
            </a>
        </div>
    </div>

   
</section>
