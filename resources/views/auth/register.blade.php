<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Worksite') }} | Register</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-900 overflow-hidden">
    {{-- subtle background accents --}}
    <div class="pointer-events-none fixed -top-28 -left-28 h-80 w-80 rounded-full bg-[#16A34A]/15 blur-3xl"></div>
    <div class="pointer-events-none fixed -bottom-28 -right-28 h-80 w-80 rounded-full bg-[#16A34A]/10 blur-3xl"></div>

    <main class="min-h-screen flex items-center justify-center px-4 py-6">
        <div x-data="registerForm()" class="w-full max-w-sm sm:max-w-md rounded-3xl border border-gray-200 bg-white shadow-lg overflow-hidden">

            {{-- Header --}}
            <div class="px-6 pt-6 pb-4 text-center">
                <div class="mx-auto w-11 h-11 rounded-2xl bg-[#16A34A] flex items-center justify-center shadow-sm">
                    <i data-lucide="briefcase" class="w-5 h-5 text-white"></i>
                </div>

                <h1 class="mt-3 text-xl font-bold text-gray-900">Create Account</h1>
                <p class="mt-1 text-sm text-gray-600">Register to start using Worksite</p>

                {{-- Role toggle --}}
                <div class="mt-4 text-left">
                    <p class="text-xs font-semibold text-gray-500 mb-2">Register as</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="role='candidate'; resetForm()"
                                :class="role==='candidate'
                                    ? 'bg-[#16A34A] text-white border-[#16A34A]'
                                    : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300'"
                                class="rounded-xl border px-3 py-2.5 text-sm font-semibold transition">
                            Candidate
                        </button>

                        <button type="button" @click="role='employer'; resetForm()"
                                :class="role==='employer'
                                    ? 'bg-[#16A34A] text-white border-[#16A34A]'
                                    : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300'"
                                class="rounded-xl border px-3 py-2.5 text-sm font-semibold transition">
                            Employer
                        </button>
                    </div>

                    <p class="mt-2 text-xs text-gray-500 text-center">
                        <span x-show="role==='candidate'">Candidate registration is not available yet.</span>
                        <span x-show="role==='employer'">Post jobs and manage applicants easily.</span>
                    </p>
                </div>
            </div>

            {{-- Form --}}
            <div class="px-6 pb-5">
                <form method="POST"
                      :action="role==='employer' ? '{{ route('employer.register') }}' : '#'"
                      class="space-y-3">
                    @csrf

                    {{-- Full Name --}}
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Full Name</label>
                        <div class="mt-2 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </span>
                            <input type="text" name="name" placeholder="Juan Dela Cruz" required
                                   class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mt-3">
                        <label class="text-sm font-semibold text-gray-700">Email Address</label>
                        <div class="mt-2 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </span>
                            <input type="email" name="email" placeholder="you@example.com" required
                                   class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="mt-3">
                        <label class="text-sm font-semibold text-gray-700">Password</label>
                        <div class="mt-2 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </span>
                            <input :type="showPass ? 'text' : 'password'" name="password" placeholder="••••••••" required
                                   class="w-full rounded-xl border border-gray-200 pl-11 pr-12 py-2.5 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                            <button type="button" @click="showPass=!showPass"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i data-lucide="eye" class="w-5 h-5" x-show="!showPass"></i>
                                <i data-lucide="eye-off" class="w-5 h-5" x-show="showPass"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mt-3">
                        <label class="text-sm font-semibold text-gray-700">Confirm Password</label>
                        <div class="mt-2 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i data-lucide="check" class="w-5 h-5"></i>
                            </span>
                            <input :type="showPass ? 'text' : 'password'" name="password_confirmation"
                                   placeholder="••••••••" required
                                   class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                        </div>
                    </div>

                    {{-- Hidden role --}}
                    <input type="hidden" name="role" :value="role">

                    {{-- Notice for candidate --}}
                    <p x-show="role === 'candidate'" class="text-xs text-red-500 mt-1">
                        Candidate registration is not available yet.
                    </p>

                    {{-- Submit --}}
                    <button type="submit"
                            :disabled="role === 'candidate'"
                            class="w-full rounded-xl bg-[#16A34A] py-2.5 text-sm font-semibold text-white
                                   hover:bg-green-700 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        Create Account
                    </button>

                    {{-- Already have account --}}
                    <p class="text-center text-sm text-gray-600 pt-3">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-[#16A34A] hover:underline">
                            Sign in
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </main>

    <script>
        function registerForm() {
            return {
                role: 'employer', // default to employer
                showPass: false,
                resetForm() {
                    this.$root.querySelector('form').reset();
                    this.showPass = false;
                }
            }
        }

        lucide.createIcons();
    </script>
</body>
</html>