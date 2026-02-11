<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Jobs | Worksite</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        html, body { overflow-x: hidden; }
    </style>
</head>

<body class="font-['Inter',sans-serif] bg-gray-50 text-gray-800">
    @include('mainpage.components.navbar')

    {{-- ✅ Search Bar Partial --}}
    @include('mainpage.search-jobs-page.partials._search-bar')

    {{-- ✅ Results Partial --}}
    @include('mainpage.search-jobs-page.partials._results')

    @include('mainpage.components.footer')

    <script>
        lucide.createIcons();
    </script>

</body>
</html>
