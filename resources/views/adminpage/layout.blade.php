<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin')</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- Alpine --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- Google Font --}}
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  {{-- Notyf (toast) --}}
  <link rel="stylesheet" href="https://unpkg.com/notyf/notyf.min.css">
  <script defer src="https://unpkg.com/notyf/notyf.min.js"></script>

  {{-- Global toast helper --}}
  <script defer>
    document.addEventListener('DOMContentLoaded', () => {
      if (!window.Notyf) return;

      window.notyf = new window.Notyf({
        duration: 1800,
        ripple: true,
        dismissible: true,
        position: { x: 'right', y: 'top' },
      });

      window.notify = (type, message, title = '') => {
        if (!window.notyf) return;

        const msg = title ? `${title}: ${message}` : message;

        if (type === 'success') return window.notyf.success(msg);
        if (type === 'error') return window.notyf.error(msg);
        if (type === 'warning') return window.notyf.error(msg);

        return window.notyf.success(msg);
      };
    });
  </script>
</head>
