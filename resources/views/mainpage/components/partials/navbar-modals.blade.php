<!-- ✅ LOGIN CHOICE MODAL -->
<div id="loginModal" class="fixed inset-0 z-[999] hidden">
    <div id="loginOverlay" class="absolute inset-0 bg-black/50 backdrop-blur-[2px]"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl border border-gray-100 overflow-hidden">
            <div class="flex items-start justify-between gap-4 p-5 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Login as</h3>
                    <p class="text-sm text-gray-600 mt-1">Choose which account you want to access.</p>
                </div>

                <button type="button" id="loginCloseBtn"
                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg hover:bg-gray-100 transition"
                    aria-label="Close modal">
                    <i data-lucide="x" class="w-5 h-5 text-gray-700"></i>
                </button>
            </div>

            <div class="p-5 space-y-3">
                <!-- ✅ change routes here if you have separate login pages -->
                <a href="/candidate/login"
                    class="group flex items-start gap-3 p-4 rounded-xl border border-gray-200 hover:border-green-200 hover:bg-green-50/60 transition">
                    <div
                        class="mt-0.5 h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center border border-green-200">
                        <i data-lucide="user" class="w-5 h-5 text-green-700"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-semibold text-gray-900">Candidate</p>
                            <i data-lucide="arrow-right"
                                class="w-4 h-4 text-gray-500 group-hover:translate-x-0.5 transition"></i>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">I’m applying for jobs.</p>
                    </div>
                </a>

                <a href="/employer/login"
                    class="group flex items-start gap-3 p-4 rounded-xl border border-gray-200 hover:border-green-200 hover:bg-green-50/60 transition">
                    <div
                        class="mt-0.5 h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center border border-emerald-200">
                        <i data-lucide="briefcase" class="w-5 h-5 text-emerald-700"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-semibold text-gray-900">Employer</p>
                            <i data-lucide="arrow-right"
                                class="w-4 h-4 text-gray-500 group-hover:translate-x-0.5 transition"></i>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">I’m posting jobs and hiring.</p>
                    </div>
                </a>
            </div>

            <div class="p-5 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" id="loginCancelBtn"
                    class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-100 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- REGISTER CHOICE MODAL (your existing one) -->
<div id="registerModal" class="fixed inset-0 z-[999] hidden">
    <div id="registerOverlay" class="absolute inset-0 bg-black/50 backdrop-blur-[2px]"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl border border-gray-100 overflow-hidden">
            <div class="flex items-start justify-between gap-4 p-5 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Register as</h3>
                    <p class="text-sm text-gray-600 mt-1">Choose the account type you want to create.</p>
                </div>

                <button type="button" id="registerCloseBtn"
                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg hover:bg-gray-100 transition"
                    aria-label="Close modal">
                    <i data-lucide="x" class="w-5 h-5 text-gray-700"></i>
                </button>
            </div>

            <div class="p-5 space-y-3">
                <a href="/candidate/register"
                    class="group flex items-start gap-3 p-4 rounded-xl border border-gray-200 hover:border-green-200 hover:bg-green-50/60 transition">
                    <div
                        class="mt-0.5 h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center border border-green-200">
                        <i data-lucide="user" class="w-5 h-5 text-green-700"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-semibold text-gray-900">Candidate</p>
                            <i data-lucide="arrow-right"
                                class="w-4 h-4 text-gray-500 group-hover:translate-x-0.5 transition"></i>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">I’m finding jobs and applying.</p>
                    </div>
                </a>

                <a href="/employer/register"
                    class="group flex items-start gap-3 p-4 rounded-xl border border-gray-200 hover:border-green-200 hover:bg-green-50/60 transition">
                    <div
                        class="mt-0.5 h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center border border-emerald-200">
                        <i data-lucide="briefcase" class="w-5 h-5 text-emerald-700"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-semibold text-gray-900">Employer</p>
                            <i data-lucide="arrow-right"
                                class="w-4 h-4 text-gray-500 group-hover:translate-x-0.5 transition"></i>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">I’m posting jobs and hiring.</p>
                    </div>
                </a>
            </div>

            <div class="p-5 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" id="registerCancelBtn"
                    class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-100 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

