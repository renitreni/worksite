<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    use App\Models\Setting;

    $siteName = Setting::get('site.name', config('app.name', 'JobAbroad'));
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Permissions-Policy" content="unload=(self)">

    <title>@yield('title', $siteName . ' Admin Dashboard')</title>

    <meta name="robots" content="noindex,nofollow">
    <meta name="referrer" content="no-referrer-when-downgrade">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ url()->current() }}">

    
    <link rel="icon" href="/favicon.ico">
    <link rel="icon" type="image/png" href="/images/favicon.png">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        window.userId = @json(auth('admin')->id());
    </script>
    @livewireStyles
</head>

<body class="bg-slate-50 text-slate-900 font-['Inter',sans-serif]">

    {{-- Shared Alpine state --}}
    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('adminpage.components.sidebar')

        {{-- Main content --}}
        <main class="flex-1 min-w-0">

            {{-- Navbar --}}
            @include('adminpage.components.navbar')

            {{-- Page content --}}
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @yield('content')
            </div>

        </main>
    </div>


    <script>
        document.addEventListener('livewire:navigated', () => {

            // Reinitialize Lucide icons
            if (window.lucide) {
                lucide.createIcons();
            }

            // Reinitialize charts if needed
            if (typeof initCharts === 'function') {
                initCharts();
            }

        });
    </script>
    @livewireScripts

</body>

</html>
