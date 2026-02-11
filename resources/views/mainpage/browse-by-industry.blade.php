<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                Browse by Specialization
            </h2>
            <p class="text-gray-600 mt-3 max-w-xl mx-auto">
                Discover the most in-demand OFW job categories and opportunities.
            </p>
        </div>

        @php
            $specializations = [
                [
                    'image' => 'domestic.jpg',
                    'name' => 'Domestic',
                    'jobs' => 1200,
                    'sub' => ['Helper', 'Nanny', 'Housekeeper', 'Driver'],
                ],
                [
                    'image' => 'caregiver.avif',
                    'name' => 'Caregiver',
                    'jobs' => 860,
                    'sub' => ['Caregiver', 'Nursing Aide', 'Home Care', 'Private Nurse'],
                ],
                [
                    'image' => 'construction.jpeg',
                    'name' => 'Construction',
                    'jobs' => 740,
                    'sub' => ['Laborer', 'Welder', 'Electrician', 'Carpenter'],
                ],
                [
                    'image' => 'factory.jpg',
                    'name' => 'Factory',
                    'jobs' => 920,
                    'sub' => ['Worker', 'Operator', 'Packaging', 'Assembler'],
                ],
                [
                    'image' => 'driver.webp',
                    'name' => 'Driver',
                    'jobs' => 510,
                    'sub' => ['Company', 'Delivery', 'Truck', 'Forklift'],
                ],
                [
                    'image' => 'hospitality.png',
                    'name' => 'Hospitality',
                    'jobs' => 680,
                    'sub' => ['Reception', 'Housekeeping', 'Bellman', 'Cleaner'],
                ],
                [
                    'image' => 'food.jpg',
                    'name' => 'Food',
                    'jobs' => 590,
                    'sub' => ['Barista', 'Waiter', 'Cook', 'Kitchen'],
                ],
                [
                    'image' => 'admin.jpg',
                    'name' => 'Admin',
                    'jobs' => 430,
                    'sub' => ['Assistant', 'Clerk', 'Encoder', 'CSR'],
                ],
                [
                    'image' => 'beauty.jpg',
                    'name' => 'Beauty',
                    'jobs' => 370,
                    'sub' => ['Beautician', 'Hair', 'Makeup', 'Massage'],
                ],
                [
                    'image' => 'maritime.avif',
                    'name' => 'Maritime',
                    'jobs' => 295,
                    'sub' => ['Deck', 'Engine', 'Cook', 'Steward'],
                ],
            ];
        @endphp

        <!-- Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach ($specializations as $item)
                <a href="#"
                   class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg
                          transition-all duration-300 hover:-translate-y-1 overflow-hidden">

                    <!-- Image -->
                    <div class="h-32 w-full overflow-hidden">
                        <img
                            src="{{ asset('images/' . $item['image']) }}"
                            alt="{{ $item['name'] }}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                        >
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <div class="text-center">
                            <h3 class="text-base font-semibold text-gray-900 group-hover:text-[#16A34A] transition-colors">
                                {{ $item['name'] }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ number_format($item['jobs']) }} jobs
                            </p>
                        </div>

                        <!-- Sub jobs -->
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            @foreach ($item['sub'] as $subjob)
                                <div class="px-2 py-1 text-[11px] font-medium
                                            bg-green-50 text-green-700
                                            border border-green-100
                                            rounded-full text-center
                                            whitespace-nowrap overflow-hidden text-ellipsis">
                                    {{ $subjob }}
                                </div>
                            @endforeach
                        </div>

                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>
