<div x-show="passOpen" x-transition.opacity
    class="fixed inset-0 z-[999] flex items-center justify-center p-3 sm:p-6"
    aria-modal="true" role="dialog"
    @keydown.escape.window="passOpen=false">

    <div class="absolute inset-0 bg-gray-900/40" @click="passOpen=false"></div>

    <div x-transition @click.stop
        class="relative w-full max-w-lg rounded-2xl bg-white border border-gray-200 shadow-xl"
        x-data="{ showCurrent:false, showNew:false, showConfirm:false }">

        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-900">Change Password</h3>
            <button type="button" @click="passOpen=false"
                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white hover:bg-gray-50">
                <i data-lucide="x" class="h-5 w-5 text-gray-700"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('candidate.profile.password') }}" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Current Password</label>
                <div class="relative">
                    <input name="current_password" :type="showCurrent ? 'text' : 'password'"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-4 pr-12 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                    <button type="button" @click="showCurrent = !showCurrent"
                        class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex h-10 w-10 items-center justify-center rounded-xl hover:bg-gray-100">
                        <i data-lucide="eye" x-show="!showCurrent" class="h-5 w-5 text-gray-600"></i>
                        <i data-lucide="eye-off" x-show="showCurrent" class="h-5 w-5 text-gray-600"></i>
                    </button>
                </div>
                @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">New Password</label>
                <div class="relative">
                    <input name="password" :type="showNew ? 'text' : 'password'"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-4 pr-12 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                    <button type="button" @click="showNew = !showNew"
                        class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex h-10 w-10 items-center justify-center rounded-xl hover:bg-gray-100">
                        <i data-lucide="eye" x-show="!showNew" class="h-5 w-5 text-gray-600"></i>
                        <i data-lucide="eye-off" x-show="showNew" class="h-5 w-5 text-gray-600"></i>
                    </button>
                </div>
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Confirm New Password</label>
                <div class="relative">
                    <input name="password_confirmation" :type="showConfirm ? 'text' : 'password'"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-4 pr-12 py-3 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
                    <button type="button" @click="showConfirm = !showConfirm"
                        class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex h-10 w-10 items-center justify-center rounded-xl hover:bg-gray-100">
                        <i data-lucide="eye" x-show="!showConfirm" class="h-5 w-5 text-gray-600"></i>
                        <i data-lucide="eye-off" x-show="showConfirm" class="h-5 w-5 text-gray-600"></i>
                    </button>
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" @click="passOpen=false"
                    class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

