<header class="sticky top-0 z-30 border-b border-slate-200 bg-white">
  <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">

    {{-- Left section --}}
    <div class="flex items-center gap-3 min-w-0">

      {{-- Mobile menu --}}
      <button
        type="button"
        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 lg:hidden"
        @click="sidebarOpen = true"
        aria-label="Open menu"
      >
        ☰
      </button>

      <div class="min-w-0">
        <p class="text-xs text-slate-500">Administrator</p>
        <h1 class="truncate text-lg font-semibold tracking-tight text-slate-900">
          @yield('page_title', 'Dashboard')
        </h1>
      </div>
    </div>

    {{-- Right section --}}
    <div class="flex items-center gap-3">

      {{-- Search bar --}}
      <div class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 md:flex w-72">
        <span class="text-slate-400">⌕</span>
        <input
          class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
          placeholder="Search users, jobs, payments…"
          @keydown.enter.prevent="window.toast?.('info','Search is demo only')"
        />
      </div>

      {{-- Notifications --}}
      <button
        class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50"
        title="Notifications"
        type="button"
        @click="window.toast?.('info','No new notifications (demo)')"
      >
        🔔
      </button>

      {{-- Logout (desktop only) --}}
      <form method="POST" action="{{ route('admin.logout') }}" class="inline">
        @csrf
        <button
          type="submit"
          class="hidden lg:inline-flex rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
        >
          Logout
        </button>
      </form>

    </div>
  </div>
</header>
