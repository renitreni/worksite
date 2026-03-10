@php
    $user = auth()->user();
    $candidateProfile = $user?->candidateProfile;

    $firstName = $user ? explode(' ', $user->name)[0] : '';

    $photo =
        $candidateProfile && $candidateProfile->photo_path ? asset('storage/' . $candidateProfile->photo_path) : null;
@endphp

<nav id="navbar" class="fixed top-0 left-0 w-full z-[1000] transition-all duration-300 bg-transparent">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center h-16">

            <!-- LOGO -->
            <div class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="JobAbroad" class="h-14 w-auto">
            </div>

            <!-- DESKTOP NAV -->
            <div class="hidden md:flex items-center gap-8">

                <a href="{{ route('home') }}" class="nav-link text-white font-medium transition">
                    Home
                </a>

                <!-- SEARCH DROPDOWN -->
                <div class="relative group">

                    <button class="nav-link text-white font-medium transition flex items-center gap-1">
                        Search
                        <i data-lucide="chevron-down" class="w-4 h-4 transition group-hover:rotate-180"></i>
                    </button>

                    <div
                        class="absolute left-0 mt-3 w-56 rounded-xl bg-white shadow-xl border border-gray-100
                        opacity-0 invisible translate-y-2
                        transition-all duration-300
                        group-hover:opacity-100 group-hover:visible group-hover:translate-y-0">

                        <a href="{{ route('search-jobs') }}" class="dropdown-link">
                            Search Jobs
                        </a>

                        <a href="{{ route('search-agency') }}" class="dropdown-link">
                            Search Agencies
                        </a>

                        <a href="{{ route('search-industries') }}" class="dropdown-link">
                            Search Industries
                        </a>

                        <a href="{{ route('search-country') }}" class="dropdown-link">
                            Search Country
                        </a>

                    </div>

                </div>

                <a href="/about" class="nav-link text-white font-medium transition">About</a>

                <a href="/contact" class="nav-link text-white font-medium transition">Contact</a>

            </div>

            <!-- AUTH -->
            <div class="hidden md:flex items-center gap-3">

                @guest

                    <button id="loginBtnDesktop"
                        class="px-4 py-2 rounded-lg font-medium nav-link text-white font-medium transition hover:text-white transition">
                        Login
                    </button>

                    <button id="registerBtnDesktop"
                        class="px-5 py-2 rounded-xl font-semibold bg-[#16A34A] text-white
                    hover:bg-green-600 transition shadow-md">
                        Register
                    </button>

                @endguest


                @auth
                    @if (auth()->user()->role === 'candidate')
                        <div x-data="{ open: false }" class="relative">

                            <button @click="open = !open"
                                class="flex items-center gap-3 backdrop-blur-md
    border border-white/30 rounded-xl px-3 py-2 shadow-sm hover:shadow-md transition">

                                {{-- PROFILE PHOTO --}}
                                <div
                                    class="h-9 w-9 rounded-full overflow-hidden bg-green-100 flex items-center justify-center">

                                    @if ($photo)
                                        <img src="{{ $photo }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="user" class="h-4 w-4 text-green-700"></i>
                                    @endif

                                </div>

                                {{-- FIRST NAME ONLY --}}
                                <span class="nav-link text-sm font-medium text-white">
                                    {{ $firstName }}
                                </span>

                                <i data-lucide="chevron-down" class="nav-link h-4 w-4 text-white"></i>

                            </button>

                            <!-- DROPDOWN -->

                            <div x-cloak x-show="open" x-transition @click.outside="open=false"
                                class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl border border-gray-100">

                                <a href="{{ route('candidate.dashboard') }}"
                                    class="flex items-center gap-2 text-gray-700 px-4 py-3 hover:bg-gray-50 rounded-lg transition">

                                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                    Dashboard

                                </a>

                                <div class="border-t"></div>

                                <form method="POST" action="{{ route('candidate.logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-3 text-red-600 hover:bg-gray-50 rounded-lg transition">

                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        Log Out

                                    </button>
                                </form>

                            </div>

                        </div>
                    @endif
                @endauth

            </div>

            <button id="mobile-menu-button"
                class="md:hidden flex items-center justify-center relative z-[1000] w-10 h-10">

                <i data-lucide="menu" id="icon-menu" class="w-6 h-6 absolute transition-all duration-300"></i>

                <i data-lucide="x" id="icon-close"
                    class="w-6 h-6 absolute text-gray-600 opacity-0 scale-75 transition-all duration-300"></i>

            </button>


        </div>
    </div>
    <div id="mobile-menu"
        class="hidden md:hidden fixed top-0 left-0 w-full bg-white shadow-xl border-t border-gray-100 z-[900] pt-16">
        <div class="flex flex-col text-gray-700">

            <a href="{{ route('home') }}" class="px-6 py-4 hover:bg-gray-50">
                Home
            </a>


            <!-- SEARCH DROPDOWN -->

            <button id="mobile-search-btn" class="flex justify-between items-center px-6 py-4 hover:bg-gray-50 w-full">

                Search

                <i data-lucide="chevron-down" class="mobile-search-icon w-4 h-4 transition-transform"></i>
            </button>

            <div id="mobile-search-dropdown" class="hidden flex flex-col">
                <a href="{{ route('search-jobs') }}" class="px-10 py-3 hover:bg-gray-50">
                    Search Jobs
                </a>

                <a href="{{ route('search-agency') }}" class="px-10 py-3 hover:bg-gray-50">
                    Search Agencies
                </a>

                <a href="{{ route('search-industries') }}" class="px-10 py-3 hover:bg-gray-50">
                    Search Industries
                </a>

                <a href="{{ route('search-country') }}" class="px-10 py-3 hover:bg-gray-50">
                    Search Country
                </a>

            </div>


            <a href="/about" class="px-6 py-4 hover:bg-gray-50">
                About
            </a>

            <a href="/contact" class="px-6 py-4 hover:bg-gray-50">
                Contact
            </a>

            <div class="border-t"></div>

            @guest

                <button id="loginBtnMobile" class="mx-6 mt-4 py-2 border border-green-600 text-green-600 rounded-lg">
                    Login
                </button>

                <button id="registerBtnMobile" class="mx-6 my-4 py-2 bg-green-600 text-white rounded-lg">
                    Register
                </button>

            @endguest


            @auth

                <a href="{{ route('candidate.dashboard') }}"
                    class="mx-6 mt-4 py-2 text-center border border-gray-200 rounded-lg">
                    Dashboard
                </a>

                <form method="POST" action="{{ route('candidate.logout') }}" class="mx-6 my-4">
                    @csrf

                    <button type="submit" class="w-full py-2 bg-red-600 text-white rounded-lg">
                        Logout
                    </button>

                </form>

            @endauth

        </div>

    </div>
</nav>


@include('mainpage.components.partials.navbar-modals')
@include('mainpage.components.partials.navbar-script')
