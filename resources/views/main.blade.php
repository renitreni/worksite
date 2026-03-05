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
    <meta name="theme-color" content="#2563eb">

    <link rel="icon" href="/favicon.ico">
    <link rel="icon" type="image/png" href="/images/favicon.png">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

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

    <link rel="preload" href="{{ asset('images/og-default.png') }}" as="image">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @verbatim
        <script type="application/ld+json">
            {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": "Jobabroad",
            "url": "http://localhost:8000",
            "description": "Worksite connects job seekers with verified overseas employers and agencies.",
            "potentialAction": {
                "@type": "SearchAction",
                "target": "http://localhost:8000/jobs?search={search_term_string}",
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
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>

<body id="top" class="font-['Inter',sans-serif] text-gray-800">
    @include('mainpage.components.navbar')

    @yield('content')

    @include('mainpage.components.footer')


    <script>
        function refreshLucide() {
            if (window.lucide) lucide.createIcons();
        }

        document.addEventListener('DOMContentLoaded', refreshLucide);
        document.addEventListener('livewire:initialized', () => {
            refreshLucide();
            Livewire.hook('morph.updated', () => refreshLucide());
        });
    </script>
    @livewireScripts

    @yield('script')
</body>

</html>
