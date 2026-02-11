<nav class="bg-white/90 backdrop-blur shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo -->
            <div class="flex items-center space-x-2 cursor-pointer">
                <img src="{{ asset('images/logo.png') }}" alt="Worksite Logo" class="h-16 w-auto">
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">

                <a href="#" class="relative text-gray-700 font-medium transition hover:text-[#16A34A]
                          after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:w-0
                          after:bg-[#16A34A] after:transition-all hover:after:w-full">
                    Home
                </a>

                <!-- Desktop Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-1 font-medium text-gray-700 transition hover:text-[#16A34A]">
                        Search
                        <i data-lucide="chevron-down"
                            class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180"></i>
                    </button>

                    <div class="absolute left-0 mt-3 w-56 rounded-xl bg-white shadow-lg border border-gray-100
                               opacity-0 invisible translate-y-2
                               transition-all duration-300
                               group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">

                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-[#16A34A] rounded-t-xl">
                            Search by Jobs
                        </a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-[#16A34A]">
                            Search by Agencies
                        </a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-[#16A34A]">
                            Search by Industries
                        </a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-[#16A34A] rounded-b-xl">
                            Search by Country
                        </a>
                    </div>
                </div>

                <a href="#" class="relative text-gray-700 font-medium transition hover:text-[#16A34A]
                          after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:w-0
                          after:bg-[#16A34A] after:transition-all hover:after:w-full">
                    About
                </a>

                <a href="#" class="relative text-gray-700 font-medium transition hover:text-[#16A34A]
                          after:absolute after:-bottom-1 after:left-0 after:h-[2px] after:w-0
                          after:bg-[#16A34A] after:transition-all hover:after:w-full">
                    Contact Us
                </a>
            </div>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center space-x-3">
                <!-- ✅ Login opens modal (not direct route) -->
                <button type="button" id="loginBtnDesktop"
                    class="px-4 py-2 rounded-lg font-medium text-[#16A34A] border border-[#16A34A]
                          transition hover:bg-[#16A34A] hover:text-white">
                    Login
                </button>

                <!-- Register (opens modal) -->
                <button type="button" id="registerBtnDesktop"
                    class="px-4 py-2 rounded-lg font-semibold text-white bg-[#16A34A]
                          transition hover:scale-105 hover:bg-green-600 shadow-sm">
                    Register
                </button>
            </div>

            <!-- Mobile Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" type="button">
                    <i data-lucide="menu" class="w-6 h-6 text-gray-700"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu"
        class="hidden md:hidden absolute top-full left-0 w-full bg-white shadow-lg z-50 border-t border-gray-100 flex flex-col">
        <a href="#" class="block px-4 py-3 hover:bg-gray-100">Home</a>

        <!-- Mobile Dropdown for Search -->
        <div class="border-t border-gray-200 flex flex-col">
            <button
                class="w-full text-left px-4 py-3 flex justify-between items-center hover:bg-gray-100 focus:outline-none"
                id="mobile-search-btn" type="button">
                Search
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300"
                    id="mobile-search-icon"></i>
            </button>
            <div id="mobile-search-dropdown" class="hidden flex-col bg-white border-t border-gray-100">
                <a href="#" class="block px-6 py-2 hover:bg-green-50 hover:text-[#16A34A]">Search by Jobs</a>
                <a href="#" class="block px-6 py-2 hover:bg-green-50 hover:text-[#16A34A]">Search by Agencies</a>
                <a href="#" class="block px-6 py-2 hover:bg-green-50 hover:text-[#16A34A]">Search by Industries</a>
                <a href="#" class="block px-6 py-2 hover:bg-green-50 hover:text-[#16A34A]">Search by Country</a>
            </div>
        </div>

        <a href="#" class="block px-4 py-3 hover:bg-gray-100">About</a>
        <a href="#" class="block px-4 py-3 hover:bg-gray-100">Contact Us</a>

        <div class="px-4 py-3 flex flex-col gap-2">
            <!-- ✅ Login opens modal (not direct route) -->
            <button type="button" id="loginBtnMobile"
                class="w-full text-center border border-[#16A34A] text-[#16A34A] rounded-lg py-2">
                Login
            </button>

            <!-- Register (opens modal) -->
            <button type="button" id="registerBtnMobile"
                class="w-full text-center bg-[#16A34A] text-white rounded-lg py-2">
                Register
            </button>
        </div>
    </div>

</nav>

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
                    <div class="mt-0.5 h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center border border-green-200">
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
                    <div class="mt-0.5 h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center border border-emerald-200">
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
                    <div class="mt-0.5 h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center border border-green-200">
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
                    <div class="mt-0.5 h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center border border-emerald-200">
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

<!-- ✅ Scripts -->
<script>
    lucide.createIcons();

    // Mobile main menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Mobile Search dropdown toggle
    const mobileSearchBtn = document.getElementById('mobile-search-btn');
    const mobileSearchDropdown = document.getElementById('mobile-search-dropdown');
    const mobileSearchIcon = document.getElementById('mobile-search-icon');

    mobileSearchBtn.addEventListener('click', () => {
        mobileSearchDropdown.classList.toggle('hidden');
        mobileSearchIcon.classList.toggle('rotate-180');
    });

    // ✅ REGISTER MODAL (no scrollbar hiding)
    const registerModal = document.getElementById('registerModal');
    const registerOverlay = document.getElementById('registerOverlay');
    const registerBtnDesktop = document.getElementById('registerBtnDesktop');
    const registerBtnMobile = document.getElementById('registerBtnMobile');
    const registerCloseBtn = document.getElementById('registerCloseBtn');
    const registerCancelBtn = document.getElementById('registerCancelBtn');

    function openRegisterModal() {
        registerModal.classList.remove('hidden');
        lucide.createIcons();
    }

    function closeRegisterModal() {
        registerModal.classList.add('hidden');
    }

    registerBtnDesktop?.addEventListener('click', openRegisterModal);
    registerBtnMobile?.addEventListener('click', () => {
        openRegisterModal();
        mobileMenu.classList.add('hidden');
    });

    registerCloseBtn?.addEventListener('click', closeRegisterModal);
    registerCancelBtn?.addEventListener('click', closeRegisterModal);
    registerOverlay?.addEventListener('click', closeRegisterModal);

    // ✅ LOGIN MODAL (no scrollbar hiding)
    const loginModal = document.getElementById('loginModal');
    const loginOverlay = document.getElementById('loginOverlay');
    const loginBtnDesktop = document.getElementById('loginBtnDesktop');
    const loginBtnMobile = document.getElementById('loginBtnMobile');
    const loginCloseBtn = document.getElementById('loginCloseBtn');
    const loginCancelBtn = document.getElementById('loginCancelBtn');

    function openLoginModal() {
        loginModal.classList.remove('hidden');
        lucide.createIcons();
    }

    function closeLoginModal() {
        loginModal.classList.add('hidden');
    }

    loginBtnDesktop?.addEventListener('click', openLoginModal);
    loginBtnMobile?.addEventListener('click', () => {
        openLoginModal();
        mobileMenu.classList.add('hidden');
    });

    loginCloseBtn?.addEventListener('click', closeLoginModal);
    loginCancelBtn?.addEventListener('click', closeLoginModal);
    loginOverlay?.addEventListener('click', closeLoginModal);

    // ESC closes whichever is open
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;

        if (!loginModal.classList.contains('hidden')) closeLoginModal();
        if (!registerModal.classList.contains('hidden')) closeRegisterModal();
    });
</script>
