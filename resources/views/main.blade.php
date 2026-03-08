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

    <style>
        [x-cloak] {
            display: none !important;
        }

        .hero-bg {
            background-attachment: scroll !important;
            background-position: center;
            background-repeat: no-repeat;
        }

        html {
            scroll-behavior: smooth !important;
        }

        .hero-title {
            font-family: 'Space Grotesk', sans-serif;
        }

        .section-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .dropdown-link {
            display: block;
            padding: 10px 16px;
            font-size: 14px;
            color: #374151;
        }

        .dropdown-link:hover {
            background: #f0fdf4;
            color: #16A34A;
        }

        .nav-link {
            position: relative;
            transition: all .25s ease;
        }

        .nav-link:hover {
            color: #22c55e;
        }

        #navbar {
            transition: background .3s ease, box-shadow .3s ease;
        }

        /* SCROLLED NAVBAR */

        .navbar-scrolled {
            background: white !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .navbar-scrolled .nav-link {
            color: #374151 !important;
        }

        .navbar-scrolled .nav-link:hover {
            color: #16A34A !important;
        }

        /* Mobile icon color when scrolled */
        .navbar-scrolled #icon-menu,
        .navbar-scrolled #icon-close {
            color: #374151 !important;
        }

        #mobile-menu {
            background: white;
        }

        #icon-menu,
        #icon-close {
            transition: all .3s ease;
        }
    </style>

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

            /* -----------------------
               LUCIDE ICONS
            ----------------------- */

            if (window.lucide) {
                lucide.createIcons();
            }


            const menuBtn = document.getElementById("mobile-menu-button");
            const mobileMenu = document.getElementById("mobile-menu");
            const iconMenu = document.getElementById("icon-menu");
            const iconClose = document.getElementById("icon-close");
            /* -----------------------
               NAVBAR COLOR CONTROL
            ----------------------- */

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


            /* -----------------------
               HERO OBSERVER
            ----------------------- */

            if (hero) {

                /* set initial state */
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
