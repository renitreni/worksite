<section id="hero-section" class="relative min-h-screen flex items-center justify-center px-6">

    <!-- BACKGROUND -->
    <div class="absolute inset-0 -z-10">

        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/images/background-2.webp');">
        </div>

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-green-950/80 via-black/70 to-green-900/70"></div>

        <!-- Radial Highlight -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(34,197,94,0.18),transparent_60%)]"></div>

    </div>

    <!-- HERO CONTENT -->
    <div class="relative z-10 w-full max-w-5xl mx-auto text-center flex flex-col items-center">

        <!-- TITLE -->
        <h1 class="hero-title text-3xl sm:text-5xl lg:text-6xl font-semibold leading-tight text-white animate-fade-in">

            Find Trusted Overseas <br class="hidden sm:block">
            Job Opportunities

        </h1>

        <!-- DESCRIPTION -->
        <p class="mt-6 max-w-2xl text-sm sm:text-lg text-white/80 leading-relaxed animate-fade-in delay-200">

            Discover verified overseas job opportunities with no placement fees.
            Connect with trusted agencies and take the next step toward building
            your career abroad.

        </p>

        <!-- SEARCH BAR (CENTERED) -->
        <div class="mt-10 w-full max-w-3xl animate-slide-up delay-200">


            <livewire:hero-job-search />


        </div>

    </div>

</section>
