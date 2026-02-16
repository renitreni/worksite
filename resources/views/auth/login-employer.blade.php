<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Worksite') }} | Employer Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="font-['Inter',sans-serif] bg-gray-50 text-gray-900 overflow-hidden">
    {{-- background --}}
    <div class="pointer-events-none fixed -top-32 -left-32 h-96 w-96 rounded-full bg-[#16A34A]/15 blur-3xl"></div>
    <div class="pointer-events-none fixed -bottom-32 -right-32 h-96 w-96 rounded-full bg-[#16A34A]/10 blur-3xl"></div>

    <div class="hidden md:block fixed top-4 left-20 z-50">
        <x-back-button />
    </div>

    <main class="min-h-screen flex items-center justify-center px-4 py-6">
        <div x-data="employerLogin()"
            class="w-full max-w-sm md:max-w-md rounded-3xl border border-gray-200 bg-white shadow-lg overflow-hidden">

            <!-- ✅ Login Loading Overlay -->
            <div x-cloak x-show="isSubmitting"
                class="fixed inset-0 z-[99997] flex items-center justify-center bg-black/30 backdrop-blur-sm"
                aria-live="polite" aria-busy="true">
                <div class="w-[92%] max-w-sm rounded-2xl bg-white shadow-xl border border-gray-200 p-5 text-center">
                    <div class="mx-auto h-10 w-10 rounded-full border-4 border-gray-200 border-t-[#16A34A] animate-spin"></div>
                    <p class="mt-4 text-sm font-semibold text-gray-900">Signing you in…</p>
                    <p class="mt-1 text-xs text-gray-600">Please wait.</p>
                </div>
            </div>

            {{-- Header --}}
            <div class="px-6 pt-6 pb-4 text-center">
                <div class="inline-flex items-center gap-2 rounded-full border border-green-100 bg-green-50 px-3 py-1">
                    <i data-lucide="building-2" class="w-4 h-4 text-[#16A34A]"></i>
                    <span class="text-xs font-semibold text-green-900">Employer Login</span>
                </div>

                <h1 class="mt-3 text-xl font-bold text-gray-900">Welcome Back</h1>
                <p class="mt-1 text-sm text-gray-600">Login to manage jobs and applicants</p>
            </div>

            {{-- Form --}}
            <div class="px-6 pb-6">
                <form x-ref="loginForm" @submit.prevent="submitForm"
                    method="POST" action="{{ route('employer.login.store') }}" class="space-y-3.5">
                    @csrf

                    {{-- errors --}}
                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- Email --}}
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Company Email</label>
                        <div class="mt-2 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="hr@company.com"
                                class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]"
                                required autofocus>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-gray-700">Password</label>
                            <a href="#" class="text-xs font-semibold text-[#16A34A] hover:underline">
                                Forgot password?
                            </a>
                        </div>

                        <div class="mt-2 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </span>

                            <input :type="showPass ? 'text' : 'password'" name="password" placeholder="••••••••"
                                class="w-full rounded-xl border border-gray-200 pl-11 pr-12 py-2.5 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]"
                                required>

                            <button type="button" @click="showPass = !showPass; refreshIcons()"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i data-lucide="eye" class="w-5 h-5" x-show="!showPass"></i>
                                <i data-lucide="eye-off" class="w-5 h-5" x-show="showPass"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember --}}
                    <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember"
                            class="rounded border-gray-300 text-[#16A34A] focus:ring-[#16A34A]/30">
                        Remember me
                    </label>

                    {{-- Submit --}}
                    <button type="submit"
                        :disabled="isSubmitting"
                        :class="isSubmitting ? 'bg-gray-300 cursor-not-allowed' : 'bg-[#16A34A] hover:bg-green-700'"
                        class="w-full rounded-xl py-2.5 text-sm font-semibold text-white transition shadow-sm inline-flex items-center justify-center gap-2">

                        <svg x-show="isSubmitting" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>

                        <span x-text="isSubmitting ? 'Signing in…' : 'Sign In'"></span>
                    </button>

                    {{-- Register --}}
                    <p class="text-center text-sm text-gray-600 pt-1">
                        Don’t have an account?
                        <a href="{{ route('employer.register') }}" class="font-semibold text-[#16A34A] hover:underline">
                            Register as Employer
                        </a>
                    </p>
                </form>
            </div>

            {{-- Footer --}}
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

    <script>
        function employerLogin() {
            return {
                showPass: false,
                isSubmitting: false,

                refreshIcons() {
                    setTimeout(() => lucide.createIcons(), 0);
                },

                submitForm() {
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;
                    this.refreshIcons();
                    this.$refs.loginForm.submit();
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    </script>
</body>

</html>
