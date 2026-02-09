<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin')</title>

  @vite(['resources/css/app.css','resources/js/app.js'])

  {{-- Alpine --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- Notyf (toast) via CDN --}}
  <link rel="stylesheet" href="https://unpkg.com/notyf/notyf.min.css">
  <script defer src="https://unpkg.com/notyf/notyf.min.js"></script>

  {{-- Create a global toast helper --}}
  <script defer>
    document.addEventListener('DOMContentLoaded', () => {
      // If Notyf CDN failed, don't crash pages
      if (!window.Notyf) {
        console.warn('Notyf is not loaded.');
        return;
      }

      // Global instance (use anywhere)
      window.notyf = new window.Notyf({
        duration: 1800,
        ripple: true,
        dismissible: true,
        position: { x: 'right', y: 'top' },
      });

      // Simple wrapper so your pages can call window.notify(...)
      // type: 'success' | 'error' | 'info' | 'warning'
      window.notify = (type, message, title = '') => {
        if (!window.notyf) return;

        const msg = title ? `${title}: ${message}` : message;

        if (type === 'success') return window.notyf.success(msg);
        if (type === 'error') return window.notyf.error(msg);

        // Notyf default doesn't have "info/warning" built-in styles unless you add custom types.
        // We'll show them as success/error style fallback:
        if (type === 'warning') return window.notyf.error(msg);
        return window.notyf.success(msg); // info fallback
      };
    });
  </script>
</head>

<body class="bg-slate-50 text-slate-900">

  <div x-data="{ sidebarOpen:false }" class="flex min-h-screen">

    @include('adminpage.components.sidebar')

    <main class="flex-1 min-w-0">

      @include('adminpage.components.navbar')

      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @yield('content')
      </div>

    </main>

  </div>

  {{-- Keep this if you want, but now your pages won't depend on it --}}
  <x-toast />
</body>
</html>
