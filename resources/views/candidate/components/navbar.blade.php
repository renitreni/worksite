@php
    $photo = optional(auth()->user()->candidateProfile)->photo_path;

    $first = strtoupper(substr(auth()->user()->first_name ?? '', 0, 1));
    $last = strtoupper(substr(auth()->user()->last_name ?? '', 0, 1));
@endphp

<header class="sticky top-0 z-30 bg-white border-b border-gray-200">
    <div class="h-16 min-h-[64px] px-3 sm:px-6 lg:px-8 flex items-center gap-2 sm:gap-3">
        {{-- Mobile hamburger --}}
        <button type="button"
            class="lg:hidden inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition"
            @click="mobileSidebarOpen = true" aria-label="Open sidebar">
            <x-lucide-icon name="menu" class="h-5 w-5 text-gray-700" />
        </button>

        {{-- Search (STATIC – no Alpine logic) --}}
        <div class="flex-1 min-w-0">
            <div class="relative w-full max-w-none lg:max-w-3xl">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <x-lucide-icon name="search" class="h-5 w-5" />
                </span>

                <input type="text" placeholder="Search jobs, companies, or keywords…"
                    class="w-full min-w-0 rounded-2xl border border-gray-200 bg-gray-50 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />
            </div>
        </div>

        {{-- Right Section --}}
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">

            <x-notification-bell wire:ignore />
            {{-- Profile Dropdown --}}
            <div x-cloak x-data="{ open: false }" class="relative">

                <button type="button" @click="open = !open" @keydown.escape.window="open = false"
                    class="flex items-center gap-2 sm:gap-3 rounded-2xl border border-gray-200 bg-white px-2 sm:px-3 py-2 hover:bg-gray-50 transition">

                    @if ($photo)
                        <img src="{{ asset('storage/' . $photo) }}" alt="Avatar"
                            class="h-9 w-9 rounded-full object-cover ring-2 ring-gray-100" />
                    @else
                        <div
                            class="h-9 w-9 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold ring-2 ring-gray-100">
                            {{ $first }}{{ $last }}
                        </div>
                    @endif

                    <div class="hidden md:block text-left leading-tight">
                        <p class="text-sm font-semibold text-gray-900">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <x-lucide-icon name="chevron-down" class="hidden md:block h-4 w-4 text-gray-500" />
                </button>

                {{-- Dropdown --}}
                <div x-show="open" x-transition.opacity.scale.origin.top.right @click.outside="open = false"
                    class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg z-50">

                    <a href="#" class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        <x-lucide-icon name="user" class="h-4 w-4 text-gray-500" />
                        <span>My Profile</span>
                    </a>

                    <a href="#" class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        <x-lucide-icon name="settings" class="h-4 w-4 text-gray-500" />
                        <span>Settings</span>
                    </a>

                    <div class="h-px bg-gray-100"></div>

                    <form method="POST" action="{{ route('candidate.logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                            <x-lucide-icon name="log-out" class="h-4 w-4 text-red-500" />
                            <span>Log Out</span>
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</header>
