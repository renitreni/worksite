<div x-data="candidateRegisterBasic()"
    class="w-full max-w-sm sm:max-w-md rounded-3xl border border-gray-200 bg-white shadow-lg">

    <!-- ✅ Submit Loading Overlay -->
    <div x-cloak x-show="isSubmitting"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/30 backdrop-blur-sm" aria-live="polite"
        aria-busy="true">
        <div class="w-[92%] max-w-sm rounded-2xl bg-white shadow-xl border border-gray-200 p-5 text-center">
            <div class="mx-auto h-10 w-10 rounded-full border-4 border-gray-200 border-t-[#16A34A] animate-spin"></div>
            <p class="mt-4 text-sm font-semibold text-gray-900">Creating your account…</p>
            <p class="mt-1 text-xs text-gray-600">Please wait. Don’t close this tab.</p>
        </div>
    </div>

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
                <div class="h-full bg-[#16A34A] transition-all duration-300" :style="`width:${(step/3)*100}%`"></div>
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
        <form id="regForm" x-ref="regForm" @submit.prevent="submitForm" method="POST"
            action="{{ route('candidate.register.store') }}" class="space-y-4">
            @csrf

            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <input type="hidden" name="role" value="candidate">
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
                            <input type="text" name="first_name" placeholder="Juan" required class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700">Last name</label>
                        <div class="mt-2 relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </span>
                            <input type="text" name="last_name" placeholder="Dela Cruz" required class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
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
                            <input type="email" name="email" placeholder="you@example.com" required class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700">Mobile number</label>

                        <input id="phone" type="tel" name="contact_number" required inputmode="numeric"
                            autocomplete="tel" pattern="[0-9]*"
                            @input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm
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
                                placeholder="Create a strong password" required class="w-full rounded-xl border border-gray-200 pl-11 pr-12 py-2.5 text-sm
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
                                name="password_confirmation" placeholder="Re-type your password" required class="w-full rounded-xl border border-gray-200 pl-11 pr-4 py-2.5 text-sm
                                focus:outline-none focus:ring-2 focus:ring-[#16A34A]/30 focus:border-[#16A34A]">
                        </div>

                        <p class="mt-2 text-[11px]"
                            :class="confirmPassword.length === 0 ? 'text-gray-500' : (passwordsMatch ? 'text-green-700' : 'text-red-600')">
                            <span x-show="confirmPassword.length === 0">Re-type your password to confirm.</span>
                            <span x-show="confirmPassword.length > 0 && passwordsMatch">Passwords match.</span>
                            <span x-show="confirmPassword.length > 0 && !passwordsMatch">Passwords do not match.</span>
                        </p>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <button type="button" @click="step=2; refreshIcons()"
                        class="w-1/2 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Back
                    </button>

                    <button type="submit" :disabled="!canSubmit || isSubmitting"
                        :class="(!canSubmit || isSubmitting) ? 'bg-gray-300 cursor-not-allowed' : 'bg-[#16A34A] hover:bg-green-700'"
                        class="w-1/2 rounded-xl py-2.5 text-sm font-semibold text-white transition inline-flex items-center justify-center gap-2">

                        <svg x-show="isSubmitting" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>

                        <span x-text="isSubmitting ? 'Creating…' : 'Create'"></span>
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