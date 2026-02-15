<!DOCTYPE html>
<html lang="en">

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

<body class="font-['Inter',sans-serif] text-gray-800">
    @include('mainpage.components.navbar')

    {{-- ✅ Search Bar Partial --}}
    @include('mainpage.search-jobs-page.partials-search-country._search-bar')

    @include('mainpage.search-jobs-page.partials-search-country._results')

    @include('mainpage.components.footer')

    <script>
        lucide.createIcons();
    </script>

</body>
</html>
