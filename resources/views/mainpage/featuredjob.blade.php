{{-- Featured Jobs --}}
<section class="py-16 bg-white">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Featured Jobs</h2>
            <p class="text-gray-600 mt-2">Top opportunities from leading employers</p>
        </div>

        @php
            // 12 items = 4 columns x 3 rows (on lg screens)
            $featuredJobs = [
                [
                    'title' => 'Registered Nurse',
                    'company' => 'Dubai Healthcare Center',
                    'agency' => 'Healthcare Staffing Plus',
                    'location' => 'Dubai, UAE',
                    'salary' => '$3,500 - $5,000/month',
                    'type' => 'Full-time',
                    'tags' => ['Patient Care', 'Medical Records', 'Emergency Response'],
                    'posted' => '2 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Senior Software Engineer',
                    'company' => 'Tech Innovations Inc.',
                    'agency' => 'TechTalent International',
                    'location' => 'Singapore, Singapore',
                    'salary' => '$6,000 - $8,500/month',
                    'type' => 'Full-time',
                    'tags' => ['React', 'Node.js', 'AWS'],
                    'posted' => '1 day ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Civil Engineer',
                    'company' => 'Qatar Construction Group',
                    'agency' => 'Construction Careers Global',
                    'location' => 'Doha, Qatar',
                    'salary' => '$4,200 - $6,000/month',
                    'type' => 'Full-time',
                    'tags' => ['AutoCAD', 'Project Management', 'Structural Design'],
                    'posted' => '3 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Hotel Front Desk Officer',
                    'company' => 'Marina Bay Hotels',
                    'agency' => 'Prime Hospitality Staffing',
                    'location' => 'Dubai, UAE',
                    'salary' => '$1,800 - $2,300/month',
                    'type' => 'Full-time',
                    'tags' => ['Customer Service', 'Reservations', 'Front Office'],
                    'posted' => '4 days ago',
                    'badge' => 'Featured',
                ],

                [
                    'title' => 'Welder (TIG/MIG)',
                    'company' => 'Gulf Industrial Works',
                    'agency' => 'SkilledTrades Overseas',
                    'location' => 'Riyadh, Saudi Arabia',
                    'salary' => '$2,200 - $3,200/month',
                    'type' => 'Full-time',
                    'tags' => ['TIG', 'MIG', 'Fabrication'],
                    'posted' => '2 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Mechanical Engineer',
                    'company' => 'Metro Engineering Ltd.',
                    'agency' => 'Engineers Hub Global',
                    'location' => 'Abu Dhabi, UAE',
                    'salary' => '$4,800 - $6,500/month',
                    'type' => 'Full-time',
                    'tags' => ['HVAC', 'Maintenance', 'CAD'],
                    'posted' => '5 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Accountant',
                    'company' => 'Blue River Finance',
                    'agency' => 'Prime Talent Agency',
                    'location' => 'Singapore, Singapore',
                    'salary' => '$3,000 - $4,200/month',
                    'type' => 'Full-time',
                    'tags' => ['Bookkeeping', 'Excel', 'Tax'],
                    'posted' => '1 week ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Factory Production Staff',
                    'company' => 'Sunrise Manufacturing Co.',
                    'agency' => 'Global Workforce Solutions',
                    'location' => 'Osaka, Japan',
                    'salary' => '$1,900 - $2,600/month',
                    'type' => 'Full-time',
                    'tags' => ['Assembly', 'Quality Check', 'Safety'],
                    'posted' => '6 days ago',
                    'badge' => 'Featured',
                ],

                [
                    'title' => 'Caregiver',
                    'company' => 'Golden Heart Care Home',
                    'agency' => 'Healthcare Staffing Plus',
                    'location' => 'Toronto, Canada',
                    'salary' => '$2,800 - $3,600/month',
                    'type' => 'Full-time',
                    'tags' => ['Elder Care', 'First Aid', 'Compassion'],
                    'posted' => '3 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'IT Support Specialist',
                    'company' => 'CloudWorks Systems',
                    'agency' => 'TechStaff Recruiters',
                    'location' => 'Kuala Lumpur, Malaysia',
                    'salary' => '$2,500 - $3,400/month',
                    'type' => 'Full-time',
                    'tags' => ['Helpdesk', 'Networking', 'Windows'],
                    'posted' => '2 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'Site Supervisor',
                    'company' => 'BuildRight Contractors',
                    'agency' => 'Construction Careers Global',
                    'location' => 'Doha, Qatar',
                    'salary' => '$3,700 - $5,200/month',
                    'type' => 'Full-time',
                    'tags' => ['Site Safety', 'Scheduling', 'QA/QC'],
                    'posted' => '5 days ago',
                    'badge' => 'Featured',
                ],
                [
                    'title' => 'ESL Teacher',
                    'company' => 'Bright Future Academy',
                    'agency' => 'Education Placement Hub',
                    'location' => 'Seoul, South Korea',
                    'salary' => '$2,300 - $3,000/month',
                    'type' => 'Full-time',
                    'tags' => ['Teaching', 'Classroom', 'English'],
                    'posted' => '1 day ago',
                    'badge' => 'Featured',
                ],
            ];
        @endphp

        {{-- Grid: 4 columns on lg, 2 on sm, 3 on md --}}
        {{-- Grid: 3 columns, max 3 rows (9 items) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach (array_slice($featuredJobs, 0, 9) as $job)
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                    {{-- Title + badge --}}
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="text-lg font-semibold text-gray-900 leading-snug">
                            {{ $job['title'] }}
                        </h3>
                        <span class="text-xs font-medium px-2 py-1 rounded-md bg-green-100 text-green-800">
                            {{ $job['badge'] }}
                        </span>
                    </div>

                    {{-- Company + via agency --}}
                    <div class="mt-3 space-y-1 text-gray-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="building-2" class="w-4 h-4 text-gray-500"></i>
                            <span class="text-sm">{{ $job['company'] }}</span>
                        </div>
                        <div class="text-sm">
                            via <span class="text-gray-700 font-medium">{{ $job['agency'] }}</span>
                        </div>
                    </div>

                    {{-- Location + Salary --}}
                    <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-gray-500"></i>
                            {{ $job['location'] }}
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="wallet" class="w-4 h-4 text-gray-500"></i>
                            {{ $job['salary'] }}
                        </div>
                    </div>

                    {{-- Job type --}}
                    <div class="mt-2 flex items-center gap-2 text-sm text-gray-600">
                        <i data-lucide="briefcase" class="w-4 h-4 text-gray-500"></i>
                        {{ $job['type'] }}
                    </div>

                    {{-- Tags --}}
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($job['tags'] as $tag)
                            <span class="text-xs px-2 py-1 rounded-md bg-gray-100 text-gray-700">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>

                    {{-- Footer --}}
                    <div class="mt-5 flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ $job['posted'] }}</span>
                        <a href="#"
                            class="rounded-xl bg-[#16A34A] px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 transition">
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