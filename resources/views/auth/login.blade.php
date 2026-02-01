<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Worksite') }} | Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-900 overflow-hidden">
    {{-- subtle background accents --}}
    <div class="pointer-events-none fixed -top-32 -left-32 h-96 w-96 rounded-full bg-[#16A34A]/15 blur-3xl"></div>
    <div class="pointer-events-none fixed -bottom-32 -right-32 h-96 w-96 rounded-full bg-[#16A34A]/10 blur-3xl"></div>

    <div class="hidden md:block fixed top-4 left-20 z-50">
        <x-back-button />
    </div>




    {{-- Full page wrapper (NO page scroll) --}}
    <main class="min-h-screen flex items-center justify-center px-4 py-6">
        <div x-data="{ role: 'candidate', showPass: false }" class="w-full max-w-sm md:max-w-md rounded-3xl border border-gray-200 bg-white shadow-lg overflow-hidden
                   max-h-[calc(100vh-4rem)]">
            {{-- Internal scroll only if needed (page stays fixed) --}}
            <div class="overflow-auto max-h-[calc(100vh-4rem)]">
                {{-- Header --}}
                <div class="px-6 pt-6 pb-4 text-center">
                    <div class="mx-auto w-11 h-11 rounded-2xl bg-[#16A34A] flex items-center justify-center shadow-sm">
                        <i data-lucide="briefcase" class="w-5 h-5 text-white"></i>
                    </div>

                    <h1 class="mt-3 text-xl font-bold text-gray-900">Welcome Back</h1>
                    <p class="mt-1 text-sm text-gray-600">Sign in to your Worksite account</p>

                    {{-- Role Toggle --}}
                    <div class="mt-5 text-left">
                        <p class="text-xs font-semibold text-gray-500 mb-2">I am a</p>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" @click="role='candidate'" :class="role==='candidate'
                                    ? 'bg-[#16A34A] text-white border-[#16A34A]'
                                    : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300'"
                                class="rounded-xl border px-3 py-2.5 text-sm font-semibold transition">
                                Candidate
                            </button>

                            <button type="button" @click="role='employer'" :class="role==='employer'
                                    ? 'bg-[#16A34A] text-white border-[#16A34A]'
                                    : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300'"
                                class="rounded-xl border px-3 py-2.5 text-sm font-semibold transition">
                                Employer
                            </button>
                        </div>

                        <p class="mt-2 text-xs text-gray-500 text-center">
                            <span x-show="role==='candidate'">Find verified jobs and track applications.</span>
                            <span x-show="role==='employer'">Post jobs and manage applicants.</span>
                        </p>
                    </div>
                </div>

                {{-- Form --}}
                <div class="px-6 pb-6">
                    <form method="POST" action="#" class="space-y-3.5">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Email Address</label>
                            <div class="mt-2 relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                </span>
                                <input type="email" name="email" placeholder="you@example.com"
                                    class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]" required>
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

                                <button type="button" @click="showPass = !showPass"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                    <i data-lucide="eye" class="w-5 h-5" x-show="!showPass"></i>
                                    <i data-lucide="eye-off" class="w-5 h-5" x-show="showPass"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Remember --}}
                        <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox"
                                class="rounded border-gray-300 text-[#16A34A] focus:ring-[#16A34A]/30">
                            Remember me
                        </label>

                        {{-- Submit --}}
                        <button type="submit" class="w-full rounded-xl bg-[#16A34A] py-2.5 text-sm font-semibold text-white
                                   hover:bg-green-700 transition shadow-sm">
                            Sign In
                        </button>

                        {{-- Register --}}
                        <p class="text-center text-sm text-gray-600 pt-1">
                            Don’t have an account?
                            <a href="{{ route('register') }}" class="font-semibold text-[#16A34A] hover:underline">
                                Register here
                            </a>
                        </p>

                        <input type="hidden" name="role" :value="role">
                    </form>
                </div>

                {{-- Footer note --}}
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 text-center">
                    <p class="text-[11px] text-gray-500 leading-relaxed">
                        By signing in, you agree to our
                        <a href="#" class="text-[#16A34A] font-semibold hover:underline">Terms</a>
                        and
                        <a href="#" class="text-[#16A34A] font-semibold hover:underline">Privacy Policy</a>.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>