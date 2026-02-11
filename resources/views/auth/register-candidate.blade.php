<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Worksite') }} | Candidate Register</title>

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

<!-- ✅ removed overflow-hidden so dropdown works on mobile -->

<body class="font-['Inter',sans-serif] bg-gray-50 text-gray-900 overflow-x-hidden">
    <!-- subtle bg -->
    <div class="pointer-events-none fixed -top-28 -left-28 h-80 w-80 rounded-full bg-[#16A34A]/12 blur-3xl"></div>
    <div class="pointer-events-none fixed -bottom-28 -right-28 h-80 w-80 rounded-full bg-[#16A34A]/10 blur-3xl"></div>

    <div class="hidden md:block fixed top-4 left-20 z-50">
        <x-back-button />
    </div>

    <main class="min-h-screen flex items-center justify-center px-4 py-8">
        <!-- ✅ removed overflow-hidden so dropdown isn't clipped -->
        <div x-data="candidateRegisterBasic()"
            class="w-full max-w-sm sm:max-w-md rounded-3xl border border-gray-200 bg-white shadow-lg">

            <!-- Header -->
            <div class="px-6 pt-7 pb-5 text-center">
                <div class="inline-flex items-center gap-2 rounded-full border border-green-100 bg-green-50 px-3 py-1">
                    <i data-lucide="user" class="w-4 h-4 text-[#16A34A]"></i>
                    <span class="text-xs font-semibold text-green-900">Candidate Registration</span>
                </div>

                <h1 class="mt-3 text-xl font-bold text-gray-900">Create your account</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Step <span class="font-semibold" x-text="step"></span> of 3 ·
                    <span x-text="stepLabel()"></span>
                </p>

                <!-- progress -->
                <div class="mt-4">
                    <div class="h-2 w-full rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full bg-[#16A34A] transition-all duration-300" :style="`width:${(step/3)*100}%`">
                        </div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-[11px] text-gray-500">
                        <span :class="step>=1 ? 'text-gray-800 font-semibold' : ''">Profile</span>
                        <span :class="step>=2 ? 'text-gray-800 font-semibold' : ''">Contact</span>
                        <span :class="step>=3 ? 'text-gray-800 font-semibold' : ''">Password</span>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 pb-6">
                <form id="regForm" method="POST" action="{{ route('candidate.register.store') }}" class="space-y-4">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded-xl border  border-red-200 bg-red-50 p-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <input type="hidden" name="role" value="candidate">

                    <!-- Stores E.164 like +639171234567 -->
                    <input type="hidden" name="contact_e164" id="contact_e164">

                    <!-- STEP 1 -->
                    <div x-show="step===1" x-transition.opacity>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-700">First name</label>
                                <div class="mt-2 relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <i data-lucide="user" class="w-5 h-5"></i>
                                    </span>
                                    <input type="text" name="first_name" placeholder="Juan" required
                                        class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                        focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-700">Last name</label>
                                <div class="mt-2 relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <i data-lucide="user" class="w-5 h-5"></i>
                                    </span>
                                    <input type="text" name="last_name" placeholder="Dela Cruz" required
                                        class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                        focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <a href="{{ route('candidate.login') }}"
                                class="w-1/2 text-center rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Back
                            </a>
                            <button type="button" @click="goNext()"
                                class="w-1/2 rounded-xl bg-[#16A34A] py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">
                                Continue
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2 -->
                    <div x-show="step===2" x-transition.opacity>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-700">Email</label>
                                <div class="mt-2 relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <i data-lucide="mail" class="w-5 h-5"></i>
                                    </span>
                                    <input type="email" name="email" placeholder="you@example.com" required
                                        class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                        focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-700">Mobile number</label>

                                <input id="phone" type="tel" name="contact_number" required class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]"
                                    placeholder="Enter your number">

                                <p class="mt-2 text-[11px] text-gray-500">Tap the flag to choose country code.</p>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <button type="button" @click="step=1; refreshIcons()"
                                class="w-1/2 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Back
                            </button>
                            <button type="button" @click="goNext()"
                                class="w-1/2 rounded-xl bg-[#16A34A] py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">
                                Continue
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div x-show="step===3" x-transition.opacity>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-700">Password</label>
                                <div class="mt-2 relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <i data-lucide="lock" class="w-5 h-5"></i>
                                    </span>

                                    <input x-model="password" :type="showPass ? 'text' : 'password'" name="password"
                                        placeholder="Create a strong password" required
                                        class="w-full rounded-xl border border-gray-200 pl-11 pr-12 py-2.5 text-sm
                                        focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">

                                    <button type="button" @click="showPass=!showPass; refreshIcons()"
                                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition"
                                        aria-label="Toggle password visibility">
                                        <i data-lucide="eye" class="w-5 h-5" x-show="!showPass"></i>
                                        <i data-lucide="eye-off" class="w-5 h-5" x-show="showPass"></i>
                                    </button>
                                </div>

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

                            <div>
                                <label class="text-xs font-semibold text-gray-700">Confirm password</label>
                                <div class="mt-2 relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <i data-lucide="check" class="w-5 h-5"></i>
                                    </span>

                                    <input x-model="confirmPassword" :type="showPass ? 'text' : 'password'"
                                        name="password_confirmation" placeholder="Re-type your password" required
                                        class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
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
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <button type="button" @click="step=2; refreshIcons()"
                                class="w-1/2 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Back
                            </button>

                            <button type="submit" :disabled="!canSubmit"
                                :class="canSubmit ? 'bg-[#16A34A] hover:bg-green-700' : 'bg-gray-300 cursor-not-allowed'"
                                class="w-1/2 rounded-xl py-2.5 text-sm font-semibold text-white transition">
                                Create
                            </button>
                        </div>
                    </div>
                </form>

                <p class="text-center text-sm text-gray-600 pt-4">
                    Already have an account?
                    <a href="{{ route('candidate.login') }}" class="font-semibold text-[#16A34A] hover:underline">Sign in</a>
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
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.15.0/build/js/intlTelInput.min.js"></script>

    <script>
        function candidateRegisterBasic() {
            return {
                step: 1,
                showPass: false,
                password: '',
                confirmPassword: '',

                stepLabel() {
                    return this.step === 1 ? 'Profile' : this.step === 2 ? 'Contact' : 'Password';
                },

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
                    const form = this.$root.querySelector('form');

                    const required = {
                        1: ['first_name', 'last_name'],
                        2: ['email', 'contact_number'],
                    };

                    let ok = true;
                    (required[this.step] || []).forEach((name) => {
                        const el = form.querySelector(`[name="${name}"]`);
                        if (!el || !el.value.trim()) ok = false;
                    });

                    if (!ok) return;

                    this.step = Math.min(3, this.step + 1);
                    this.refreshIcons();
                }
            }
        }


    </script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            lucide.createIcons();

            const input = document.querySelector('#phone');
            const form = document.querySelector('#regForm');
            const hidden = document.querySelector('#contact_e164');

            const iti = window.intlTelInput(input, {
                initialCountry: 'ph',
                separateDialCode: true,
                nationalMode: true,
                dropdownContainer: document.body,
                autoPlaceholder: 'aggressive',
                formatOnDisplay: true,
            });

            // ✅ Try to load utils from CDN (most reliable)
            let utilsReady = false;
            if (typeof iti.loadUtils === 'function') {
                try {
                    await iti.loadUtils('https://cdn.jsdelivr.net/npm/intl-tel-input@25.15.0/build/js/utils.js');
                    utilsReady = true;
                } catch (e) {
                    utilsReady = false;
                    console.warn('intl-tel-input utils failed to load. Using fallback E.164.', e);
                }
            }

            // ✅ Fallback E.164 builder (works even without utils)
            function fallbackE164() {
                const dialCode = iti.getSelectedCountryData()?.dialCode || '';
                let digits = (input.value || '').replace(/\D/g, '');

                // common rule: remove leading 0 for national format (e.g., 09xx -> 9xx)
                if (digits.startsWith('0')) digits = digits.slice(1);

                if (!dialCode || !digits) return '';
                return `+${dialCode}${digits}`;
            }

            function setE164() {
                let e164 = '';

                if (utilsReady) {
                    // ✅ best quality when utils is ready
                    e164 = iti.getNumber() || '';
                }

                // ✅ if utils not ready OR still empty, use fallback
                if (!e164) e164 = fallbackE164();

                hidden.value = e164;

                // Debug (remove later)
                // console.log('contact_number:', input.value, 'contact_e164:', hidden.value);
            }

            input.addEventListener('input', setE164);
            input.addEventListener('blur', setE164);
            input.addEventListener('countrychange', setE164);

            form.addEventListener('submit', (e) => {
                setE164();

                // Optional: block submit if still empty
                if (!hidden.value) {
                    e.preventDefault();
                    alert('Please enter a valid mobile number.');
                    return;
                }

                // If utils is ready, also validate properly
                if (utilsReady && !iti.isValidNumber()) {
                    e.preventDefault();
                    alert('Please enter a valid mobile number.');
                }
            });
        });
    </script>


</body>

</html>