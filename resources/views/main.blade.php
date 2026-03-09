<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name', 'Jobabroad'))</title>

    <meta name="description" content="@yield('meta_description', 'Worksite connects job seekers with verified overseas employers and agencies.')">
    <meta name="keywords"
        content="overseas jobs, job abroad, verified employers, work abroad Philippines, overseas employment, jobabroad">
    <meta name="author" content="Jobabroad">
    <meta name="robots" content="@yield('robots', 'index,follow')">

    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta name="theme-color" content="#16A34A">
    <link rel="icon" href="/favicon.ico">
    <link rel="icon" type="image/png" href="/images/favicon.png">

    <meta name="mobile-web-app-capable" content="yes">

    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title', config('app.name', 'Jobabroad'))))">
    <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description', 'Jobabroad connects job seekers with verified overseas employers and agencies.')))">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:type" content="website">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.png'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', trim($__env->yieldContent('title', config('app.name', 'Jobabroad'))))">
    <meta name="twitter:description" content="@yield('twitter_description', trim($__env->yieldContent('meta_description', 'Jobabroad connects job seekers with verified overseas employers and agencies.')))">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-default.png'))">

    <meta name="google-site-verification" content="_ey-_rSSXAgZc9T6m9UpWC0GwasM1jjQUaok-YscLI4" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet">
    @verbatim
        <script type="application/ld+json">
            {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": "Jobabroad",
            "url": "/",
            "description": "Worksite connects job seekers with verified overseas employers and agencies.",
            "potentialAction": {
                "@type": "SearchAction",
                "target": "/jobs?search={search_term_string}",
                "query-input": "required name=search_term_string"
            }
            }
        </script>
    @endverbatim

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>

<body id="top" class="text-gray-800">
    @include('mainpage.components.navbar')

    <main>
        @yield('content')
    </main>
    @include('mainpage.components.footer')


    <script>
        document.addEventListener("DOMContentLoaded", () => {

            if (window.lucide) {
                lucide.createIcons();
            }


            const menuBtn = document.getElementById("mobile-menu-button");
            const mobileMenu = document.getElementById("mobile-menu");
            const iconMenu = document.getElementById("icon-menu");
            const iconClose = document.getElementById("icon-close");
            const navbar = document.getElementById("navbar");
            const hero = document.querySelector("#hero-section");

            function setNavbarWhite() {

                navbar.classList.add("navbar-scrolled");
                navbar.classList.remove("bg-transparent");

                document.querySelectorAll(".nav-link").forEach(el => {
                    el.classList.remove("text-white");
                    el.classList.add("text-gray-700");
                });

                iconMenu?.classList.remove("text-white");
                iconMenu?.classList.add("text-gray-700");

                iconClose?.classList.remove("text-white");
                iconClose?.classList.add("text-gray-700");

            }

            function setNavbarTransparent() {

                navbar.classList.remove("navbar-scrolled");
                navbar.classList.add("bg-transparent");

                document.querySelectorAll(".nav-link").forEach(el => {
                    el.classList.add("text-white");
                    el.classList.remove("text-gray-700");
                });

                iconMenu?.classList.add("text-white");
                iconMenu?.classList.remove("text-gray-700");

                iconClose?.classList.add("text-gray-700");
                iconClose?.classList.remove("text-gray-700");

            }

            if (hero) {

                if (hero.getBoundingClientRect().bottom > 80) {
                    setNavbarTransparent();
                } else {
                    setNavbarWhite();
                }

                const observer = new IntersectionObserver(
                    ([entry]) => {
                        if (entry.isIntersecting) {
                            setNavbarTransparent();
                        } else {
                            setNavbarWhite();
                        }
                    }, {
                        threshold: 0.1
                    }
                );

                observer.observe(hero);

            } else {
                setNavbarWhite();
            }

        });
    </script>
    @livewireScripts

    @yield('script')
</body>

</html>
