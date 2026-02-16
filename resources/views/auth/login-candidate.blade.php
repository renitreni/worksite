<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Worksite') }} | Candidate Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="font-['Inter',sans-serif] bg-gray-50 text-gray-900 overflow-hidden">
    <div class="pointer-events-none fixed -top-32 -left-32 h-96 w-96 rounded-full bg-[#16A34A]/15 blur-3xl"></div>
    <div class="pointer-events-none fixed -bottom-32 -right-32 h-96 w-96 rounded-full bg-[#16A34A]/10 blur-3xl"></div>

    <div class="hidden md:block fixed top-4 left-20 z-50">
        <x-back-button />
    </div>

    <main class="min-h-screen flex items-center justify-center px-4 py-6">
        <div x-data="candidateLogin()"
            class="w-full max-w-sm md:max-w-md rounded-3xl border border-gray-200 bg-white shadow-lg overflow-hidden">

            <div x-cloak x-show="isSubmitting"
                class="fixed inset-0 z-[99997] flex items-center justify-center bg-black/30 backdrop-blur-sm"
                aria-live="polite" aria-busy="true">
                <div class="w-[92%] max-w-sm rounded-2xl bg-white shadow-xl border border-gray-200 p-5 text-center">
                    <div
                        class="mx-auto h-10 w-10 rounded-full border-4 border-gray-200 border-t-[#16A34A] animate-spin">
                    </div>
                    <p class="mt-4 text-sm font-semibold text-gray-900">Signing you in…</p>
                    <p class="mt-1 text-xs text-gray-600">Please wait.</p>
                </div>
            </div>

            <div class="px-6 pt-6 pb-4 text-center">
                <div class="inline-flex items-center gap-2 rounded-full border border-green-100 bg-green-50 px-3 py-1">
                    <i data-lucide="user" class="w-4 h-4 text-[#16A34A]"></i>
                    <span class="text-xs font-semibold text-green-900">Candidate Login</span>
                </div>

                <h1 class="mt-3 text-xl font-bold text-gray-900">Welcome Back</h1>
                <p class="mt-1 text-sm text-gray-600">Sign in to continue your job search</p>
            </div>

            <div class="px-6 pb-6">
                <form x-ref="loginForm" @submit.prevent="submitForm" method="POST"
                    action="{{ route('candidate.login.store') }}" class="space-y-3.5">

                    @csrf

                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Email Address</label>
                        <div class="mt-2 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                placeholder="you@example.com" class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-gray-700">Password</label>
                            <a href="#" class="text-xs font-semibold text-[#16A34A] hover:underline">Forgot
                                password?</a>
                        </div>

                        <div class="mt-2 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </span>

                            <input :type="showPass ? 'text' : 'password'" name="password" required
                                placeholder="••••••••" class="w-full rounded-xl border border-gray-200 pl-11 pr-12 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">

                            <button type="button" @click="showPass=!showPass"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i data-lucide="eye" class="w-5 h-5" x-show="!showPass"></i>
                                <i data-lucide="eye-off" class="w-5 h-5" x-show="showPass"></i>
                            </button>


                        </div>
                    </div>

                    <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember"
                            class="rounded border-gray-300 text-[#16A34A] focus:ring-[#16A34A]/30">
                        Remember me
                    </label>

                    <button type="submit" :disabled="isSubmitting"
                        :class="isSubmitting ? 'bg-gray-300 cursor-not-allowed' : 'bg-[#16A34A] hover:bg-green-700'"
                        class="w-full rounded-xl py-2.5 text-sm font-semibold text-white transition shadow-sm
           inline-flex items-center justify-center gap-2">

                        <svg x-show="isSubmitting" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>

                        <span x-text="isSubmitting ? 'Signing in…' : 'Sign In'"></span>
                    </button>


                    <p class="text-center text-sm text-gray-600 pt-1">
                        Don’t have an account?
                        <a href="{{ route('candidate.register') }}"
                            class="font-semibold text-[#16A34A] hover:underline">
                            Register as Candidate
                        </a>
                    </p>

                </form>
            </div>

            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-[11px] text-gray-500 leading-relaxed">
                    By signing in, you agree to our
                    <a href="#" class="text-[#16A34A] font-semibold hover:underline">Terms</a>
                    and
                    <a href="#" class="text-[#16A34A] font-semibold hover:underline">Privacy Policy</a>.
                </p>
            </div>
        </div>
    </main>

    <script>lucide.createIcons();</script>
    <script>
        window.__unverified = {
            show: {{ session('unverified_modal') ? 'true' : 'false' }},
            email: "{{ session('verify_email') ?? '' }}",
        };
    </script>

    <div x-data="{ open: false }" x-init="open = window.__unverified.show" x-show="open" x-transition.opacity
        class="fixed inset-0 z-[99998] flex items-center justify-center px-4" style="display:none;">
        <div class="absolute inset-0 bg-black/40" @click="open=false"></div>

        <div class="relative w-full max-w-md rounded-3xl bg-white shadow-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Email not verified</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Your account needs email verification before you can access the dashboard.
                    </p>
                </div>

                <button type="button" @click="closeInfo()"
                    class="w-10 h-10 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 flex items-center justify-center transition">
                    <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                </button>
            </div>

            <div class="mt-4 rounded-2xl border border-green-100 bg-green-50 p-4">
                <p class="text-sm text-green-900">
                    We can send a verification code to:
                <p class="text-sm text-green-900 font-semibold mt-1">
                    Email: <span class="font-semibold" x-text="window.__unverified.email"></span>
                </p>
                </p>
            </div>

            <div class="mt-6 flex items-center gap-2">
                <button type="button" @click="open=false; window.dispatchEvent(new CustomEvent('open-verify-modal'))"
                    class="w-1/2 rounded-xl bg-[#16A34A] py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">
                    Verify your account
                </button>

                <button type="button" @click="open=false"
                    class="w-1/2 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    Not now
                </button>
            </div>

            <p class="mt-4 text-[11px] text-gray-500">
                Tip: Check spam/junk if the email doesn’t arrive.
            </p>
        </div>
    </div>
    @include('auth.partials-candidate.email-verification-modal')

    <script>
        function candidateLogin() {
            return {
                showPass: false,
                isSubmitting: false,

                submitForm() {
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;

                    // update icons if needed
                    setTimeout(() => lucide.createIcons(), 0);

                    this.$refs.loginForm.submit();
                }
            }
        }
    </script>



</body>

</html>