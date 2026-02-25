<header class="sticky top-0 z-30 bg-white border-b border-gray-200">
    <div class="h-16 px-3 sm:px-6 lg:px-8 flex items-center">

        {{-- Mobile hamburger --}}
        <button
            type="button"
            class="lg:hidden inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition"
            @click="mobileSidebarOpen = true"
            aria-label="Open sidebar"
        >
            <i data-lucide="menu" class="h-5 w-5 text-gray-700"></i>
        </button>

        {{-- Spacer --}}
        <div class="flex-1"></div>

        {{-- Right actions --}}
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">

            {{-- Post Job button --}}
            <button type="button"
                class="hidden md:inline-flex items-center gap-2 rounded-2xl border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600 transition cursor-pointer"
                onclick="window.location.href='{{ route('employer.job-postings.create', ['from' => 'navbar']) }}'">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Post Job
            </button>

            {{-- Messages --}}
            <button
                type="button"
                class="relative inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition cursor-pointer"
                @click="alert('Open Messages (frontend-only)')"
                aria-label="Messages"
            >
                <i data-lucide="message-circle" class="h-5 w-5 text-gray-600"></i>
                <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-emerald-500 ring-2 ring-white"></span>
            </button>

            {{-- Analytics --}}
            <button
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition cursor-pointer"
                @click="alert('Open Analytics Dashboard')"
                aria-label="Analytics"
            >
                <i data-lucide="bar-chart-2" class="h-5 w-5 text-gray-600"></i>
            </button>

            {{-- Profile Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button
                    type="button"
                    @click="open = !open"
                    class="flex items-center gap-2 rounded-2xl border border-gray-200 bg-white px-2 sm:px-3 py-2 hover:bg-gray-50 transition cursor-pointer"
                >
                    <div class="h-9 w-9 rounded-full bg-gray-200 flex items-center justify-center ring-2 ring-gray-100">
                        <i data-lucide="building" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <div class="hidden md:block text-left leading-tight">
                        {{-- Use the company name from the authenticated user's profile --}}
                        <p class="text-sm font-semibold text-gray-900">
                            {{ auth()->user()->employerProfile->company_name ?? 'Your Company' }}
                        </p>
                        <p class="text-xs text-gray-500">Verified Employer</p>
                    </div>
                    <i data-lucide="chevron-down" class="hidden md:block h-4 w-4 text-gray-500"></i>
                </button>
                <div
                    x-show="open"
                    x-transition
                    @click.outside="open = false"
                    class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg z-50"
                >
                    <a href="{{ route('employer.company-profile') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        <i data-lucide="user" class="h-4 w-4 text-gray-500"></i>
                        Company Profile
                    </a>
                    <a href="{{ route('employer.subscription.dashboard') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        <i data-lucide="credit-card" class="h-4 w-4 text-gray-500"></i>
                        Subscription / Plan
                    </a>
                    <div class="h-px bg-gray-100"></div>
                    <button
                        type="button"
                        onclick="window.location.href='{{ url('/login') }}'"
                        class="flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition cursor-pointer"
                    >
                        <i data-lucide="log-out" class="h-4 w-4 text-red-500"></i>
                        Log Out
                    </button>
                </div>
            </div>

        </div>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", () => window.lucide?.createIcons());
</script>