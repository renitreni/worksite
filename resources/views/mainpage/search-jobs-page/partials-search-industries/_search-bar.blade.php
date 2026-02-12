<header class="w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Title -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight">
                Browse Job <span class="text-[#16A34A]">Industries</span>
            </h1>
            <p class="mt-2 text-gray-600 max-w-2xl">
                Explore opportunities by industry. Choose a category and discover available jobs from trusted agencies.
            </p>
        </div>

        @include('mainpage.components.search-switcher')

                <!-- GREEN SEARCH CARD -->
        <div class="relative overflow-hidden rounded-3xl shadow-lg border border-emerald-900/10">

            <div class="absolute inset-0 bg-gradient-to-br from-[#0f5f2f] via-[#16A34A] to-[#22c55e]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.22),transparent_55%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,rgba(0,0,0,0.18),transparent_50%)]"></div>

            <div class="relative p-4 sm:p-6 lg:p-8">
                <form id="industrySearchForm" class="space-y-5">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4">

                        <!-- keyword -->
                        <div class="lg:col-span-6">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Industry name
                            </label>
                            <div class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90
                                px-4 py-3">
                                <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                                <input type="text" name="keyword" placeholder="Example: Healthcare"
                                    class="w-full outline-none bg-transparent text-gray-800">
                            </div>
                        </div>

                        <!-- country -->
                        <div class="lg:col-span-6">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Country
                            </label>
                            <div class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90
                                px-4 py-3">
                                <i data-lucide="globe" class="w-5 h-5 text-gray-400"></i>
                                <select name="country" class="w-full outline-none bg-transparent text-gray-700">
                                    <option value="">All Countries</option>
                                    <option>Saudi Arabia</option>
                                    <option>Japan</option>
                                    <option>UAE</option>
                                    <option>Qatar</option>
                                    <option>Canada</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                                   bg-white text-[#0f5f2f] font-extrabold px-8 py-4 rounded-2xl shadow-md">
                            <i data-lucide="search" class="w-5 h-5"></i>
                            Search Industry
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</header>
