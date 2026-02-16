@php
    $photo = optional(auth()->user()->candidateProfile)->photo_path;

    $first = strtoupper(substr(auth()->user()->first_name ?? '', 0, 1));
    $last  = strtoupper(substr(auth()->user()->last_name ?? '', 0, 1));
@endphp



<header class="sticky top-0 z-30 bg-white border-b border-gray-200">
    <div class="h-16 px-3 sm:px-6 lg:px-8 flex items-center gap-2 sm:gap-3">

        {{-- Mobile hamburger --}}
        <button type="button"
            class="lg:hidden inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition"
            @click="mobileSidebarOpen = true" aria-label="Open sidebar">
            <i data-lucide="menu" class="h-5 w-5 text-gray-700"></i>
        </button>

        {{-- Search --}}
        <div class="flex-1 min-w-0" x-data="worksiteSearch()" x-init="init()">
            <div class="relative w-full max-w-none lg:max-w-3xl">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i data-lucide="search" class="h-5 w-5"></i>
                </span>

                <input type="text" x-model="query" @input.debounce.200ms="onInput()" @focus="open = true; onInput()"
                    @keydown.escape="close()" @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)"
                    @keydown.enter.prevent="pickActive()" placeholder="Search jobs, companies, or keywords…"
                    class="w-full min-w-0 rounded-2xl border border-gray-200 bg-gray-50 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300" />

                {{-- Search Results Dropdown (responsive width) --}}
                <div x-show="open && (loading || results.length)" x-transition.origin.top.left @click.outside="close()"
                    class="absolute left-0 z-50 mt-2 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg
                           w-[92vw] sm:w-full max-w-none lg:max-w-3xl">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-xs text-gray-500" x-show="query.trim().length">
                            Showing results for: <span class="font-semibold text-gray-700" x-text="query"></span>
                        </p>
                        <p class="text-xs text-gray-500" x-show="!query.trim().length">
                            Type something to search…
                        </p>
                    </div>

                    <div class="p-3" x-show="loading">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span
                                class="h-4 w-4 rounded-full border-2 border-gray-200 border-t-gray-500 animate-spin"></span>
                            Searching...
                        </div>
                    </div>

                    <ul class="max-h-80 overflow-auto" x-show="!loading">
                        <template x-for="(item, idx) in results" :key="item.id">
                            <li>
                                <button type="button"
                                    class="w-full px-4 py-3 text-left flex items-start gap-3 hover:bg-gray-50"
                                    :class="activeIndex === idx ? 'bg-gray-50' : ''" @mouseenter="activeIndex = idx"
                                    @click="select(item)">
                                    <div class="mt-0.5 shrink-0">
                                        <div
                                            class="h-9 w-9 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center">
                                            <i data-lucide="briefcase" class="h-4 w-4 text-gray-500"></i>
                                        </div>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="item.title"></p>
                                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="item.subtitle"></p>

                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            <span class="text-[11px] px-2 py-0.5 rounded-full border"
                                                :class="item.tagColor" x-text="item.tag"></span>
                                            <span class="text-[11px] text-gray-500 truncate" x-text="item.meta"></span>
                                        </div>
                                    </div>

                                    <div class="text-gray-400 shrink-0">
                                        <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                    </div>
                                </button>
                            </li>
                        </template>

                        <li class="border-t border-gray-100">
                            <button type="button"
                                class="w-full px-4 py-3 text-sm font-semibold text-emerald-600 hover:bg-emerald-50 flex items-center justify-between"
                                @click="viewAll()">
                                View all results
                                <i data-lucide="chevron-right" class="h-4 w-4"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Right --}}
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">

            {{-- Bell --}}
            <div class="relative" x-data="notificationBell()" x-init="init()">
                <button type="button" @click="toggle()"
                    class="relative inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition"
                    aria-label="Notifications">
                    <i data-lucide="bell" class="h-5 w-5 text-gray-600"></i>

                    <span x-show="unreadCount > 0"
                        class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                </button>

                {{-- Bell Dropdown (responsive width) --}}
                <div x-show="open" x-transition.origin.top.right @click.outside="open = false" class="absolute right-0 mt-2 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg z-50
                           w-[92vw] sm:w-[360px]">
                    <div class="px-4 py-3 border-b border-gray-100 flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Notifications</p>
                            <p class="text-xs text-gray-500" x-show="unreadCount > 0">
                                You have <span class="font-semibold" x-text="unreadCount"></span> unread
                            </p>
                            <p class="text-xs text-gray-500" x-show="unreadCount === 0">All caught up 🎉</p>
                        </div>

                        <button type="button" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700"
                            x-show="unreadCount > 0" @click="markAllRead()">
                            Mark all
                        </button>
                    </div>

                    <div class="max-h-96 overflow-auto">
                        <template x-for="n in notifications" :key="n.id">
                            <button type="button" class="w-full text-left px-4 py-3 flex gap-3 hover:bg-gray-50"
                                @click="openNotification(n)">
                                <div class="mt-0.5 shrink-0">
                                    <div class="h-10 w-10 rounded-2xl flex items-center justify-center border"
                                        :class="n.iconBg">
                                        <i :data-lucide="n.icon" class="h-5 w-5" :class="n.iconColor"></i>
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="n.title"></p>
                                        <span class="text-[11px] text-gray-400 shrink-0" x-text="n.time"></span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 leading-5 line-clamp-2" x-text="n.body"></p>
                                </div>

                                <div class="pt-1 shrink-0">
                                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-500"
                                        x-show="!n.read"></span>
                                </div>
                            </button>
                        </template>
                    </div>

                    <div class="border-t border-gray-100 p-2">
                        <button type="button"
                            class="w-full rounded-xl px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center justify-between"
                            @click="open = false">
                            Close
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Profile Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button type="button" @click="open = !open" @keydown.escape.window="open = false"
                    class="flex items-center gap-2 sm:gap-3 rounded-2xl border border-gray-200 bg-white px-2 sm:px-3 py-2 hover:bg-gray-50 transition">
                    @if($photo)
                        <img src="{{ asset('storage/' . $photo) }}" alt="Avatar"
                            class="h-9 w-9 rounded-full object-cover ring-2 ring-gray-100" />
                    @else
                        <div
                            class="h-9 w-9 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold ring-2 ring-gray-100">
                            {{ $first }}{{ $last }}
                        </div>
                    @endif
                    <div class="hidden md:block text-left leading-tight">
                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <i data-lucide="chevron-down" class="hidden md:block h-4 w-4 text-gray-500"></i>
                </button>

                <div x-show="open" x-transition @click.outside="open = false"
                    class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg z-50">
                    <a href="#" class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        <i data-lucide="user" class="h-4 w-4 text-gray-500"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="#" class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        <i data-lucide="settings" class="h-4 w-4 text-gray-500"></i>
                        <span>Settings</span>
                    </a>
                    <div class="h-px bg-gray-100"></div>
                    <a href="#" class="flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                        <i data-lucide="log-out" class="h-4 w-4 text-red-500"></i>
                        <span>Log Out</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</header>

