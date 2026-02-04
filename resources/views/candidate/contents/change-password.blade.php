@extends('candidate.layout')

@section('content')
<div class="max-w-3xl mx-auto space-y-6"
     x-data="passwordChangeForm()"
>
    {{-- Title --}}
    <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">
        Change Password
    </h1>

    {{-- Card --}}
    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-8 space-y-6">

        {{-- Info box --}}
        <div class="flex items-start gap-4 rounded-2xl bg-blue-50 border border-blue-100 p-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">
                <i data-lucide="shield-check" class="h-5 w-5 text-blue-600"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Secure your account</p>
                <p class="text-sm text-gray-600">
                    Ensure your account is using a long, random password to stay secure.
                </p>
            </div>
        </div>

        {{-- Toast / Notification --}}
        <template x-if="notice.show">
            <div
                class="rounded-2xl border p-4 text-sm flex items-start gap-3"
                :class="notice.type === 'error'
                    ? 'bg-red-50 border-red-200 text-red-700'
                    : (notice.type === 'success'
                        ? 'bg-emerald-50 border-emerald-200 text-emerald-700'
                        : 'bg-yellow-50 border-yellow-200 text-yellow-800'
                    )"
            >
                <div class="mt-0.5">
                    <i data-lucide="info" class="h-4 w-4"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold" x-text="notice.title"></p>
                    <p class="mt-0.5" x-text="notice.message"></p>
                </div>
                <button type="button" class="text-xs underline opacity-80 hover:opacity-100" @click="notice.show=false">
                    Close
                </button>
            </div>
        </template>

        {{-- Form --}}
        <form class="space-y-6" @submit.prevent="submitForm()">

            {{-- Current password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Current Password
                </label>

                <div class="relative">
                    <input
                        :type="showCurrent ? 'text' : 'password'"
                        placeholder="Enter current password"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                        x-model.trim="currentPassword"
                    />

                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500 hover:text-gray-700"
                        @click="showCurrent = !showCurrent"
                        aria-label="Toggle current password visibility"
                    >
                        <i x-show="!showCurrent" data-lucide="eye" class="h-5 w-5"></i>
                        <i x-show="showCurrent" data-lucide="eye-off" class="h-5 w-5"></i>
                    </button>
                </div>
            </div>

            {{-- New password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    New Password
                </label>

                <div class="relative">
                    <input
                        :type="showNew ? 'text' : 'password'"
                        placeholder="Enter new password"
                        class="w-full rounded-xl border px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2"
                        :class="strength.level === 1
                            ? 'border-red-200 bg-red-50 focus:ring-red-200 focus:border-red-300'
                            : (strength.level === 2
                                ? 'border-yellow-200 bg-yellow-50 focus:ring-yellow-200 focus:border-yellow-300'
                                : 'border-emerald-200 bg-emerald-50 focus:ring-emerald-200 focus:border-emerald-300'
                            )"
                        x-model="newPassword"
                        @input="updateStrength()"
                    />

                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500 hover:text-gray-700"
                        @click="showNew = !showNew"
                        aria-label="Toggle new password visibility"
                    >
                        <i x-show="!showNew" data-lucide="eye" class="h-5 w-5"></i>
                        <i x-show="showNew" data-lucide="eye-off" class="h-5 w-5"></i>
                    </button>
                </div>

                {{-- Strength bar --}}
                <div class="mt-3 space-y-2">
                    <div class="flex gap-2">
                        <span class="h-1.5 flex-1 rounded-full"
                              :class="strength.level >= 1 ? strength.barColor : 'bg-gray-200'"></span>
                        <span class="h-1.5 flex-1 rounded-full"
                              :class="strength.level >= 2 ? strength.barColor : 'bg-gray-200'"></span>
                        <span class="h-1.5 flex-1 rounded-full"
                              :class="strength.level >= 3 ? strength.barColor : 'bg-gray-200'"></span>
                        <span class="h-1.5 flex-1 rounded-full"
                              :class="strength.level >= 4 ? strength.barColor : 'bg-gray-200'"></span>
                    </div>

                    <p class="text-xs text-gray-500">
                        Password strength:
                        <span class="font-semibold" x-text="strength.label"></span>
                    </p>

                    {{-- Inline requirement note --}}
                    <p class="text-xs"
                       :class="strength.level === 1 ? 'text-red-600' : (strength.level === 2 ? 'text-yellow-700' : 'text-emerald-700')"
                       x-text="strength.helper">
                    </p>
                </div>
            </div>

            {{-- Confirm password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm New Password
                </label>

                <div class="relative">
                    <input
                        :type="showConfirm ? 'text' : 'password'"
                        placeholder="Confirm new password"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                        x-model="confirmPassword"
                    />

                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500 hover:text-gray-700"
                        @click="showConfirm = !showConfirm"
                        aria-label="Toggle confirm password visibility"
                    >
                        <i x-show="!showConfirm" data-lucide="eye" class="h-5 w-5"></i>
                        <i x-show="showConfirm" data-lucide="eye-off" class="h-5 w-5"></i>
                    </button>
                </div>

                <template x-if="confirmPassword.length > 0 && confirmPassword !== newPassword">
                    <p class="mt-2 text-xs text-red-600">
                        Passwords do not match.
                    </p>
                </template>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-4">
                <button
                    type="button"
                    class="rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition"
                    @click="resetForm()"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-xl px-5 py-3 text-sm font-semibold text-white transition"
                    :class="canSubmit
                        ? 'bg-emerald-600 hover:bg-emerald-700'
                        : 'bg-gray-300 cursor-not-allowed'"
                    :disabled="!canSubmit"
                >
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Alpine Component --}}
<script>
    function passwordChangeForm() {
        return {
            currentPassword: '',
            newPassword: '',
            confirmPassword: '',

            // NEW: toggles
            showCurrent: false,
            showNew: false,
            showConfirm: false,

            strength: {
                level: 1, // 1=red, 2=yellow, 3=green, 4=green+
                label: 'Weak',
                helper: 'Make the password longer.',
                barColor: 'bg-red-500',
            },

            notice: {
                show: false,
                type: 'error', // error | warn | success
                title: '',
                message: '',
            },

            get canSubmit() {
                const strengthOk = this.strength.level >= 2; // yellow or green
                const matches = this.newPassword.length > 0 && this.newPassword === this.confirmPassword;
                const hasCurrent = this.currentPassword.length > 0;
                return strengthOk && matches && hasCurrent;
            },

            updateStrength() {
                const pwd = this.newPassword || '';

                // Basic scoring
                let score = 0;
                if (pwd.length >= 8) score++;
                if (pwd.length >= 12) score++;
                if (/[A-Z]/.test(pwd)) score++;
                if (/[0-9]/.test(pwd)) score++;
                if (/[^A-Za-z0-9]/.test(pwd)) score++;

                if (pwd.length < 8) {
                    this.setStrength(1);
                    this.showNotice('error', 'Weak password', 'Make the password longer (at least 8 characters).');
                    return;
                }

                if (pwd.length >= 8 && (score <= 2)) {
                    this.setStrength(2);
                    this.showNotice('warn', 'Acceptable password', 'Password strength is acceptable, but you can make it stronger.');
                    return;
                }

                if (pwd.length >= 12 && score >= 4) {
                    this.setStrength(4);
                    this.showNotice('success', 'Strong password', 'Password strength is acceptable. You can update your password.');
                    return;
                }

                this.setStrength(3);
                this.showNotice('success', 'Good password', 'Password strength is acceptable. You can update your password.');
            },

            setStrength(level) {
                this.strength.level = level;

                if (level === 1) {
                    this.strength.label = 'Weak';
                    this.strength.helper = 'Make the password longer.';
                    this.strength.barColor = 'bg-red-500';
                } else if (level === 2) {
                    this.strength.label = 'Medium';
                    this.strength.helper = 'Acceptable. Add numbers/uppercase for stronger security.';
                    this.strength.barColor = 'bg-yellow-500';
                } else {
                    this.strength.label = (level === 4) ? 'Very Strong' : 'Strong';
                    this.strength.helper = 'Password strength is acceptable.';
                    this.strength.barColor = 'bg-emerald-500';
                }
            },

            showNotice(type, title, message) {
                this.notice.show = true;
                this.notice.type = type;
                this.notice.title = title;
                this.notice.message = message;
            },

            submitForm() {
                if (!this.canSubmit) {
                    this.showNotice('error', 'Cannot update password', 'Please make sure your password is acceptable and both fields match.');
                    return;
                }

                // FRONTEND DEMO ONLY:
                this.showNotice('success', 'Password updated', 'Your password has been updated successfully (demo).');

                this.currentPassword = '';
                this.newPassword = '';
                this.confirmPassword = '';
                this.showCurrent = false;
                this.showNew = false;
                this.showConfirm = false;
                this.setStrength(1);
            },

            resetForm() {
                this.currentPassword = '';
                this.newPassword = '';
                this.confirmPassword = '';
                this.showCurrent = false;
                this.showNew = false;
                this.showConfirm = false;
                this.notice.show = false;
                this.setStrength(1);
            },

            init() {
                this.updateStrength();
            }
        }
    }
</script>
@endsection
