<section class="relative min-h-[90vh] flex items-center justify-center px-4 overflow-hidden">

    <!-- Background Image + Mask -->
    <div class="absolute inset-0 z-0">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center lg:bg-right"
            style="background-image: url('/images/ofw.png');">
        </div>

        <!-- Very light blur -->
        <div class="absolute inset-0 backdrop-blur-[2px]"></div>

        <!-- Color mask -->
        <div class="absolute inset-0 bg-gradient-to-br from-green-950/50 via-black/40 to-green-900/45"></div>

        <!-- Soft highlight -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(34,197,94,0.18),transparent_58%)]"></div>

        <!-- Light vignette -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/15 via-transparent to-black/5"></div>

    </div>

    <!-- Content -->
    <div class="relative z-10 w-full max-w-4xl text-center">

        <!-- Title -->
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-white animate-fade-in">
            Explore Countless Opportunities Awaiting You
        </h1>

        <!-- Subtitle -->
        <p class="mt-4 text-base sm:text-lg text-white/80 animate-fade-in delay-200">
            Discover thousands of job opportunities with no placement fees. Connect with verified agencies and start
            your career journey today.
        </p>

        <!-- Search Bar -->
        <div
            class="mt-10 bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/30 p-4 flex flex-col md:flex-row gap-3 items-stretch animate-slide-up">

            <!-- Job Title -->
            <div
                class="flex items-center gap-2 flex-1 border border-gray-200 rounded-xl px-4 py-3 focus-within:border-green-600 transition transform hover:-translate-y-0.5 hover:shadow-md duration-200 bg-white">
                <i data-lucide="briefcase" class="w-5 h-5 text-gray-400"></i>
                <input type="text" placeholder="Job title or keyword"
                    class="w-full outline-none text-gray-700 placeholder-gray-400 text-base sm:text-lg bg-transparent" />
            </div>

            <!-- Country Dropdown -->
            <div
                class="flex items-center gap-2 flex-1 border border-gray-200 rounded-xl px-4 py-3 focus-within:border-green-600 transition transform hover:-translate-y-0.5 hover:shadow-md duration-200 bg-white">
                <i data-lucide="globe" class="w-5 h-5 text-gray-400"></i>
                <select class="w-full outline-none text-gray-700 bg-transparent text-base sm:text-lg">
                    <option>All Countries</option>
                    <option>Philippines</option>
                    <option>Saudi Arabia</option>
                    <option>UAE</option>
                    <option>Japan</option>
                    <option>Canada</option>
                    <option>Australia</option>
                    <option>Qatar</option>
                    <option>Singapore</option>
                </select>
            </div>

            <!-- Search Button -->
            <button
                class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 transition text-white font-semibold px-6 py-3 rounded-xl text-base sm:text-lg transform hover:-translate-y-0.5 hover:shadow-md duration-200">
                <i data-lucide="search" class="w-5 h-5"></i>
                Search Jobs
            </button>
        </div>

        <!-- Filter Cards -->
        <div class="mt-6 flex flex-wrap justify-center gap-3">

            <div
                class="filter-card flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md border border-white/30 rounded-2xl px-4 py-2 cursor-pointer transition transform hover:-translate-y-0.5 hover:shadow-lg duration-200">
                <i data-lucide="shield-check" class="w-4 h-4 text-green-700"></i>
                <span class="text-gray-800 text-sm font-medium">No placement fee</span>
            </div>

            <div
                class="filter-card flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md border border-white/30 rounded-2xl px-4 py-2 cursor-pointer transition transform hover:-translate-y-0.5 hover:shadow-lg duration-200">
                <i data-lucide="graduation-cap" class="w-4 h-4 text-green-700"></i>
                <span class="text-gray-800 text-sm font-medium">High school diploma</span>
            </div>

            <div
                class="filter-card flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md border border-white/30 rounded-2xl px-4 py-2 cursor-pointer transition transform hover:-translate-y-0.5 hover:shadow-lg duration-200">
                <i data-lucide="user-x" class="w-4 h-4 text-green-700"></i>
                <span class="text-gray-800 text-sm font-medium">No work experience</span>
            </div>

            <div
                class="filter-card flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md border border-white/30 rounded-2xl px-4 py-2 cursor-pointer transition transform hover:-translate-y-0.5 hover:shadow-lg duration-200">
                <i data-lucide="award" class="w-4 h-4 text-green-700"></i>
                <span class="text-gray-800 text-sm font-medium">College graduate</span>
            </div>

        </div>

    </div>
</section>

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

    // Interactive filter cards toggle using Tailwind-safe classes
    const filterCards = document.querySelectorAll('.filter-card');
    filterCards.forEach(card => {
        card.addEventListener('click', () => {
            card.classList.toggle('bg-green-50');
            card.classList.toggle('border-green-600');
            const span = card.querySelector('span');
            span.classList.toggle('text-green-700');
        });
    });
</script>

<!-- Animations -->
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease forwards;
    }

    .animate-slide-up {
        animation: slide-up 0.6s ease forwards;
    }

    .delay-200 {
        animation-delay: 0.2s;
    }
</style>