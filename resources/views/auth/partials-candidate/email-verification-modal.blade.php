<script>
    window.__verify = {
        show: {{ session('show_verify_modal') ? 'true' : 'false' }},
        userId: "{{ session('verify_user_id') ?? '' }}",
        email: "{{ session('verify_email') ?? '' }}",
        csrf: "{{ csrf_token() }}",
        loginRedirect: "{{ route('candidate.login') }}", // ✅ redirect here after success
    };
</script>

<div x-data="emailVerifyModal()" x-init="init()" x-show="open" x-transition.opacity
    class="fixed inset-0 z-[99999] flex items-center justify-center px-4" style="display:none;">

    <!-- backdrop (click does nothing, but you can change to close if you want) -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- MAIN MODAL -->
    <div class="relative w-full max-w-md rounded-3xl shadow-xl border p-6 transition" :class="verifiedUi
            ? 'bg-green-50 border-green-200'
            : 'bg-white border-gray-200'
        ">
        <!-- header -->
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-bold" :class="verifiedUi ? 'text-green-900' : 'text-gray-900'">
                    Verify your email
                </h3>

                <p class="mt-1 text-sm" :class="verifiedUi ? 'text-green-800/80' : 'text-gray-600'">
                    We sent a 6-digit code to
                    <span class="font-semibold" x-text="email"></span>
                </p>
            </div>

            <div class="flex items-center gap-2">
                <!-- icon badge -->
                <div class="w-10 h-10 rounded-xl flex items-center justify-center border transition" :class="verifiedUi
                        ? 'bg-green-100 border-green-200'
                        : 'bg-green-50 border-green-100'
                    ">
                    <i data-lucide="mail-check" class="w-5 h-5 text-[#16A34A]"></i>
                </div>

                <!-- ✅ CLOSE (X) -->
                <button type="button" @click="close()"
                    class="w-10 h-10 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 flex items-center justify-center transition"
                    aria-label="Close verification modal">
                    <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                </button>
            </div>
        </div>

        <!-- content -->
        <div class="mt-5">
            <label class="text-xs font-semibold" :class="verifiedUi ? 'text-green-900' : 'text-gray-700'">
                Verification code
            </label>

            <input type="text" inputmode="numeric" maxlength="6" x-model="code"
                @input="code = code.replace(/\D/g,'').slice(0,6)" placeholder="------" class="mt-2 w-full rounded-xl px-4 py-3 text-sm tracking-[0.35em] font-semibold text-center transition
                       focus:outline-none focus:ring-2" :class="verifiedUi
                    ? 'border-green-200 bg-white focus:ring-green-200 focus:border-green-400'
                    : 'border-gray-200 bg-white focus:ring-[#16A34A]/30 focus:border-[#16A34A]'
                " />

            <p class="mt-3 text-sm" x-show="message" :class="ok ? 'text-green-700' : 'text-red-600'" x-text="message">
            </p>
        </div>

        <!-- actions -->
        <div class="mt-6 flex items-center gap-2">
            <button type="button" @click="verify()" :disabled="loading || code.length !== 6 || verifiedUi" :class="(code.length===6 && !loading && !verifiedUi)
                    ? 'bg-[#16A34A] hover:bg-green-700'
                    : 'bg-gray-300 cursor-not-allowed'"
                class="w-1/2 rounded-xl py-2.5 text-sm font-semibold text-white transition">
                <span x-show="!loading">Verify</span>
                <span x-show="loading">Verifying...</span>
            </button>

            <button type="button" @click="resend()" :disabled="resending || verifiedUi"
                class="w-1/2 rounded-xl border py-2.5 text-sm font-semibold transition" :class="verifiedUi
                    ? 'border-green-200 text-green-800 bg-green-50 cursor-not-allowed'
                    : 'border-gray-200 text-gray-700 hover:bg-gray-50'
                ">
                <span x-show="!resending">Resend code</span>
                <span x-show="resending">Sending...</span>
            </button>
        </div>

        <p class="mt-4 text-[11px]" :class="verifiedUi ? 'text-green-800/70' : 'text-gray-500'">
            Tip: Check your spam/junk folder if you don’t see the email.
        </p>

        <!-- ✅ SUCCESS POPUP MODAL (overlay) -->
        <div x-show="successPopup" x-transition.opacity
            class="absolute inset-0 rounded-3xl flex items-center justify-center p-6" style="display:none;">
            <div class="absolute inset-0 bg-black/20 rounded-3xl"></div>

            <div class="relative w-full rounded-2xl bg-white border border-green-200 shadow-lg p-5 text-center">
                <div
                    class="mx-auto w-12 h-12 rounded-2xl bg-green-100 flex items-center justify-center border border-green-200">
                    <i data-lucide="badge-check" class="w-6 h-6 text-green-700"></i>
                </div>

                <h4 class="mt-3 text-lg font-bold text-green-900">You’re verified!</h4>
                <p class="mt-1 text-sm text-green-800/80">
                    Redirecting you to login...
                </p>

                <div class="mt-4 h-2 w-full rounded-full bg-green-100 overflow-hidden">
                    <div class="h-full bg-green-600 animate-pulse" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function emailVerifyModal() {
        return {
            open: false,
            userId: '',
            email: '',
            code: '',
            loading: false,
            resending: false,
            message: '',
            ok: false,

            verifiedUi: false,     // ✅ makes modal green after verification
            successPopup: false,   // ✅ show success popup overlay

            init() {
                this.open = window.__verify.show;
                this.userId = window.__verify.userId;
                this.email = window.__verify.email;

                window.addEventListener('open-verify-modal', () => {
                    this.open = true;
                    this.code = '';
                    this.message = '';
                    this.ok = false;
                    this.loading = false;
                    this.resending = false;
                    this.verifiedUi = false;
                    this.successPopup = false;
                    setTimeout(() => lucide.createIcons(), 0);
                });

                setTimeout(() => lucide.createIcons(), 0);
            },


            close() {
                this.open = false;
                // optional: reset state so if you open again it's clean
                this.code = '';
                this.message = '';
                this.ok = false;
                this.loading = false;
                this.resending = false;
                this.verifiedUi = false;
                this.successPopup = false;
            },

            async verify() {
                this.loading = true;
                this.message = '';
                this.ok = false;

                try {
                    const res = await fetch("{{ route('candidate.verify.email') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.__verify.csrf,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            user_id: this.userId,
                            code: this.code,
                        })
                    });

                    const data = await res.json();

                    if (!res.ok || !data.ok) {
                        this.ok = false;
                        this.message = data.message || 'Verification failed.';
                        return;
                    }

                    // ✅ success UI
                    this.ok = true;
                    this.verifiedUi = true;
                    this.message = 'Verified successfully!';

                    // ✅ show popup then redirect to candidate login
                    this.successPopup = true;

                    setTimeout(() => {
                        window.location.href = window.__verify.loginRedirect;
                    }, 1200);

                } catch (e) {
                    this.ok = false;
                    this.message = 'Network error. Please try again.';
                } finally {
                    this.loading = false;
                    setTimeout(() => lucide.createIcons(), 0);
                }
            },

            async resend() {
                this.resending = true;
                this.message = '';
                this.ok = false;

                try {
                    const res = await fetch("{{ route('candidate.verify.resend') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.__verify.csrf,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ user_id: this.userId })
                    });

                    const data = await res.json();

                    this.ok = !!data.ok;
                    this.message = data.message || (data.ok ? 'A new code has been sent.' : 'Failed to resend.');
                } catch (e) {
                    this.ok = false;
                    this.message = 'Network error. Please try again.';
                } finally {
                    this.resending = false;
                }
            },
        }
    }
</script>