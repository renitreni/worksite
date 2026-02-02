<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900">
  <div class="flex min-h-screen">
    @include('adminpage.components.sidebar')

    <main class="flex-1">
      @include('adminpage.components.navbar')

      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @yield('content')
      </div>
    </main>
  </div>
</body>
</html>
