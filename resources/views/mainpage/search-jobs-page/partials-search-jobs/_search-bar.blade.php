<header class="w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Title -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight">
                Find Overseas <span class="text-[#16A34A]">Jobs</span> here
            </h1>
            <p class="mt-2 text-gray-600 max-w-2xl">
                Search by keyword, preferred country, and industry — then apply quick filters to match your needs.
            </p>
        </div>
        @include('mainpage.components.search-switcher')


        <!-- GREEN SEARCH CARD -->
        <div class="relative overflow-hidden rounded-3xl shadow-lg border border-emerald-900/10">

            <!-- green layered background -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#0f5f2f] via-[#16A34A] to-[#22c55e]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.22),transparent_55%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,rgba(0,0,0,0.18),transparent_50%)]"></div>

            <!-- Content -->
            <div class="relative p-4 sm:p-6 lg:p-8">
                <form id="jobSearchForm" class="space-y-5">

                    <!-- Inputs -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4">

                        <!-- Keywords -->
                        <div class="lg:col-span-4">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Jobs by Keywords
                            </label>
                            <div class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90 backdrop-blur-md
                                px-4 py-3 focus-within:border-white transition">
                                <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                                <input type="text" name="keyword" placeholder="Search Jobs by Keywords"
                                    class="w-full outline-none bg-transparent text-gray-800 placeholder:text-gray-400" />
                            </div>
                        </div>

                        <!-- Country -->
                        <div class="lg:col-span-4">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Preferred Country
                            </label>
                            <div class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90 backdrop-blur-md
                                px-4 py-3 focus-within:border-white transition">
                                <i data-lucide="globe" class="w-5 h-5 text-gray-400"></i>
                                <select name="country" class="w-full outline-none bg-transparent text-gray-700">
                                    <option value="">Search Jobs by Country</option>
                                    <option>Saudi Arabia</option>
                                    <option>UAE</option>
                                    <option>Qatar</option>
                                    <option>Japan</option>
                                    <option>Canada</option>
                                    <option>Australia</option>
                                    <option>Singapore</option>
                                </select>
                            </div>
                        </div>

                        <!-- Industry -->
                        <div class="lg:col-span-4">
                            <label class="block text-sm font-semibold text-white/90 mb-2">
                                Preferred Industry
                            </label>
                            <div class="flex items-center gap-2 rounded-2xl border border-white/25 bg-white/90 backdrop-blur-md
                                px-4 py-3 focus-within:border-white transition">
                                <i data-lucide="briefcase" class="w-5 h-5 text-gray-400"></i>
                                <select name="industry" class="w-full outline-none bg-transparent text-gray-700">
                                    <option value="">Search Jobs by Industry</option>
                                    <option>Healthcare</option>
                                    <option>Construction</option>
                                    <option>Hospitality</option>
                                    <option>Manufacturing</option>
                                    <option>Transportation</option>
                                    <option>Technology</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- FILTER CARDS -->
                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-3">

                        <button type="button" class="filter-card w-full sm:w-auto flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md
                            border border-white/30 rounded-2xl px-3 sm:px-4 py-2 cursor-pointer transition
                            hover:-translate-y-0.5 hover:shadow-lg duration-200">
                            <i data-lucide="shield-check" class="w-4 h-4 text-green-700"></i>
                            <span class="text-gray-800 text-xs sm:text-sm font-medium">No placement fee</span>
                            <input type="hidden" name="no_fee" value="0">
                        </button>

                        <button type="button" class="filter-card w-full sm:w-auto flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md
                            border border-white/30 rounded-2xl px-3 sm:px-4 py-2 cursor-pointer transition
                            hover:-translate-y-0.5 hover:shadow-lg duration-200">
                            <i data-lucide="graduation-cap" class="w-4 h-4 text-green-700"></i>
                            <span class="text-gray-800 text-xs sm:text-sm font-medium">High school graduate</span>
                            <input type="hidden" name="hs_grad" value="0">
                        </button>

                        <button type="button" class="filter-card w-full sm:w-auto flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md
                            border border-white/30 rounded-2xl px-3 sm:px-4 py-2 cursor-pointer transition
                            hover:-translate-y-0.5 hover:shadow-lg duration-200">
                            <i data-lucide="user-x" class="w-4 h-4 text-green-700"></i>
                            <span class="text-gray-800 text-xs sm:text-sm font-medium">No work experience</span>
                            <input type="hidden" name="no_exp" value="0">
                        </button>

                        <button type="button" class="filter-card w-full sm:w-auto flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md
                            border border-white/30 rounded-2xl px-3 sm:px-4 py-2 cursor-pointer transition
                            hover:-translate-y-0.5 hover:shadow-lg duration-200">
                            <i data-lucide="award" class="w-4 h-4 text-green-700"></i>
                            <span class="text-gray-800 text-xs sm:text-sm font-medium">College graduate</span>
                            <input type="hidden" name="college_grad" value="0">
                        </button>
                    </div>

                    <!-- Search Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                            bg-white text-[#0f5f2f] hover:bg-white/90 transition
                            font-extrabold px-8 py-4 rounded-2xl shadow-md">
                            <i data-lucide="search" class="w-5 h-5"></i>
                            Search Jobs
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</header>
