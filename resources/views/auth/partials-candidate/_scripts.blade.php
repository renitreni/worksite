<!-- Intl Tel Input JS -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.15.0/build/js/intlTelInput.min.js"></script>

<script>
    function candidateRegisterBasic() {
        return {
            step: 1,
            showPass: false,
            password: '',
            confirmPassword: '',
            isSubmitting: false,

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
            },

            submitForm() {
                if (this.step !== 3 || !this.canSubmit || this.isSubmitting) return;

                // ✅ ensure e164 is set before submit (since @submit.prevent)
                if (typeof window.setE164 === 'function') window.setE164();

                const hidden = document.querySelector('#contact_e164');
                if (!hidden?.value) {
                    alert('Please enter a valid mobile number.');
                    return;
                }

                this.isSubmitting = true;
                this.refreshIcons();
                this.$refs.regForm.submit();
            },
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        lucide.createIcons();

        const input  = document.querySelector('#phone');
        const form   = document.querySelector('#regForm');
        const hidden = document.querySelector('#contact_e164');

        if (!input || !form || !hidden) return;

        const iti = window.intlTelInput(input, {
            initialCountry: 'ph',
            separateDialCode: true,
            nationalMode: true,
            dropdownContainer: document.body,
            autoPlaceholder: 'aggressive',
            formatOnDisplay: true,
        });

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

        function fallbackE164() {
            const dialCode = iti.getSelectedCountryData()?.dialCode || '';
            let digits = (input.value || '').replace(/\D/g, '');
            if (digits.startsWith('0')) digits = digits.slice(1);
            if (!dialCode || !digits) return '';
            return `+${dialCode}${digits}`;
        }

        // ✅ expose globally so Alpine submitForm() can call it
        window.setE164 = function () {
            let e164 = '';
            if (utilsReady) e164 = iti.getNumber() || '';
            if (!e164) e164 = fallbackE164();
            hidden.value = e164;
        }

        input.addEventListener('input', window.setE164);
        input.addEventListener('blur', window.setE164);
        input.addEventListener('countrychange', window.setE164);

        // keep normal submit protection for non-Alpine submits too
        form.addEventListener('submit', (e) => {
            window.setE164();

            if (!hidden.value) {
                e.preventDefault();
                alert('Please enter a valid mobile number.');
                return;
            }

            if (utilsReady && !iti.isValidNumber()) {
                e.preventDefault();
                alert('Please enter a valid mobile number.');
            }
        });
    });
</script>
