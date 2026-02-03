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

  // FRONTEND-ONLY demo alerts (replace later with real DB counts)
  $alerts = [
    [
      'label' => 'Jobs pending review',
      'value' => 28,
      'href'  => route('admin.jobs'),
      'tone'  => 'warn', // warn | bad | good
    ],
    [
      'label' => 'Employer approvals',
      'value' => 6,
      'href'  => route('admin.users'),
      'tone'  => 'warn',
    ],
    [
      'label' => 'Payments to verify',
      'value' => 4,
      'href'  => route('admin.billing'),
      'tone'  => 'bad',
    ],
    [
      'label' => 'Expired subscriptions',
      'value' => 14,
      'href'  => route('admin.billing'),
      'tone'  => 'bad',
    ],
  ];

  $badge = function(string $tone): string {
    return match($tone){
      'good' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
      'warn' => 'bg-amber-50 text-amber-800 ring-amber-200',
      'bad'  => 'bg-rose-50 text-rose-700 ring-rose-200',
      default=> 'bg-slate-100 text-slate-700 ring-slate-200',
    };
  };
@endphp

{{-- MOBILE: Backdrop --}}
<div
  x-show="sidebarOpen"
  x-transition.opacity
  class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"
  @click="sidebarOpen = false"
  aria-hidden="true"
></div>

{{-- MOBILE: Drawer --}}
<aside
  x-show="sidebarOpen"
  x-transition:enter="transition ease-out duration-200"
  x-transition:enter-start="-translate-x-full"
  x-transition:enter-end="translate-x-0"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="translate-x-0"
  x-transition:leave-end="-translate-x-full"
  class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-xl lg:hidden"
  @keydown.escape.window="sidebarOpen = false"
  role="dialog"
  aria-modal="true"
>
  <div class="flex h-full flex-col">

    {{-- Drawer header --}}
    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
      <img
        src="{{ asset('images/logo.png') }}"
        alt="WorkSITE"
        class="h-10 object-contain"
      />
      <button
        type="button"
        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50"
        @click="sidebarOpen = false"
      >
        Close
      </button>
    </div>

    {{-- Drawer nav --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4">
      <div class="space-y-1">
        @foreach($items as $it)
          @php $active = str_starts_with($current, $it['href']); @endphp
          <a
            href="{{ $it['href'] }}"
            @click="sidebarOpen = false"
            class="block rounded-xl px-4 py-3 text-sm font-semibold
            {{ $active
                ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100'
                : 'text-slate-700 hover:bg-slate-50' }}"
          >
            {{ $it['label'] }}
          </a>
        @endforeach
      </div>

      <div class="my-5 border-t border-slate-200"></div>

      {{-- Logout (mobile only, INSIDE drawer) --}}
      <a
        href="{{ route('admin.adminlogin') }}"
        class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
      >
        Logout
      </a>
    </nav>

    {{-- Drawer footer: ADMIN ALERTS (replaces System Status) --}}
    <div class="px-5 py-4">
      <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <div class="flex items-center justify-between">
          <p class="text-xs font-semibold text-slate-700">Admin Alerts</p>
          <a href="{{ route('admin.reports') }}" class="text-[11px] font-semibold text-emerald-700 hover:underline">
            View
          </a>
        </div>

        <div class="mt-3 space-y-2">
          @foreach($alerts as $a)
            <a href="{{ $a['href'] }}" class="flex items-center justify-between rounded-xl bg-white px-3 py-2 hover:bg-slate-50">
              <span class="text-xs font-semibold text-slate-700">{{ $a['label'] }}</span>
              <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 {{ $badge($a['tone']) }}">
                {{ $a['value'] }}
              </span>
            </a>
          @endforeach
        </div>

        <div class="mt-3 text-[11px] text-slate-500">
          Demo counts (frontend). Backend later.
        </div>
      </div>
    </div>

  </div>
</aside>

{{-- DESKTOP SIDEBAR --}}
<aside class="sticky top-0 hidden h-screen w-72 border-r border-slate-200 bg-white lg:flex lg:flex-col">

  {{-- LOGO --}}
  <div class="px-6 py-6">
    <img
      src="{{ asset('images/logo.png') }}"
      alt="WorkSITE"
      class="w-full max-h-36 object-contain"
    />
  </div>

  {{-- NAVIGATION --}}
  <nav class="px-3 flex-1 overflow-y-auto">
    <div class="space-y-1">
      @foreach($items as $it)
        @php $active = str_starts_with($current, $it['href']); @endphp

        <a
          href="{{ $it['href'] }}"
          class="block rounded-xl px-4 py-3 text-sm font-semibold
          {{ $active
              ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100'
              : 'text-slate-700 hover:bg-slate-50' }}"
        >
          {{ $it['label'] }}
        </a>
      @endforeach
    </div>
  </nav>

</aside>
