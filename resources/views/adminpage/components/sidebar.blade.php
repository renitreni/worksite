@php
  $items = [
    ['label'=>'Dashboard','href'=>route('admin.dashboard')],
    ['label'=>'Users','href'=>route('admin.users')],
    ['label'=>'Job Postings','href'=>route('admin.jobs')],
    ['label'=>'Categories / Skills / Locations','href'=>route('admin.taxonomy')],
    ['label'=>'Subscriptions & Payments','href'=>route('admin.billing')],
    ['label'=>'Reports','href'=>route('admin.reports')],
    ['label'=>'System Settings','href'=>route('admin.settings')],
  ];
  $current = url()->current();
@endphp

<aside class="sticky top-0 hidden h-screen w-72 border-r border-slate-200 bg-white lg:block">
  <div class="px-6 py-5">
    <div class="flex items-center gap-3">
      <div class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-600 text-white font-bold">WS</div>
      <div>
        <p class="text-sm font-semibold leading-tight">WorkSITE</p>
        <p class="text-xs text-slate-500">Administrator Panel</p>
      </div>
    </div>
  </div>

  <nav class="px-3">
    <div class="space-y-1">
      @foreach($items as $it)
        @php $active = str_starts_with($current, $it['href']); @endphp

        <a href="{{ $it['href'] }}"
           class="block rounded-xl px-4 py-3 text-sm font-semibold
           {{ $active ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'text-slate-700 hover:bg-slate-50' }}">
          {{ $it['label'] }}
        </a>
      @endforeach
    </div>

    <div class="my-5 border-t border-slate-200"></div>

    <a href="{{ route('admin.login') }}"
       class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
      Logout
    </a>
  </nav>

  <div class="mt-auto px-6 py-4">
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
      <p class="text-xs font-semibold text-slate-700">System Status</p>
      <div class="mt-2 space-y-1 text-xs text-slate-600">
        <div class="flex justify-between"><span>Uptime</span><span class="font-semibold text-slate-900">99.98%</span></div>
        <div class="flex justify-between"><span>Last sync</span><span class="font-semibold text-slate-900">2m ago</span></div>
      </div>
    </div>
  </div>
</aside>
