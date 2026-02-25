<section class="relative min-h-[90vh] flex items-center justify-center px-4 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="hero-bg absolute inset-0 bg-cover bg-center" style="background-image: url('/images/ofw-mobile.png');">
        </div>
        <div class="hero-bg absolute inset-0 bg-cover bg-center hidden lg:block"
            style="background-image: url('/images/ofw.png');"></div>
        <div class="absolute inset-0 "></div>
        <div class="absolute inset-0 bg-gradient-to-br from-green-950/50 via-black/40 to-green-900/45"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(34,197,94,0.18),transparent_58%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/15 via-transparent to-black/5"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl text-center pt-10 sm:pt-0">
        <h1 class="text-2xl sm:text-4xl md:text-5xl font-bold tracking-tight text-white animate-fade-in">
            Find Trusted Overseas Job Opportunities with JobAbroad
        </h1>

        <p class="mt-3 sm:mt-4 text-sm sm:text-lg text-white/80 animate-fade-in delay-200 px-2 sm:px-0">
            Discover thousands of job opportunities with no placement fees. Connect with verified agencies and start
            your career journey today.
        </p>

        <livewire:hero-job-search />
    </div>

</section>

<style>
    .hero-bg {
        background-attachment: scroll !important;
        background-position: center center;
        background-repeat: no-repeat;
        transform: translateZ(0);
        will-change: auto;
    }

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
