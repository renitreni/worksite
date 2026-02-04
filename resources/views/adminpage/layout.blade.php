<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin')</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body class="bg-slate-50 text-slate-900 font-['Inter',sans-serif]">

  <div x-data="{ sidebarOpen:false }" class="flex min-h-screen">

    @include('adminpage.components.sidebar')

    <main class="flex-1 min-w-0">

      @include('adminpage.components.navbar')

      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @yield('content')
      </div>

    </main>

  </div>

</body>

</html>