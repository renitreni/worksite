<section class="relative min-h-[90vh] flex items-center justify-center px-4 overflow-hidden">
    <!-- Background Image + Masks -->
    <div class="absolute inset-0 z-0">
        <!-- ONE background only -->
        <div class="hero-bg absolute inset-0 bg-cover bg-center"
            style="background-image: url('/images/ofw-mobile.png');"></div>

        <!-- Desktop background swap -->
        <div class="hero-bg absolute inset-0 bg-cover bg-center hidden lg:block"
            style="background-image: url('/images/ofw.png');"></div>

        <!-- Very light blur -->
        <div class="absolute inset-0 "></div>

        <!-- Color mask -->
        <div class="absolute inset-0 bg-gradient-to-br from-green-950/50 via-black/40 to-green-900/45"></div>

        <!-- Soft highlight -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(34,197,94,0.18),transparent_58%)]"></div>

        <!-- Light vignette -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/15 via-transparent to-black/5"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 w-full max-w-4xl text-center pt-10 sm:pt-0">
        <h1 class="text-2xl sm:text-4xl md:text-5xl font-bold tracking-tight text-white animate-fade-in">
            Explore Countless Opportunities Awaiting You
        </h1>

        <p class="mt-3 sm:mt-4 text-sm sm:text-lg text-white/80 animate-fade-in delay-200 px-2 sm:px-0">
            Discover thousands of job opportunities with no placement fees. Connect with verified agencies and start
            your career journey today.
        </p>

        <div class="mt-6 sm:mt-10 bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/30
                   p-3 sm:p-4 flex flex-col md:flex-row gap-2 sm:gap-3 items-stretch animate-slide-up">

            <div class="flex items-center gap-2 flex-1 border border-gray-200 rounded-xl
                       px-3 sm:px-4 py-2.5 sm:py-3 focus-within:border-green-600 transition
                       transform hover:-translate-y-0.5 hover:shadow-md duration-200 bg-white">
                <i data-lucide="briefcase" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400"></i>
                <input type="text" placeholder="Job title or keyword"
                    class="w-full outline-none text-gray-700 placeholder-gray-400 text-sm sm:text-lg bg-transparent" />
            </div>

            <div class="flex items-center gap-2 flex-1 border border-gray-200 rounded-xl
                       px-3 sm:px-4 py-2.5 sm:py-3 focus-within:border-green-600 transition
                       transform hover:-translate-y-0.5 hover:shadow-md duration-200 bg-white">
                <i data-lucide="globe" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400"></i>
                <select class="w-full outline-none text-gray-700 bg-transparent text-sm sm:text-lg">
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

            <button class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 transition
                       text-white font-semibold px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl
                       text-sm sm:text-lg transform hover:-translate-y-0.5 hover:shadow-md duration-200">
                <i data-lucide="search" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                Search Jobs
            </button>
        </div>

        <div class="mt-5 sm:mt-6 grid grid-cols-2 sm:flex sm:flex-wrap justify-center gap-2 sm:gap-3">
            <div class="filter-card flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md border border-white/30
                       rounded-2xl px-3 sm:px-4 py-2 cursor-pointer transition transform hover:-translate-y-0.5
                       hover:shadow-lg duration-200">
                <i data-lucide="shield-check" class="w-4 h-4 text-green-700"></i>
                <span class="text-gray-800 text-xs sm:text-sm font-medium">No placement fee</span>
            </div>

            <div class="filter-card flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md border border-white/30
                       rounded-2xl px-3 sm:px-4 py-2 cursor-pointer transition transform hover:-translate-y-0.5
                       hover:shadow-lg duration-200">
                <i data-lucide="graduation-cap" class="w-4 h-4 text-green-700"></i>
                <span class="text-gray-800 text-xs sm:text-sm font-medium">High school diploma</span>
            </div>

            <div class="filter-card flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md border border-white/30
                       rounded-2xl px-3 sm:px-4 py-2 cursor-pointer transition transform hover:-translate-y-0.5
                       hover:shadow-lg duration-200">
                <i data-lucide="user-x" class="w-4 h-4 text-green-700"></i>
                <span class="text-gray-800 text-xs sm:text-sm font-medium">No work experience</span>
            </div>

            <div class="filter-card flex items-center gap-2 bg-white/90 backdrop-blur-md shadow-md border border-white/30
                       rounded-2xl px-3 sm:px-4 py-2 cursor-pointer transition transform hover:-translate-y-0.5
                       hover:shadow-lg duration-200">
                <i data-lucide="award" class="w-4 h-4 text-green-700"></i>
                <span class="text-gray-800 text-xs sm:text-sm font-medium">College graduate</span>
            </div>
        </div>
    </div>
</section>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

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

<style>
    /* IMPORTANT: stops the "zooming/parallax" feeling on mobile */
    .hero-bg {
        background-attachment: scroll !important;
        background-position: center center;
        background-repeat: no-repeat;
        /* keeps it stable on mobile repaint */
        transform: translateZ(0);
        will-change: auto;
    }

    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in { animation: fade-in 0.6s ease forwards; }
    .animate-slide-up { animation: slide-up 0.6s ease forwards; }
    .delay-200 { animation-delay: 0.2s; }
</style>
