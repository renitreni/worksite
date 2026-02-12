<header class="w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Title -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight">
                Find Jobs by <span class="text-[#16A34A]">Country</span>
            </h1>
            <p class="mt-2 text-gray-600 max-w-2xl">
                Explore destinations and discover available opportunities from trusted agencies around the world.
            </p>
        </div>

        {{-- your reusable 4 buttons --}}
        @include('mainpage.components.search-switcher')
        <!-- GREEN SEARCH CARD -->
        <div class="relative overflow-hidden rounded-3xl shadow-lg border border-emerald-900/10">

            <!-- background -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#0f5f2f] via-[#16A34A] to-[#22c55e]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.22),transparent_55%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,rgba(0,0,0,0.18),transparent_50%)]"></div>

            <!-- content -->
            <div class="relative p-4 sm:p-6 lg:p-8">
                <form id="countrySearchForm" class="space-y-5">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4">

                        <!-- keyword -->
                        <div class="lg:col-span-6">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Country Name
                            </label>
                            <div class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90
                                px-4 py-3">
                                <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                                <input type="text"
                                       id="countryKeyword"
                                       placeholder="Example: Singapore"
                                       class="w-full outline-none bg-transparent text-gray-800">
                            </div>
                        </div>

                        <!-- region -->
                        <div class="lg:col-span-6">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Region
                            </label>
                            <div class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90
                                px-4 py-3">
                                <i data-lucide="globe" class="w-5 h-5 text-gray-400"></i>
                                <select id="countryRegion"
                                    class="w-full outline-none bg-transparent text-gray-700">
                                    <option value="">All Regions</option>
                                    <option>Africa</option>
                                    <option>Americas</option>
                                    <option>Asia</option>
                                    <option>Europe</option>
                                    <option>Oceania</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                                   bg-white text-[#0f5f2f] hover:bg-white/90 transition
                                   font-extrabold px-8 py-4 rounded-2xl shadow-md">
                            <i data-lucide="search" class="w-5 h-5"></i>
                            Search Country
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</header>
