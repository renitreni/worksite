<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Worksite') }} | Candidate Register</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.15.0/build/css/intlTelInput.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .iti {
            width: 100%;
            display: block;
        }

        .iti input,
        .iti__tel-input {
            width: 100% !important;
            height: 44px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding-left: 90px !important;
            padding-right: 12px;
            font-size: 14px;
            outline: none;
            background: #fff;
        }

        .iti input:focus {
            border-color: #16A34A;
            box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
        }

        .iti__flag-container {
            border-radius: 12px 0 0 12px;
        }

        .iti__country-list {
            max-height: 260px;
            overflow-y: auto;
            border-radius: 12px;
            z-index: 99999 !important;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="font-['Inter',sans-serif] bg-gray-50 text-gray-900 overflow-x-hidden">
    <!-- subtle bg -->
    <div class="pointer-events-none fixed -top-28 -left-28 h-80 w-80 rounded-full bg-[#16A34A]/12 blur-3xl"></div>
    <div class="pointer-events-none fixed -bottom-28 -right-28 h-80 w-80 rounded-full bg-[#16A34A]/10 blur-3xl"></div>

    <div class="hidden md:block fixed top-4 left-20 z-50">
        <x-back-button />
    </div>

    <main class="min-h-screen flex items-center justify-center px-4 py-8">
        @include('auth.partials-candidate._form')
    </main>

    @include("auth.partials-candidate.email-verification-modal")

    @include('auth.partials-candidate._scripts')
</body>

</html>