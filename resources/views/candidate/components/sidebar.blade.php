{{-- resources/views/candidate/components/sidebar.blade.php --}}
@php
    $is = fn($path) => request()->is($path);

    $items = [
        ['label' => 'Home', 'icon' => 'home', 'href' => url('/'), 'active' => request()->is('/')],
        ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'href' => url('/candidate/dashboard'), 'active' => $is('candidate/dashboard')],
        ['label' => 'Profile', 'icon' => 'user', 'href' => url('/candidate/profile'), 'active' => $is('candidate/profile')],
        ['label' => 'My Resume', 'icon' => 'file-text', 'href' => url('/candidate/my-resume'), 'active' => $is('candidate/my-resume')],
        ['label' => 'My Applied Jobs', 'icon' => 'briefcase', 'href' => url('/candidate/my-applied-jobs'), 'active' => $is('candidate/my-applied-jobs')],
        ['label' => 'Shortlist Jobs', 'icon' => 'bookmark', 'href' => url('/candidate/shortlist-jobs'), 'active' => $is('candidate/shortlist-jobs')],
        ['label' => 'Following Employers', 'icon' => 'users', 'href' => url('/candidate/following-employers'), 'active' => $is('candidate/following-employers')],
        ['label' => 'Job Alerts', 'icon' => 'bell', 'href' => url('/candidate/job-alerts'), 'active' => $is('candidate/job-alerts')],
        ['label' => 'Messages', 'icon' => 'messages-square', 'href' => url('/candidate/messages'), 'active' => $is('candidate/messages')],
        ['label' => 'Meetings', 'icon' => 'calendar', 'href' => url('/candidate/meetings'), 'active' => $is('candidate/meetings')],
        ['label' => 'Delete Profile', 'icon' => 'trash-2', 'href' => url('/candidate/delete-profile'), 'active' => $is('candidate/delete-profile')],
    ];
@endphp


<aside class="hidden lg:flex lg:fixed lg:inset-y-0 lg:w-64 lg:flex-col bg-white border-r border-gray-200">
    <div class="h-16 flex items-center px-6 border-b border-gray-100">
        <div class="flex items-center gap-2">
            <span
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 border border-emerald-100">
                <svg viewBox="0 0 24 24" class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M12 21s7-4.5 7-10a7 7 0 1 0-14 0c0 5.5 7 10 7 10z" />
                    <path d="M9 11l2 2 4-4" />
                </svg>
            </span>
            <span class="text-lg font-semibold text-gray-900">
                Work<span class="text-emerald-600">SITE</span>
            </span>
        </div>
    </div>

    <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto">
        @foreach ($items as $item)
            <a href="{{ $item['href'] }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                   {{ $item['active']
            ? 'bg-blue-50 text-blue-700 border border-blue-100'
            : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                <i data-lucide="{{ $item['icon'] }}"
                    class="h-5 w-5 {{ $item['active'] ? 'text-blue-700' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                <span class="truncate">{{ $item['label'] }}</span>
            </a>
        @endforeach

        <div class="pt-4">
            <div class="h-px bg-gray-100"></div>
        </div>


        <form method="POST" action="{{ route('candidate.logout') }}">
            @csrf
            <button type="submit"
                class="w-full group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                <i data-lucide="log-out" class="h-5 w-5 text-red-500"></i>
                <span>Log Out</span>
            </button>
        </form>
    </nav>
</aside>


<aside x-show="mobileSidebarOpen" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-200 lg:hidden"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
    <div class="h-16 flex items-center justify-between px-5 border-b border-gray-100">
        <span class="text-lg font-semibold text-gray-900">
            Work<span class="text-emerald-600">SITE</span>
        </span>

        <button @click="mobileSidebarOpen = false"
            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200">
            <i data-lucide="x" class="h-5 w-5"></i>
        </button>
    </div>

    <nav class="px-4 py-5 space-y-1 overflow-y-auto h-[calc(100vh-64px)]">
        @foreach ($items as $item)
            <a href="{{ $item['href'] }}" @click="mobileSidebarOpen = false" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                   {{ $item['active']
            ? 'bg-blue-50 text-blue-700 border border-blue-100'
            : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                <i data-lucide="{{ $item['icon'] }}"
                    class="h-5 w-5 {{ $item['active'] ? 'text-blue-700' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach

        <div class="pt-4">
            <div class="h-px bg-gray-100"></div>
        </div>


        <form method="POST" action="{{ route('candidate.logout') }}">
            @csrf
            <button type="submit"
                class="w-full group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                <i data-lucide="log-out" class="h-5 w-5 text-red-500"></i>
                <span>Log Out</span>
            </button>
        </form>
    </nav>
</aside>