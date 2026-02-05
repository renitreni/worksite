{{-- resources/views/employer/components/sidebar.blade.php --}}
@php
    $is = fn ($path) => request()->is($path);

    $items = [
        ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'href' => route('employer.dashboard'), 'active' => $is('employer/dashboard')],
        ['label' => 'Company Profile', 'icon' => 'user', 'href' => url('/employer/company-profile'), 'active' => $is('employer/company-profile')],
        [
            'label' => 'Job Postings',
            'icon' => 'briefcase',
            'dropdown' => [
                ['label' => 'Active Jobs', 'href' => url('/employer/job-postings/active')],
                ['label' => 'Closed Jobs', 'href' => url('/employer/job-postings/closed')],
            ]
        ],
        [
            'label' => 'Applicants',
            'icon' => 'users',
            'dropdown' => [
                ['label' => 'All Applicants', 'href' => url('/employer/applicants/all')],
                ['label' => 'Shortlisted', 'href' => url('/employer/applicants/shortlisted')],
                ['label' => 'Rejected', 'href' => url('/employer/applicants/rejected')],
            ]
        ],
        ['label' => 'Analytics', 'icon' => 'bar-chart-2', 'href' => url('/employer/analytics')],
        ['label' => 'Subscription / Plan', 'icon' => 'credit-card', 'href' => url('/employer/subscription')],
    ];
@endphp

<aside x-data="{ openDropdown: null }" class="hidden lg:flex lg:fixed lg:inset-y-0 lg:w-64 lg:flex-col bg-white border-r border-gray-200">
    <div class="h-20 flex items-center justify-center border-b border-gray-100 px-4">
        <img src="{{ asset('images/logo.png') }}" alt="WorkSite Logo" class="h-24 w-auto" />
    </div>

    <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto">
        @foreach ($items as $item)
            @if(isset($item['dropdown']))
                {{-- Dropdown --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="group flex items-center gap-3 w-full rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                        <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5 text-gray-400 group-hover:text-gray-600"></i>
                        <span class="flex-1 text-left">{{ $item['label'] }}</span>
                        <i data-lucide="chevron-down" :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-gray-400 transition-transform"></i>
                    </button>
                    <div x-show="open" x-transition class="ml-7 mt-1 space-y-1">
                        @foreach ($item['dropdown'] as $sub)
                            <a href="{{ $sub['href'] }}"
                               class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition">
                                {{ $sub['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Single link --}}
                <a href="{{ $item['href'] }}"
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition">
                    <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5 text-gray-400 group-hover:text-gray-600"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach

        <div class="pt-4">
            <div class="h-px bg-gray-100"></div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition cursor-pointer">
                <i data-lucide="log-out" class="h-5 w-5 text-red-500"></i>
                <span>Log Out</span>
            </button>
        </form>
    </nav>
</aside>