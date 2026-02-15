<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Worksite') }} | Employer Register</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide + Alpine -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Intl Tel Input (CSS only) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.15.0/build/css/intlTelInput.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* phone input matches your Tailwind input */
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
    </style>
</head>

<!-- ✅ keep dropdown working on mobile -->

<body class="font-['Inter',sans-serif] bg-gray-50 text-gray-900 overflow-x-hidden">
    <div class="pointer-events-none fixed -top-28 -left-28 h-80 w-80 rounded-full bg-[#16A34A]/15 blur-3xl"></div>
    <div class="pointer-events-none fixed -bottom-28 -right-28 h-80 w-80 rounded-full bg-[#16A34A]/10 blur-3xl"></div>

    <div class="hidden md:block fixed top-4 left-20 z-50">
        <x-back-button />
    </div>

    <main class="min-h-screen flex items-center justify-center px-4 py-6">
        <!-- ✅ removed overflow-hidden so dropdown isn't clipped -->
        <div x-data="employerRegister()"
            class="w-full max-w-sm sm:max-w-md rounded-3xl border border-gray-200 bg-white shadow-lg">

            {{-- Header --}}
            <div class="px-6 pt-6 pb-4 text-center">
                <div class="inline-flex items-center gap-2 rounded-full border border-green-100 bg-green-50 px-3 py-1">
                    <i data-lucide="building-2" class="w-4 h-4 text-[#16A34A]"></i>
                    <span class="text-xs font-semibold text-green-900">Employer Registration</span>
                </div>

                <h1 class="mt-3 text-xl font-bold text-gray-900">Create Employer Account</h1>
                <p class="mt-1 text-sm text-gray-600">Register to post jobs and manage applicants</p>

                {{-- Stepper (4) --}}
                <div class="mt-4">
                    <div class="flex items-center justify-center gap-2">
                        <span class="h-2 w-8 rounded-full" :class="step===1 ? 'bg-[#16A34A]' : 'bg-gray-200'"></span>
                        <span class="h-2 w-8 rounded-full" :class="step===2 ? 'bg-[#16A34A]' : 'bg-gray-200'"></span>
                        <span class="h-2 w-8 rounded-full" :class="step===3 ? 'bg-[#16A34A]' : 'bg-gray-200'"></span>
                        <span class="h-2 w-8 rounded-full" :class="step===4 ? 'bg-[#16A34A]' : 'bg-gray-200'"></span>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        <span x-show="step===1">Step 1: Company</span>
                        <span x-show="step===2">Step 2: Company Contact</span>
                        <span x-show="step===3">Step 3: Representative</span>
                        <span x-show="step===4">Step 4: Security</span>
                    </p>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 pb-5">
                <form id="empForm" method="POST" action="{{ route('employer.register.store') }}" class="space-y-3">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- ✅ store E.164 here too -->
                    <input type="hidden" name="company_contact_e164" id="company_contact_e164">

                    {{-- STEP 1 --}}
                    <div x-show="step===1" x-transition.opacity>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Company Name</label>
                            <div class="mt-2 relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                </span>
                                <input type="text" name="company_name" placeholder="Worksite Recruitment Inc." required
                                    class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="text-sm font-semibold text-gray-700">Company Email</label>
                            <div class="mt-2 relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                </span>
                                <input type="email" name="company_email" placeholder="hr@company.com" required class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <a href="{{ route('employer.login') }}"
                                class="w-1/2 text-center rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Back
                            </a>
                            <button type="button" @click="goNext()"
                                class="w-1/2 rounded-xl bg-[#16A34A] py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">
                                Continue
                            </button>
                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div x-show="step===2" x-transition.opacity>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Company Address</label>
                            <div class="mt-2 relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                </span>
                                <input type="text" name="company_address" placeholder="Barangay, City, Province"
                                    required class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="text-sm font-semibold text-gray-700">Contact / Tel Number</label>

                            <!-- ✅ intl-tel-input will convert this into flag dropdown + dial code -->
                            <input id="employer_phone" type="tel" name="company_contact" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm
  focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]" placeholder="Enter your number"
                                required>



                            <p class="mt-2 text-[11px] text-gray-500">Tap the flag to choose country code.</p>
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <button type="button" @click="step=1"
                                class="w-1/2 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Back
                            </button>
                            <button type="button" @click="goNext()"
                                class="w-1/2 rounded-xl bg-[#16A34A] py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">
                                Continue
                            </button>
                        </div>
                    </div>

                    {{-- STEP 3 --}}
                    <div x-show="step===3" x-transition.opacity>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Representative Name</label>
                            <div class="mt-2 relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i data-lucide="user" class="w-5 h-5"></i>
                                </span>
                                <input type="text" name="representative_name" placeholder="Maria Santos" required class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="text-sm font-semibold text-gray-700">Position</label>
                            <div class="mt-2 relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i data-lucide="briefcase" class="w-5 h-5"></i>
                                </span>
                                <input type="text" name="position" placeholder="HR Manager / Recruiter" required class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <button type="button" @click="step=2"
                                class="w-1/2 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Back
                            </button>
                            <button type="button" @click="goNext()"
                                class="w-1/2 rounded-xl bg-[#16A34A] py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">
                                Continue
                            </button>
                        </div>
                    </div>

                    {{-- STEP 4 --}}
                    <div x-show="step===4" x-transition.opacity>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Password</label>
                            <div class="mt-2 relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i data-lucide="lock" class="w-5 h-5"></i>
                                </span>

                                <input x-model="password" :type="showPass ? 'text' : 'password'" name="password"
                                    placeholder="Create a strong password" required class="w-full rounded-xl border border-gray-200 pl-11 pr-12 py-2.5 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">

                                <button type="button" @click="showPass=!showPass; refreshIcons()"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition"
                                    aria-label="Toggle password visibility">
                                    <i data-lucide="eye" class="w-5 h-5" x-show="!showPass"></i>
                                    <i data-lucide="eye-off" class="w-5 h-5" x-show="showPass"></i>
                                </button>
                            </div>

                            <!-- ✅ same password rules as candidate -->
                            <div class="mt-3 rounded-xl border border-gray-200 bg-gray-50 p-3">
                                <p class="text-[11px] font-semibold text-gray-700 mb-2">Password must include:</p>

                                <div class="space-y-1 text-[11px]">
                                    <div class="flex items-center gap-2"
                                        :class="ruleLen ? 'text-green-700' : 'text-red-600'">
                                        <i data-lucide="check-circle" class="w-4 h-4" x-show="ruleLen"></i>
                                        <i data-lucide="x-circle" class="w-4 h-4" x-show="!ruleLen"></i>
                                        <span>At least 8 characters</span>
                                    </div>

                                    <div class="flex items-center gap-2"
                                        :class="ruleUpper ? 'text-green-700' : 'text-red-600'">
                                        <i data-lucide="check-circle" class="w-4 h-4" x-show="ruleUpper"></i>
                                        <i data-lucide="x-circle" class="w-4 h-4" x-show="!ruleUpper"></i>
                                        <span>1 uppercase letter (A–Z)</span>
                                    </div>

                                    <div class="flex items-center gap-2"
                                        :class="ruleLower ? 'text-green-700' : 'text-red-600'">
                                        <i data-lucide="check-circle" class="w-4 h-4" x-show="ruleLower"></i>
                                        <i data-lucide="x-circle" class="w-4 h-4" x-show="!ruleLower"></i>
                                        <span>1 lowercase letter (a–z)</span>
                                    </div>

                                    <div class="flex items-center gap-2"
                                        :class="ruleSymbol ? 'text-green-700' : 'text-red-600'">
                                        <i data-lucide="check-circle" class="w-4 h-4" x-show="ruleSymbol"></i>
                                        <i data-lucide="x-circle" class="w-4 h-4" x-show="!ruleSymbol"></i>
                                        <span>1 symbol (e.g. ! @ # $ %)</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="text-sm font-semibold text-gray-700">Confirm Password</label>
                            <div class="mt-2 relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i data-lucide="check" class="w-5 h-5"></i>
                                </span>
                                <input x-model="confirmPassword" :type="showPass ? 'text' : 'password'"
                                    name="password_confirmation" placeholder="Re-type your password" required class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                            </div>

                            <p class="mt-2 text-[11px]"
                                :class="confirmPassword.length === 0 ? 'text-gray-500' : (passwordsMatch ? 'text-green-700' : 'text-red-600')">
                                <span x-show="confirmPassword.length === 0">Re-type your password to confirm.</span>
                                <span x-show="confirmPassword.length > 0 && passwordsMatch">Passwords match.</span>
                                <span x-show="confirmPassword.length > 0 && !passwordsMatch">Passwords do not
                                    match.</span>
                            </p>
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <button type="button" @click="step=3"
                                class="w-1/2 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Back
                            </button>

                            <button type="submit" :disabled="!canSubmit"
                                :class="canSubmit ? 'bg-[#16A34A] hover:bg-green-700' : 'bg-gray-300 cursor-not-allowed'"
                                class="w-1/2 rounded-xl py-2.5 text-sm font-semibold text-white transition">
                                Create Account
                            </button>
                        </div>
                    </div>
                </form>

                <p class="text-center text-sm text-gray-600 pt-3">
                    Already have an account?
                    <a href="{{ route('employer.login') }}" class="font-semibold text-[#16A34A] hover:underline">Sign in</a>
                </p>
            </div>

            <div class="px-6 py-3 rounded-b-3xl bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-[11px] text-gray-500 leading-relaxed">
                    By creating an account, you agree to our
                    <a href="#" class="text-[#16A34A] font-semibold hover:underline">Terms</a> and
                    <a href="#" class="text-[#16A34A] font-semibold hover:underline">Privacy Policy</a>.
                </p>
            </div>
        </div>
    </main>

    <!-- Intl Tel Input JS (✅ ONLY THIS, no utils.js script tag) -->
    <script>
        function employerRegister() {
            return {
                step: 1,
                showPass: false,

                // password UI state
                password: '',
                confirmPassword: '',

                refreshIcons() {
                    setTimeout(() => lucide.createIcons(), 0);
                },

                // password rules
                get ruleLen() { return this.password.length >= 8; },
                get ruleUpper() { return /[A-Z]/.test(this.password); },
                get ruleLower() { return /[a-z]/.test(this.password); },
                get ruleSymbol() { return /[^A-Za-z0-9]/.test(this.password); },

                get passwordsMatch() {
                    return this.password.length > 0 && this.password === this.confirmPassword;
                },

                get canSubmit() {
                    return this.ruleLen && this.ruleUpper && this.ruleLower && this.ruleSymbol && this.passwordsMatch;
                },

                goNext() {
                    const form = this.$root.querySelector('form')
                    const required = {
                        1: ['company_name', 'company_email'],
                        2: ['company_address', 'company_contact'],
                        3: ['representative_name', 'position'],
                    }
                    const fields = required[this.step] || []

                    let ok = true
                    fields.forEach((name) => {
                        const el = form.querySelector(`[name="${name}"]`)
                        if (!el || !el.value.trim()) ok = false
                    })
                    if (!ok) return

                    this.step = Math.min(4, this.step + 1)
                    this.refreshIcons()
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.15.0/build/js/intlTelInput.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            lucide.createIcons();

            const input = document.querySelector('#employer_phone');
            const hidden = document.querySelector('#company_contact_e164');

            if (!input || !hidden) return;

            const iti = window.intlTelInput(input, {
                initialCountry: 'ph',
                separateDialCode: true,
                nationalMode: true,
                dropdownContainer: document.body,
                autoPlaceholder: 'aggressive',
                formatOnDisplay: true,
            });

            // Try load utils
            let utilsReady = false;
            if (typeof iti.loadUtils === 'function') {
                try {
                    await iti.loadUtils('https://cdn.jsdelivr.net/npm/intl-tel-input@25.15.0/build/js/utils.js');
                    utilsReady = true;
                } catch (e) {
                    utilsReady = false;
                    console.warn('Employer utils failed, using fallback e164', e);
                }
            }

            function fallbackE164() {
                const dialCode = iti.getSelectedCountryData()?.dialCode || '';
                let digits = (input.value || '').replace(/\D/g, '');
                if (digits.startsWith('0')) digits = digits.slice(1);
                if (!dialCode || !digits) return '';
                return `+${dialCode}${digits}`;
            }

            function sync() {
                let e164 = '';
                if (utilsReady) e164 = iti.getNumber() || '';
                if (!e164) e164 = fallbackE164();
                hidden.value = e164;
            }

            input.addEventListener('input', sync);
            input.addEventListener('blur', sync);
            input.addEventListener('countrychange', sync);

            sync();
        });
    </script>



</body>

</html>