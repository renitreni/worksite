<nav class="bg-white/90 backdrop-blur shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo -->
            <div class="flex items-center space-x-2 cursor-pointer">
                <img src="{{ asset('images/logo.png') }}" alt="Worksite Logo" class="h-16 w-auto">
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">

                <a href="{{ route('home') }}" class="relative text-gray-700 font-medium transition hover:text-[#16A34A]
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

                        <a href="{{ route('search-jobs') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-[#16A34A] rounded-t-xl">
                            Search by Jobs
                        </a>
                        <a href="{{ route('search-agency') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-[#16A34A]">
                            Search by Agencies
                        </a>
                        <a href="{{ route('search-industries') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-[#16A34A]">
                            Search by Industries
                        </a>
                        <a href="{{ route('search-country') }}"
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

            <div class="hidden md:flex items-center space-x-3">

                @guest
                    <button type="button" id="loginBtnDesktop" class="px-4 py-2 rounded-lg font-medium text-[#16A34A] border border-[#16A34A]
                                  transition hover:bg-[#16A34A] hover:text-white">
                        Login
                    </button>

                    <button type="button" id="registerBtnDesktop" class="px-4 py-2 rounded-lg font-semibold text-white bg-[#16A34A]
                                  transition hover:scale-105 hover:bg-green-600 shadow-sm">
                        Register
                    </button>
                @endguest

                @auth
                    @if(auth()->user()->role === 'candidate')

                    <div x-data="{ open: false }" class="relative">
                        <button type="button" @click="open = !open" @keydown.escape.window="open = false"
                            class="flex items-center gap-2 rounded-xl border border-gray-200 bg-[white] px-3 py-2 hover:bg-gray-50 transition">
                            <div
                                class="h-9 w-9 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                                <i data-lucide="user" class="h-4 w-4 text-emerald-700"></i>
                            </div>

                            <div class="text-left leading-tight">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ auth()->user()->name ?? 'Profile' }}
                                </p>

                            </div>

                            <i data-lucide="chevron-down" class="h-4 w-4 text-gray-500"></i>
                        </button>

                        <div x-cloak x-show="open" x-transition @click.outside="open = false"
                            class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg z-50">

                            <a href="{{ route('candidate.dashboard') }}"
                                class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                                <i data-lucide="layout-dashboard" class="h-4 w-4 text-gray-500"></i>
                                <span>Go to Dashboard</span>
                            </a>

                            <div class="h-px bg-gray-100"></div>

                            <form method="POST" action="{{ route('candidate.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                                    <i data-lucide="log-out" class="h-4 w-4 text-red-500"></i>
                                    <span>Log Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                @endauth

            </div>

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

        <div class="border-t border-gray-200 flex flex-col">
            <button
                class="w-full text-left px-4 py-3 flex justify-between items-center hover:bg-gray-100 focus:outline-none"
                id="mobile-search-btn" type="button">
                Search
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300"
                    id="mobile-search-icon"></i>
            </button>
            <div id="mobile-search-dropdown" class="hidden flex-col bg-white border-t border-gray-100">
                <a href="{{ route('search-jobs') }}"
                    class="block px-6 py-2 hover:bg-green-50 hover:text-[#16A34A]">Search by Jobs</a>
                <a href="{{ route('search-agency') }}"
                    class="block px-6 py-2 hover:bg-green-50 hover:text-[#16A34A]">Search by Agencies</a>
                <a href="{{ route('search-industries') }}"
                    class="block px-6 py-2 hover:bg-green-50 hover:text-[#16A34A]">Search by Industries</a>
                <a href="{{ route('search-country') }}"
                    class="block px-6 py-2 hover:bg-green-50 hover:text-[#16A34A]">Search by Country</a>
            </div>
        </div>

        <a href="#" class="block px-4 py-3 hover:bg-gray-100">About</a>
        <a href="#" class="block px-4 py-3 hover:bg-gray-100">Contact Us</a>

        <div class="px-4 py-3 flex flex-col gap-2">
            @guest
                <button type="button" id="loginBtnMobile"
                    class="w-full text-center border border-[#16A34A] text-[#16A34A] rounded-lg py-2">
                    Login
                </button>

                <button type="button" id="registerBtnMobile"
                    class="w-full text-center bg-[#16A34A] text-white rounded-lg py-2">
                    Register
                </button>
            @endguest

            @auth
                <a href="{{ route('candidate.dashboard') }}"
                    class="w-full text-center border border-gray-200 text-gray-700 rounded-lg py-2 hover:bg-gray-50">
                    Go to Dashboard
                </a>

                <form method="POST" action="{{ route('candidate.logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center bg-red-600 text-white rounded-lg py-2 hover:bg-red-700">
                        Log Out
                    </button>
                </form>
            @endauth
        </div>
    </div>

</nav>

<script>
    document.addEventListener("DOMContentLoaded", () => window.lucide?.createIcons());
</script>
@include('mainpage.components.partials.navbar-modals')