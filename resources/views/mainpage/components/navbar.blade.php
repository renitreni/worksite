<nav class="bg-white/90 backdrop-blur shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo -->
            <div class="flex items-center space-x-2 cursor-pointer">
                <i data-lucide="briefcase" class="w-6 h-6 text-[#16A34A]"></i>
                <span class="text-xl font-semibold tracking-tight text-gray-800">
                    Work<span class="text-[#16A34A]">SITE</span>
                </span>
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
                <a href="#" class="px-4 py-2 rounded-lg font-medium text-[#16A34A] border border-[#16A34A]
                          transition hover:bg-[#16A34A] hover:text-white">
                    Login
                </a>
                <a href="#" class="px-4 py-2 rounded-lg font-semibold text-white bg-[#16A34A]
                          transition hover:scale-105 hover:bg-green-600 shadow-sm">
                    Register
                </a>
            </div>

            <!-- Mobile Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button">
                    <i data-lucide="menu" class="w-6 h-6 text-gray-700"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Menu -->
    <!-- Mobile Menu -->
    <div id="mobile-menu"
        class="hidden md:hidden absolute top-full left-0 w-full bg-white shadow-lg z-50 border-t border-gray-100 flex flex-col">
        <a href="#" class="block px-4 py-3 hover:bg-gray-100">Home</a>

        <!-- Mobile Dropdown for Search -->
        <div class="border-t border-gray-200 flex flex-col">
            <button
                class="w-full text-left px-4 py-3 flex justify-between items-center hover:bg-gray-100 focus:outline-none"
                id="mobile-search-btn">
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
            <a href="#" class="w-full text-center border border-[#16A34A] text-[#16A34A] rounded-lg py-2">
                Login
            </a>
            <a href="#" class="w-full text-center bg-[#16A34A] text-white rounded-lg py-2">
                Register
            </a>
        </div>
    </div>

</nav>

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>
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
</script>