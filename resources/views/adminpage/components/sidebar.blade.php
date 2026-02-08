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

  $alerts = [
    [
      'label' => 'Jobs pending review',
      'value' => 28,
      'href'  => route('admin.jobs'),
      'tone'  => 'warn',
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

{{-- Mobile sidebar --}}
<div
  x-show="sidebarOpen"
  x-transition.opacity
  class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"
  @click="sidebarOpen = false"
  aria-hidden="true"
></div>

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

      <a
        href="{{ route('admin.adminlogin') }}"
        class="block rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
      >
        Logout
      </a>
    </nav>

   

  </div>
</aside>

{{-- Desktop sidebar --}}
<aside class="sticky top-0 hidden h-screen w-72 border-r border-slate-200 bg-white lg:flex lg:flex-col">
  <div class="px-6 py-6">
    <img
      src="{{ asset('images/logo.png') }}"
      alt="WorkSITE"
      class="w-full max-h-36 object-contain"
    />
  </div>

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
