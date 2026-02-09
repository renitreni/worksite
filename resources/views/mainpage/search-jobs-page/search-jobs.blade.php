<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Jobs | Worksite</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">


    <style>
        html,
        body {
            overflow-x: hidden;
        }
    </style>
</head>

<body  class="font-['Inter',sans-serif] bg-gray-50 text-gray-800>
    @include('mainpage.components.navbar')

    <!-- TOP AREA -->
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

            <!-- GREEN SEARCH CARD (unique design, hero vibe) -->
            <div class="relative overflow-hidden rounded-3xl shadow-lg border border-emerald-900/10">

                <!-- green layered background -->
                <div class="absolute inset-0 bg-gradient-to-br from-[#0f5f2f] via-[#16A34A] to-[#22c55e]"></div>
                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.22),transparent_55%)]">
                </div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,rgba(0,0,0,0.18),transparent_50%)]">
                </div>

                <!-- Content -->
                <div class="relative p-4 sm:p-6 lg:p-8">

                    <!-- FORM -->
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


                        <!-- FILTER CARDS (same as hero style) -->
                        <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-3">

                            <!-- card 1 -->
                            <button type="button" class="filter-card w-full sm:w-auto flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md
                       border border-white/30 rounded-2xl px-3 sm:px-4 py-2 cursor-pointer transition
                       hover:-translate-y-0.5 hover:shadow-lg duration-200">
                                <i data-lucide="shield-check" class="w-4 h-4 text-green-700"></i>
                                <span class="text-gray-800 text-xs sm:text-sm font-medium">No placement fee</span>
                                <input type="hidden" name="no_fee" value="0">
                            </button>

                            <!-- card 2 -->
                            <button type="button" class="filter-card w-full sm:w-auto flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md
                       border border-white/30 rounded-2xl px-3 sm:px-4 py-2 cursor-pointer transition
                       hover:-translate-y-0.5 hover:shadow-lg duration-200">
                                <i data-lucide="graduation-cap" class="w-4 h-4 text-green-700"></i>
                                <span class="text-gray-800 text-xs sm:text-sm font-medium">High school graduate</span>
                                <input type="hidden" name="hs_grad" value="0">
                            </button>

                            <!-- card 3 -->
                            <button type="button" class="filter-card w-full sm:w-auto flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md
                       border border-white/30 rounded-2xl px-3 sm:px-4 py-2 cursor-pointer transition
                       hover:-translate-y-0.5 hover:shadow-lg duration-200">
                                <i data-lucide="user-x" class="w-4 h-4 text-green-700"></i>
                                <span class="text-gray-800 text-xs sm:text-sm font-medium">No work experience</span>
                                <input type="hidden" name="no_exp" value="0">
                            </button>

                            <!-- card 4 -->
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

    <!-- RESULTS AREA PLACEHOLDER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <i data-lucide="info" class="w-4 h-4"></i>
            <span>Showing <strong>1 to 10</strong> out of <strong>4124</strong> jobs</span>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-[#16A34A] font-semibold">Sample Job</p>
                <h3 class="mt-1 font-bold text-gray-900">Factory Worker</h3>
                <p class="mt-2 text-sm text-gray-600">Saudi Arabia • Manufacturing</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-[#16A34A] font-semibold">Sample Job</p>
                <h3 class="mt-1 font-bold text-gray-900">Caregiver</h3>
                <p class="mt-2 text-sm text-gray-600">Japan • Healthcare</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-[#16A34A] font-semibold">Sample Job</p>
                <h3 class="mt-1 font-bold text-gray-900">Hotel Staff</h3>
                <p class="mt-2 text-sm text-gray-600">UAE • Hospitality</p>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();

        // Filter cards toggle (hero-style) + set hidden inputs to 1/0
        const filterCards = document.querySelectorAll('.filter-card');

        filterCards.forEach(card => {
            card.addEventListener('click', () => {
                const span = card.querySelector('span');
                const hidden = card.querySelector('input[type="hidden"]');

                card.classList.toggle('bg-green-50');
                card.classList.toggle('border-green-600');

                if (span) span.classList.toggle('text-green-700');

                // store state for form submit
                if (hidden) {
                    hidden.value = (hidden.value === "0") ? "1" : "0";
                }
            });
        });

        // Demo only: prevent reload (remove this when connecting to backend)
        document.getElementById("jobSearchForm").addEventListener("submit", (e) => {
            e.preventDefault();
            alert("Search submitted! (Connect this to backend later)");
        });
    </script>

</body>

</html>