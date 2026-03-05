<div wire:ignore x-cloak x-data="notificationBell()" x-init="init()" class="relative">
    <!-- 🔔 Bell -->
    <button @click="open = !open" :class="animate ? 'animate-bounce' : ''"
        class="relative inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition">

        <i data-lucide="bell" class="h-5 w-5 text-gray-600"></i>

        <!-- Unread Count -->
        <span x-show="unread > 0" x-transition
            class="absolute -top-2 -right-2 min-w-[18px] px-1.5 h-[18px] flex items-center justify-center text-[10px] font-bold text-white bg-emerald-600 rounded-full ring-2 ring-white">
            <span x-text="unread > 99 ? '99+' : unread"></span>
        </span>
    </button>

    <!-- Mobile backdrop -->
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/40 z-40 sm:hidden" @click="open = false">
    </div>

    <!-- Dropdown -->
    <div x-cloak x-show="open === true" x-transition @click.outside="open = false"
        class="fixed sm:absolute
            top-16 left-0 right-0 sm:top-auto sm:left-auto
            sm:right-0 sm:mt-2
            w-full sm:w-[400px]
            max-h-[80vh] sm:max-h-[480px]
            rounded-2xl border border-gray-200 bg-white shadow-2xl z-50
            flex flex-col overflow-hidden">

        <!-- Header -->
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <div>
                <p class="text-sm font-semibold text-gray-900">Notifications</p>
                <p class="text-xs text-gray-500">
                    You have
                    <span class="font-semibold text-emerald-600" x-text="unread"></span>
                    unread
                </p>
            </div>

            <button x-show="unread > 0" @click="markAllRead()"
                class="text-xs font-semibold text-emerald-600 hover:underline">
                Mark all
            </button>
        </div>

        <!-- List -->
        <div class="overflow-y-auto flex-1 divide-y divide-gray-100">

            <template x-for="n in notifications" :key="n.id">
                <div @click="markSingleRead(n)" class="px-5 py-4 hover:bg-gray-50 transition flex gap-3 cursor-pointer">

                    <div class="flex-1 min-w-0">

                        <!-- TITLE WITH COLOR -->
                        <p class="text-sm font-bold"
                            :class="{
                                'text-emerald-600': n.status === 'hired',
                                'text-blue-600': n.status === 'shortlisted',
                                'text-amber-600': n.status === 'interview',
                                'text-rose-600': n.status === 'rejected',
                                'text-gray-900': !n.status
                            }"
                            x-text="n.title">
                        </p>

                        <p class="text-xs text-gray-500 mt-1" x-text="n.body">
                        </p>

                        <span class="text-[11px] text-gray-400 mt-1 block" x-text="n.time">
                        </span>
                    </div>

                    <!-- Unread dot -->
                    <div class="flex items-start pt-1">
                        <span x-show="!n.read" class="h-2.5 w-2.5 bg-emerald-500 rounded-full">
                        </span>
                    </div>

                </div>
            </template>

            <div x-show="notifications.length === 0" class="p-6 text-sm text-gray-500 text-center">
                No notifications yet.
            </div>

        </div>

        <!-- Footer -->
        <div class="p-3 border-t border-gray-100 text-center bg-gray-50">
            <a href="{{ url('/all-notifications') }}" class="text-sm font-semibold text-emerald-600 hover:underline">
                View All Notifications
            </a>
        </div>

    </div>
</div>
