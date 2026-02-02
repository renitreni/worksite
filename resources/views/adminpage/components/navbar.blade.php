<header class="border-b border-slate-200 bg-white">
  <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
    <div>
      <p class="text-xs text-slate-500">Administrator</p>
      <h1 class="text-lg font-semibold tracking-tight">@yield('page_title', 'Dashboard')</h1>
    </div>

    <div class="flex items-center gap-3">
      <div class="hidden w-90 items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 md:flex">
        <span class="text-slate-400">⌕</span>
        <input class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
               placeholder="Search users, jobs, payments…" />
      </div>

      <button class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50" title="Notifications">🔔</button>

      <a href="{{ route('admin.login') }}"
         class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
        Logout
      </a>
    </div>
  </div>
</header>
