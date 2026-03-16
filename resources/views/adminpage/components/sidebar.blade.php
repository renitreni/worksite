@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Setting;

    $adminUser = Auth::guard('admin')->user();

    $siteName = Setting::get('site.name', 'WorkSITE');
    $logoPath = Setting::get('site.logo_path', null);

    $logoUrl = $logoPath
        ? (str_starts_with($logoPath, 'http')
            ? $logoPath
            : asset(ltrim($logoPath, '/')))
        : asset('images/logo.png');

    $items = [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'layout-dashboard',
            'active' => 'admin.dashboard*',
        ],
        [
            'label' => 'Users',
            'route' => 'admin.users.index',
            'icon' => 'shield-check',
            'active' => 'admin.users.*',
        ],
        [
            'label' => 'Job Postings',
            'route' => 'admin.job-posts.index',
            'icon' => 'briefcase',
            'active' => 'admin.job-posts.*',
        ],

        [
            'label' => 'Manage Lists',
            'icon' => 'list',
            'active' => 'admin.industries.*|admin.skills.*|admin.locations.*|admin.location_suggestions.*',
            'children' => [
                [
                    'label' => 'Industries',
                    'route' => 'admin.industries.index',
                    'icon' => 'building-2',
                    'active' => 'admin.industries.*',
                ],
                [
                    'label' => 'Skills',
                    'route' => 'admin.skills.index',
                    'icon' => 'badge-check',
                    'active' => 'admin.skills.*',
                ],
                [
                    'label' => 'Locations',
                    'route' => 'admin.locations.countries.index',
                    'icon' => 'map-pin',
                    'active' => 'admin.locations.*',
                ],
                [
                    'label' => 'Location Suggestions',
                    'route' => 'admin.location_suggestions.index',
                    'icon' => 'message-square-plus',
                    'active' => 'admin.location_suggestions.*',
                ],
            ],
        ],

        [
            'label' => 'Subscriptions & Payments',
            'icon' => 'credit-card',
            'active' => 'admin.subscriptions.*',
            'children' => [
                [
                    'label' => 'Plans',
                    'route' => 'admin.subscriptions.plans.index',
                    'icon' => 'package',
                    'active' => 'admin.subscriptions.plans.*',
                ],
                [
                    'label' => 'Payments',
                    'route' => 'admin.subscriptions.payments.index',
                    'icon' => 'receipt',
                    'active' => 'admin.subscriptions.payments.*',
                ],
                [
                    'label' => 'Subscriptions',
                    'route' => 'admin.subscriptions.index',
                    'icon' => 'repeat',
                    'active' => 'admin.subscriptions.index|admin.subscriptions.activate|admin.subscriptions.suspend',
                ],
                [
                    'label' => 'Expired',
                    'route' => 'admin.subscriptions.expired',
                    'icon' => 'calendar-x',
                    'active' => 'admin.subscriptions.expired|admin.subscriptions.remind',
                ],
            ],
        ],

        [
            'label' => 'Reports',
            'route' => 'admin.reports',
            'icon' => 'bar-chart-3',
            'active' => 'admin.reports*',
        ],
        [
            'label' => 'Messages',
            'route' => 'admin.messages.index',
            'icon' => 'mail',
            'active' => 'admin.messages.*',
            'badge' => \App\Models\ContactMessage::where('is_read', false)->count(),
        ],
    ];

    if ($adminUser && ($adminUser->role ?? null) === 'superadmin') {
        // Admin Accounts
        array_splice($items, 2, 0, [
            [
                'label' => 'Admin Accounts',
                'route' => 'admin.admins.index',
                'icon' => 'shield',
                'active' => 'admin.admins.*',
            ],
        ]);

        // System Settings
        $items[] = [
            'label' => 'System Settings',
            'icon' => 'settings',
            'active' => 'admin.system.*|admin.email_templates.*|admin.backups.*|admin.settings*',
            'children' => [
                [
                    'label' => 'System Configuration',
                    'route' => 'admin.system.index',
                    'icon' => 'sliders-horizontal',
                    'active' => 'admin.system.*',
                ],
                [
                    'label' => 'Email Templates',
                    'route' => 'admin.email_templates.index',
                    'icon' => 'mail',
                    'active' => 'admin.email_templates.*',
                ],
                [
                    'label' => 'Backups',
                    'route' => 'admin.backups.index',
                    'icon' => 'database',
                    'active' => 'admin.backups.*',
                ],
            ],
        ];
    }

    $isActive = function (array $it): bool {
        $pattern = $it['active'] ?? ($it['route'] ?? '') . '*';
        foreach (explode('|', $pattern) as $p) {
            if ($p && request()->routeIs(trim($p))) {
                return true;
            }
        }
        return false;
    };
@endphp
{{-- Mobile overlay --}}
<div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"
    @click="sidebarOpen = false" aria-hidden="true"></div>

