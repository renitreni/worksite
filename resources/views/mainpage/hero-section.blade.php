<section id="hero-section" aria-label="Overseas jobs for Filipinos"
    class="relative min-h-screen flex items-center justify-center px-6 overflow-hidden">

    <!-- SEO IMAGE (hidden but indexable) -->
    <img src="/images/background/Background-1.png" alt="Filipino workers applying for overseas jobs abroad" class="hidden">

    <img src="/images/background/Background-2.png" alt="Overseas job opportunities for Filipinos with verified employers"
        class="hidden">

    <!-- BACKGROUND -->
    <div class="absolute inset-0 -z-10">

        <!-- Background Layer 1 -->
        <div id="hero-bg-1" class="hero-bg absolute inset-0 bg-cover bg-center opacity-100"
            style="background-image:url('/images/background/Background-1.png');">
        </div>

        <!-- Background Layer 2 -->
        <div id="hero-bg-2" class="hero-bg absolute inset-0 bg-cover bg-center opacity-0"
            style="background-image:url('/images/background/Background-2.png');">
        </div>

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-green-950/80 via-black/70 to-green-900/70"></div>

        <!-- Radial Highlight -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(34,197,94,0.18),transparent_60%)]"></div>

    </div>

    <!-- HERO CONTENT -->
    <div class="relative z-10 w-full max-w-5xl mx-auto text-center flex flex-col items-center">

        <!-- ✅ SEO IMPROVED H1 -->
        <h1 class="hero-title text-3xl sm:text-5xl lg:text-6xl font-semibold leading-tight text-white animate-fade-in">
            Find Trusted Overseas Jobs <br class="hidden sm:block">
            for Filipinos
        </h1>

        <!-- ✅ SEO IMPROVED DESCRIPTION -->
        <p class="mt-6 max-w-2xl text-sm sm:text-lg text-white/80 leading-relaxed animate-fade-in delay-200">
            Explore verified overseas job opportunities for Filipinos with no placement fees.
            Connect with trusted recruitment agencies and employers abroad, and start your journey as an OFW today.
        </p>

        <div class="mt-10 w-full max-w-3xl animate-slide-up delay-200">
            <livewire:hero-job-search />
        </div>

    </div>

</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const bg1 = document.getElementById("hero-bg-1");
        const bg2 = document.getElementById("hero-bg-2");

        const desktopImages = [
            "/images/background/Background-1.png",
            "/images/background/Background-2.png",
            "/images/background/Background-3.png",
            "/images/background/Background-4.png"
        ];

        const mobileImages = [
            "/images/background/mobile-background-1.png",
            "/images/background/mobile-background-2.png",
            "/images/background/mobile-background-3.png",
            "/images/background/mobile-background-4.png"
        ];

        const isMobile = window.innerWidth <= 640;

        const images = isMobile ? mobileImages : desktopImages;

        let index = 0;
        let showingFirst = true;

        bg1.style.backgroundImage = `url('${images[0]}')`;

        setInterval(() => {

            index = (index + 1) % images.length;

            if (showingFirst) {

                bg2.style.backgroundImage = `url('${images[index]}')`;

                bg2.style.opacity = "1";
                bg1.style.opacity = "0";

            } else {

                bg1.style.backgroundImage = `url('${images[index]}')`;

                bg1.style.opacity = "1";
                bg2.style.opacity = "0";

            }

            showingFirst = !showingFirst;

        }, 8000);

    });
</script>
