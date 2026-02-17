<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', default: 'Worksite') }}</title>

    <!-- Fonts -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">


    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-['Inter',sans-serif] bg-gray-100 text-gray-900 antialiased">
    <div x-data="{ mobileSidebarOpen: false }" class="min-h-screen">

        {{-- Mobile overlay --}}
        <div x-show="mobileSidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40 lg:hidden"
            @click="mobileSidebarOpen = false" x-cloak></div>

        {{-- Sidebar (Desktop + Mobile Drawer) --}}
        @include('employer.components.sidebar')

        {{-- Main Area --}}
        <div class="lg:pl-64 flex min-h-screen flex-col">
            @include('employer.components.navbar')

            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) window.lucide.createIcons();
        });
    </script>

    <style>
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>

</body>

</html>