<script>
    // SEARCH
    function worksiteSearch() {
        return {
            query: "",
            open: false,
            loading: false,
            results: [],
            activeIndex: -1,

            items: [
                { id: 1, title: "Dashboard", subtitle: "Go to Dashboard overview", tag: "Page", tagColor: "border-emerald-200 bg-emerald-50 text-emerald-700", meta: "Candidate • Home" },
                { id: 2, title: "Profile", subtitle: "View and edit your profile", tag: "Page", tagColor: "border-emerald-200 bg-emerald-50 text-emerald-700", meta: "Candidate" },
                { id: 3, title: "My Resume", subtitle: "Manage your resume", tag: "Page", tagColor: "border-emerald-200 bg-emerald-50 text-emerald-700", meta: "Candidate" },
                { id: 4, title: "My Applied Jobs", subtitle: "Track your applications", tag: "Feature", tagColor: "border-blue-200 bg-blue-50 text-blue-700", meta: "Jobs" },
                { id: 5, title: "Following Employers", subtitle: "Companies you follow", tag: "Feature", tagColor: "border-purple-200 bg-purple-50 text-purple-700", meta: "Tracking" },
                { id: 6, title: "Job Alerts", subtitle: "Your alerts and matches", tag: "Feature", tagColor: "border-amber-200 bg-amber-50 text-amber-700", meta: "Notifications" },
                { id: 7, title: "Messages", subtitle: "Your inbox conversations", tag: "Feature", tagColor: "border-gray-200 bg-gray-50 text-gray-700", meta: "Chat" },
                { id: 8, title: "Meetings", subtitle: "Your scheduled meetings", tag: "Feature", tagColor: "border-emerald-200 bg-emerald-50 text-emerald-700", meta: "Calendar" },
                { id: 9, title: "Change Password", subtitle: "Update your password", tag: "Settings", tagColor: "border-gray-200 bg-gray-50 text-gray-700", meta: "Security" },
                { id: 10, title: "Delete Profile", subtitle: "Delete your account", tag: "Settings", tagColor: "border-red-200 bg-red-50 text-red-700", meta: "Danger zone" },
            ],

            init() { },

            onInput() {
                const q = this.query.trim().toLowerCase();
                this.open = true;

                if (!q) {
                    this.results = [];
                    this.activeIndex = -1;
                    return;
                }

                this.loading = true;
                setTimeout(() => {
                    this.results = this.items
                        .filter(i =>
                            i.title.toLowerCase().includes(q) ||
                            i.subtitle.toLowerCase().includes(q) ||
                            i.meta.toLowerCase().includes(q)
                        )
                        .slice(0, 7);

                    this.activeIndex = this.results.length ? 0 : -1;
                    this.loading = false;

                    this.$nextTick(() => window.lucide?.createIcons());
                }, 180);
            },

            move(step) {
                if (!this.results.length) return;
                const next = this.activeIndex + step;
                if (next < 0) this.activeIndex = this.results.length - 1;
                else if (next >= this.results.length) this.activeIndex = 0;
                else this.activeIndex = next;
            },

            pickActive() {
                if (this.activeIndex < 0 || !this.results[this.activeIndex]) return;
                this.select(this.results[this.activeIndex]);
            },

            select(item) {
                this.query = item.title;
                this.close();
            },

            viewAll() {
                this.close();
                alert("View all results (frontend-only)");
            },

            close() {
                this.open = false;
                this.loading = false;
                this.activeIndex = -1;
            }
        };
    }

    // NOTIFICATIONS
    function notificationBell() {
        return {
            open: false,
            notifications: [],
            unreadCount: 0,

            init() {
                const stored = localStorage.getItem("worksite_notifications");
                if (stored) {
                    this.notifications = JSON.parse(stored);
                } else {
                    this.notifications = [
                        { id: "n1", title: "Interview Scheduled", body: "TechFlow scheduled an interview for your Senior Product Designer application.", time: "2 hours ago", icon: "calendar-check", iconBg: "bg-emerald-50 border-emerald-100", iconColor: "text-emerald-600", read: false },
                        { id: "n2", title: "Application Viewed", body: "Creative Studio viewed your application for UX Researcher.", time: "5 hours ago", icon: "eye", iconBg: "bg-blue-50 border-blue-100", iconColor: "text-blue-600", read: false },
                        { id: "n3", title: "New Job Alert", body: "3 new jobs match your 'Remote Designer' alert.", time: "1 day ago", icon: "bell", iconBg: "bg-amber-50 border-amber-100", iconColor: "text-amber-600", read: true },
                    ];
                    localStorage.setItem("worksite_notifications", JSON.stringify(this.notifications));
                }

                this.recount();
                this.$nextTick(() => window.lucide?.createIcons());
            },

            toggle() {
                this.open = !this.open;

                // requirement: opening bell = remove green dot
                if (this.open) this.markAllRead();

                this.$nextTick(() => window.lucide?.createIcons());
            },

            recount() {
                this.unreadCount = this.notifications.filter(n => !n.read).length;
            },

            persist() {
                localStorage.setItem("worksite_notifications", JSON.stringify(this.notifications));
            },

            markAllRead() {
                this.notifications = this.notifications.map(n => ({ ...n, read: true }));
                this.persist();
                this.recount();
            },

            openNotification(n) {
                n.read = true;
                this.persist();
                this.recount();
                alert(n.title + " (frontend-only)");
            }
        };
    }

    document.addEventListener("DOMContentLoaded", () => window.lucide?.createIcons());
</script>