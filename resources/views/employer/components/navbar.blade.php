@php
    $profile = auth()->user()->employerProfile ?? null;

    $accessService = app(\App\Services\Employer\EmployerAccessService::class);

    $canUseMessaging = $profile ? $accessService->canUseDirectMessaging($profile) : false;
@endphp

<header wire:ignore.self class="sticky top-0 z-30 bg-white border-b border-gray-200">
    <div class="h-16 min-h-[64px] px-3 sm:px-6 lg:px-8 flex items-center">
        {{-- Mobile hamburger --}}
        <button type="button"
            class="lg:hidden inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition"
            @click="mobileSidebarOpen = true" aria-label="Open sidebar">
            <x-lucide-icon name="menu" class="h-5 w-5 text-gray-700" />
        </button>

        {{-- Spacer --}}
        <div class="flex-1"></div>

        {{-- Right actions --}}
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">

            {{-- Post Job button --}}
            <button type="button"
                class="hidden md:inline-flex items-center gap-2 rounded-2xl border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600 transition cursor-pointer"
                onclick="window.location.href='{{ route('employer.job-postings.create', ['from' => 'navbar']) }}'">
                <x-lucide-icon name="plus" class="h-4 w-4" />
                Post Job
            </button>

            {{-- Messages --}}
            @if ($canUseMessaging)
                <a href="{{ route('employer.chat.index') }}"
                    class="relative inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition"
                    aria-label="Messages">

                    <x-lucide-icon name="message-circle" class="h-5 w-5 text-gray-600" />

                    <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-emerald-500 ring-2 ring-white"></span>

                </a>
            @else
                <button @click="$dispatch('open-upgrade-modal')"
                    class="relative inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-gray-50 hover:bg-gray-100 transition"
                    aria-label="Upgrade to use messaging">

                    <x-lucide-icon name="message-circle" class="h-5 w-5 text-gray-400" />

                    <span
                        class="absolute -top-1 -right-1 text-[9px] font-bold px-1.5 py-0.5 rounded bg-yellow-100 text-yellow-700">
                        PRO
                    </span>

                </button>
            @endif

            {{-- Analytics --}}
            <button type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition cursor-pointer"
                @click="window.location='{{ route('employer.analytics') }}'" aria-label="Analytics">
                <x-lucide-icon name="bar-chart-2" class="h-5 w-5 text-gray-600" />
            </button>

            <x-notification-bell wire:ignore />

            {{-- Profile Dropdown --}}
            <div x-cloak x-data="{ open: false }" class="relative">
                <button type="button" @click="open = !open"
                    class="flex items-center gap-2 rounded-2xl border border-gray-200 bg-white px-2 sm:px-3 py-2 hover:bg-gray-50 transition cursor-pointer">
                    <div class="h-9 w-9 rounded-full bg-gray-200 flex items-center justify-center ring-2 ring-gray-100">
                        <x-lucide-icon name="building" class="h-5 w-5 text-gray-400" />
                    </div>
                    <div class="hidden md:block text-left leading-tight">
                        {{-- Use the company name from the authenticated user's profile --}}
                        <p class="text-sm font-semibold text-gray-900">
                            {{ auth()->user()->employerProfile->company_name ?? 'Your Company' }}
                        </p>
                        <p class="text-xs text-gray-500">Verified Employer</p>
                    </div>
                    <x-lucide-icon name="chevron-down" class="hidden md:block h-4 w-4 text-gray-500" />
                </button>
                <div x-show="open" x-transition.opacity.scale.origin.top.right @click.outside="open = false"
                    class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg z-50">
                    <a href="{{ route('employer.company-profile') }}"
                        class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        <x-lucide-icon name="user" class="h-4 w-4 text-gray-500" />
                        Company Profile
                    </a>
                    <a href="{{ route('employer.subscription.dashboard') }}"
                        class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        <x-lucide-icon name="credit-card" class="h-4 w-4 text-gray-500" />
                        Subscription / Plan
                    </a>
                    <div class="h-px bg-gray-100"></div>
                    <button type="button" onclick="window.location.href='{{ url('/login') }}'"
                        class="flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition cursor-pointer">
                        <x-lucide-icon name="log-out" class="h-4 w-4 text-red-500" />
                        Log Out
                    </button>
                </div>
            </div>

        </div>
    </div>
</header>
<div 
    x-data="{ open:false }"
    x-on:open-upgrade-modal.window="open=true"
    x-show="open"
    x-transition
    class="fixed inset-0 flex items-center justify-center bg-black/40 z-50"
>

    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">

        <h2 class="text-lg font-semibold text-gray-900">
            Upgrade Required
        </h2>

        <p class="mt-2 text-sm text-gray-600 leading-relaxed">
            Direct messaging is available only for the 
            <span class="font-semibold text-emerald-600">Platinum Plan</span>.
            Upgrade your subscription to contact candidates directly.
        </p>

        <div class="mt-6 flex justify-end gap-3">

            <button
                @click="open=false"
                class="px-4 py-2 text-sm rounded-lg border border-gray-200 hover:bg-gray-50">
                Cancel
            </button>

            <a href="{{ route('employer.subscription.dashboard') }}"
                class="px-4 py-2 text-sm rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                Upgrade Plan
            </a>

        </div>

    </div>

</div>