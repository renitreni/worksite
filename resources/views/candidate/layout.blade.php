<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Permissions-Policy" content="unload=(self)">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', default: 'Worksite') }}</title>

    <link rel="icon" href="/favicon.ico">
    <link rel="icon" type="image/png" href="/images/favicon.png">

    <!-- Fonts -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script>
        window.Laravel = {
            userId: {{ auth()->id() ?? 'null' }}
        }
    </script>
</head>

<body class="font-['Inter',sans-serif] bg-gray-100 text-gray-900 antialiased">
    <div x-data="{ mobileSidebarOpen: false }" x-cloak class="min-h-screen">
        {{-- Mobile overlay --}}
        <div x-show="mobileSidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40 lg:hidden"
            @click="mobileSidebarOpen = false" x-cloak></div>

        {{-- Sidebar (Desktop + Mobile Drawer) --}}
        @include('candidate.components.sidebar')

        {{-- Main Area --}}
        <div class="lg:pl-64 flex min-h-screen flex-col">
            @include('candidate.components.navbar')

            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                @yield('content')
            </main>
        </div>
    </div>

    <style>
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>

    <script>
        document.addEventListener('refresh-icons', () => {
            window.lucide?.createIcons();
        });
    </script>
    <script>
        window.userId = {{ auth()->id() }};
    </script>


    @livewireScripts

    <script>
        function initLucide() {
            if (window.lucide) {
                lucide.createIcons();
            }
        }

        document.addEventListener("DOMContentLoaded", initLucide);
        document.addEventListener("livewire:navigated", initLucide);
        document.addEventListener("livewire:updated", initLucide);
    </script>
</body>

</html>
