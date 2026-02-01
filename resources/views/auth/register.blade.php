<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Worksite') }} | Register</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Lucide --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- Alpine --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-900 overflow-hidden">
    {{-- subtle background accents --}}
    <div class="pointer-events-none fixed -top-28 -left-28 h-80 w-80 rounded-full bg-[#16A34A]/15 blur-3xl"></div>
    <div class="pointer-events-none fixed -bottom-28 -right-28 h-80 w-80 rounded-full bg-[#16A34A]/10 blur-3xl"></div>

    {{-- Back button (hidden on mobile) --}}
    <div class="hidden md:block fixed top-4 left-20 z-50">
        <x-back-button />
    </div>

    <main class="min-h-screen flex items-center justify-center px-4 py-6">
        <div x-data="register3Step()"
            class="w-full max-w-sm sm:max-w-md rounded-3xl border border-gray-200 bg-white shadow-lg overflow-hidden">

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
                        <button type="button"
                            @click="role='candidate'; resetForm()"
                            :class="role==='candidate'
                                ? 'bg-[#16A34A] text-white border-[#16A34A]'
                                : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300'"
                            class="rounded-xl border px-3 py-2.5 text-sm font-semibold transition">
                            Candidate
                        </button>

                        <button type="button"
                            @click="role='employer'; resetForm()"
                            :class="role==='employer'
                                ? 'bg-[#16A34A] text-white border-[#16A34A]'
                                : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300'"
                            class="rounded-xl border px-3 py-2.5 text-sm font-semibold transition">
                            Employer
                        </button>
                    </div>

                    <p class="mt-2 text-xs text-gray-500 text-center">
                        <span x-show="role==='candidate'">Create a profile and apply to verified jobs.</span>
                        <span x-show="role==='employer'">Post jobs and manage applicants easily.</span>
                    </p>
                </div>

                {{-- Stepper --}}
                <div class="mt-4">
                    <div class="flex items-center justify-center gap-2">
                        <span class="h-2 w-10 rounded-full" :class="step===1 ? 'bg-[#16A34A]' : 'bg-gray-200'"></span>
                        <span class="h-2 w-10 rounded-full" :class="step===2 ? 'bg-[#16A34A]' : 'bg-gray-200'"></span>
                        <span class="h-2 w-10 rounded-full" :class="step===3 ? 'bg-[#16A34A]' : 'bg-gray-200'"></span>
                    </div>

                    <p class="mt-2 text-xs text-gray-500">
                        <span x-show="step===1">Step 1: Personal</span>
                        <span x-show="step===2">Step 2: Contact</span>
                        <span x-show="step===3">Step 3: Security</span>
                    </p>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 pb-5">
                <form method="POST" action="#" class="space-y-3">
                    @csrf

                    {{-- STEP 1: Personal --}}
                    <div x-show="step===1" x-transition.opacity>
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

                        <div class="mt-4 flex items-center gap-2">
                            <a href="{{ route('login') }}"
                                class="w-1/2 text-center rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Back
                            </a>
                            <button type="button" @click="goNext(1)"
                                class="w-1/2 rounded-xl bg-[#16A34A] py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">
                                Continue
                            </button>
                        </div>
                    </div>

                    {{-- STEP 2: Contact --}}
                    <div x-show="step===2" x-transition.opacity>
                        {{-- Address --}}
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Address</label>
                            <div class="mt-2 relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                </span>
                                <input type="text" name="address" placeholder="Barangay, City, Province" required
                                    class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                            </div>
                        </div>

                        {{-- Mobile phone --}}
                        <div class="mt-3">
                            <label class="text-sm font-semibold text-gray-700">Mobile Phone</label>
                            <div class="mt-2 relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i data-lucide="phone" class="w-5 h-5"></i>
                                </span>
                                <input type="tel" name="mobile" placeholder="09XX XXX XXXX" required
                                    class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">We’ll use this for updates and verification.</p>
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <button type="button" @click="step=1"
                                class="w-1/2 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Back
                            </button>
                            <button type="button" @click="goNext(2)"
                                class="w-1/2 rounded-xl bg-[#16A34A] py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">
                                Continue
                            </button>
                        </div>
                    </div>

                    {{-- STEP 3: Security --}}
                    <div x-show="step===3" x-transition.opacity>
                        {{-- Password --}}
                        <div>
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
                            <p class="mt-1 text-xs text-gray-500">At least 8 characters recommended.</p>
                        </div>

                        {{-- Confirm --}}
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

                        <input type="hidden" name="role" :value="role">

                        <div class="mt-4 flex items-center gap-2">
                            <button type="button" @click="step=2"
                                class="w-1/2 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Back
                            </button>

                            <button type="submit"
                                class="w-1/2 rounded-xl bg-[#16A34A] py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">
                                Create Account
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Always visible (ALL steps) --}}
                <p class="text-center text-sm text-gray-600 pt-3">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-[#16A34A] hover:underline">
                        Sign in
                    </a>
                </p>
            </div>

            {{-- Footer note --}}
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-[11px] text-gray-500 leading-relaxed">
                    By creating an account, you agree to our
                    <a href="#" class="text-[#16A34A] font-semibold hover:underline">Terms</a>
                    and
                    <a href="#" class="text-[#16A34A] font-semibold hover:underline">Privacy Policy</a>.
                </p>
            </div>
        </div>
    </main>

    <script>
        function register3Step() {
            return {
                step: 1,
                role: 'candidate',
                showPass: false,

                resetForm() {
                    this.step = 1
                    this.showPass = false
                    this.$root.querySelector('form').reset()
                },

                goNext(fromStep) {
                    const form = this.$root.querySelector('form')
                    const map = {
                        1: ['name', 'email'],
                        2: ['address', 'mobile'],
                    }

                    let ok = true
                    ;(map[fromStep] || []).forEach((name) => {
                        const el = form.querySelector(`[name="${name}"]`)
                        if (!el || !el.value.trim()) ok = false
                    })

                    if (!ok) return

                    this.step = Math.min(3, fromStep + 1)
                }
            }
        }

        lucide.createIcons()
    </script>
</body>

</html>