{{-- Mobile sidebar --}}
<aside x-cloak x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-xl lg:hidden"
    @keydown.escape.window="sidebarOpen = false" role="dialog" aria-modal="true">
    <div class="flex h-full flex-col">

        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="w-full max-h-36 object-contain"
                decoding="async" fetchpriority="high"> <button type="button"
                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                @click="sidebarOpen = false">
                Close
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4">
            <div class="space-y-1">
                @foreach ($items as $it)
                    @php
                        $active = $isActive($it);
                        $hasChildren = isset($it['children']) && is_array($it['children']);
                    @endphp

                    @if ($hasChildren)
                        @php $groupActive = $active; @endphp

                        <div x-data="{ open: {{ $groupActive ? 'true' : 'false' }} }" class="space-y-1">
                            <button type="button" @click="open = !open" title="{{ $it['label'] }}"
                                class="w-full flex min-w-0 items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold
                  {{ $groupActive ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'text-slate-700 hover:bg-slate-50' }}">
                                <span class="flex min-w-0 items-center gap-3 text-left">
                                    <x-lucide-icon name="{{ $it['icon'] ?? 'circle' }}" class="h-4 w-4 shrink-0" />
                                    <span class="min-w-0 flex-1 truncate">{{ $it['label'] }}</span>
                                </span>

                                <x-lucide-icon name="chevron-down" class="h-4 w-4 shrink-0 transition-transform"
                                    x-bind:class="open ? 'rotate-180' : ''" />
                            </button>

                            <div x-show="open" x-collapse class="pl-4">
                                <div class="space-y-1">
                                    @foreach ($it['children'] as $ch)
                                        @php $chActive = $isActive($ch); @endphp
                                        <a wire:navigate href="{{ route($ch['route']) }}" @click="sidebarOpen = false"
                                            title="{{ $ch['label'] }}"
                                            class="flex min-w-0 items-center gap-3 rounded-xl px-4 py-2 text-sm font-semibold
                        {{ $chActive ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'text-slate-700 hover:bg-slate-50' }}">
                                            <x-lucide-icon name="{{ $ch['icon'] ?? 'dot' }}"
                                                class="h-4 w-4 shrink-0 opacity-80" />
                                            <span class="min-w-0 flex-1 truncate">{{ $ch['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a wire:navigate href="{{ route($it['route']) }}" @click="sidebarOpen = false"
                            title="{{ $it['label'] }}"
                            class="flex min-w-0 items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold
                {{ $active ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'text-slate-700 hover:bg-slate-50' }}">
                            <x-lucide-icon name="{{ $it['icon'] ?? 'circle' }}" class="h-4 w-4 shrink-0" />
                            <span class="min-w-0 flex-1 truncate">{{ $it['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </nav>

    </div>
</aside>

{{-- Desktop sidebar --}}
<aside class="sticky top-0 hidden h-screen w-72 border-r border-slate-200 bg-white lg:flex lg:flex-col">
    <div class="px-6 py-6">
        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="w-full max-h-36 object-contain"
            decoding="async" fetchpriority="high">
    </div>

    <nav class="px-3 flex-1 overflow-y-auto">
        <div class="space-y-1">
            @foreach ($items as $it)
                @php
                    $active = $isActive($it);
                    $hasChildren = isset($it['children']) && is_array($it['children']);
                @endphp

                @if ($hasChildren)
                    @php $groupActive = $active; @endphp

                    <div x-data="{ open: {{ $groupActive ? 'true' : 'false' }} }" class="space-y-1">
                        <button type="button" @click="open = !open" title="{{ $it['label'] }}"
                            class="w-full flex min-w-0 items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold
                {{ $groupActive ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span class="flex min-w-0 items-center gap-3 text-left w-full">
                                <x-lucide-icon name="{{ $it['icon'] ?? 'circle' }}" class="h-4 w-4 shrink-0" />

                                <span class="flex-1 truncate">
                                    {{ $it['label'] }}
                                </span>

                                @if (isset($it['badge']) && $it['badge'] > 0)
                                    <span class="ml-auto text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                        {{ $it['badge'] }}
                                    </span>
                                @endif
                            </span>

                            <x-lucide-icon name="chevron-down" class="h-4 w-4 shrink-0 transition-transform"
                                x-bind:class="open ? 'rotate-180' : ''" />
                        </button>

                        <div x-show="open" x-collapse class="pl-4">
                            <div class="space-y-1">
                                @foreach ($it['children'] as $ch)
                                    @php $chActive = $isActive($ch); @endphp
                                    <a wire:navigate href="{{ route($ch['route']) }}" title="{{ $ch['label'] }}"
                                        class="flex min-w-0 items-center gap-3 rounded-xl px-4 py-2 text-sm font-semibold
                      {{ $chActive ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'text-slate-700 hover:bg-slate-50' }}">
                                        <x-lucide-icon name="{{ $ch['icon'] ?? 'dot' }}"
                                            class="h-4 w-4 shrink-0 opacity-80" />
                                        <span class="min-w-0 flex-1 truncate">{{ $ch['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <a wire:navigate href="{{ route($it['route']) }}" title="{{ $it['label'] }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold
{{ $active ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'text-slate-700 hover:bg-slate-50' }}">

                        <x-lucide-icon name="{{ $it['icon'] ?? 'circle' }}" class="h-4 w-4 shrink-0" />

                        <span class="flex-1 truncate">
                            {{ $it['label'] }}
                        </span>

                        {{-- BADGE --}}
                        @if (isset($it['badge']) && $it['badge'] > 0)
                            <span class="ml-auto text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                {{ $it['badge'] }}
                            </span>
                        @endif

                    </a>
                @endif
            @endforeach
        </div>
    </nav>
</aside>
