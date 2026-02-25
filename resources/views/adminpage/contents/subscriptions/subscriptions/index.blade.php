@extends('adminpage.layout')
@section('title','Employer Subscriptions')
@section('page_title','Employer Subscriptions')

@section('content')
@php
  $q = $q ?? request('q', '');
  $status = $status ?? request('status', '');
@endphp

<div class="space-y-6"
     x-data="{ suspendOpen:false, suspendUrl:'', reason:'' }"
     x-cloak
     @keydown.escape.window="suspendOpen=false">

  @include('adminpage.components.flash')

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="GET"
          action="{{ route('admin.subscriptions.index') }}"
          class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:flex-wrap">

        <div class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-96">
          <span class="text-slate-400">⌕</span>
          <input
            name="q"
            value="{{ $q }}"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
            placeholder="Search employer name/email..."
          />
        </div>

        <select
          name="status"
          class="w-full sm:w-56 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
        >
          <option value="" {{ ($status ?? '') === '' ? 'selected' : '' }}>All statuses</option>
          <option value="pending_activation" {{ ($status ?? '') === 'pending_activation' ? 'selected' : '' }}>Pending</option>
          <option value="active"             {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="suspended"          {{ ($status ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option>
          <option value="expired"            {{ ($status ?? '') === 'expired' ? 'selected' : '' }}>Expired</option>
        </select>

      </div>

      <div class="flex items-center gap-2">
        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Search
        </button>

        @if($q || ($status !== ''))
          <a
            href="{{ route('admin.subscriptions.index') }}"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
          >
            Clear
          </a>
        @endif
      </div>

    </form>
  </div>

  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-sm font-semibold text-slate-900">Employer Subscriptions</h2>
          <p class="mt-0.5 text-xs text-slate-500">
            Monitor subscription status and manage activation/suspension.
          </p>
        </div>
        <p class="text-xs text-slate-500">Total: {{ $subs->total() }}</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
          <tr>
            <th class="px-5 py-3">Employer</th>
            <th class="px-5 py-3">Plan</th>
            <th class="px-5 py-3">Status</th>
            <th class="px-5 py-3">Start</th>
            <th class="px-5 py-3">End</th>
            <th class="px-5 py-3 text-right">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-200">
          @forelse($subs as $s)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3">
                <div class="font-semibold text-slate-900">{{ $s->employer->name ?? '—' }}</div>
                <div class="text-xs text-slate-500">{{ $s->employer->email ?? '' }}</div>
              </td>

              <td class="px-5 py-3">
                <div class="font-semibold text-slate-900">{{ $s->plan->name ?? '—' }}</div>
                <div class="text-xs font-mono text-slate-500">{{ $s->plan->code ?? '' }}</div>
              </td>

              <td class="px-5 py-3">
                @php $st = $s->status ?? ''; @endphp

                @if($st === 'active')
                  <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                    Active
                  </span>
                @elseif($st === 'pending_activation')
                  <span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 border border-sky-200">
                    Pending
                  </span>
                @elseif($st === 'suspended')
                  <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800 border border-amber-200">
                    Suspended
                  </span>
                @elseif($st === 'expired')
                  <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-200">
                    Expired
                  </span>
                @else
                  <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                    {{ $st ?: '—' }}
                  </span>
                @endif
              </td>

              <td class="px-5 py-3 text-slate-600">
                {{ optional($s->starts_at)->format('M d, Y') ?? '—' }}
              </td>

              <td class="px-5 py-3 text-slate-600">
                {{ optional($s->ends_at)->format('M d, Y') ?? '—' }}
              </td>

              <td class="px-5 py-3">
                <div class="flex justify-end gap-2">

                  @if(($s->status ?? '') !== 'active')
                    <form method="POST" action="{{ route('admin.subscriptions.activate', $s) }}">
                      @csrf
                      <button type="submit"
                              class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                        Activate (30 days)
                      </button>
                    </form>
                  @endif

                  @if(($s->status ?? '') === 'active')
                    <button type="button"
                            class="rounded-xl border border-amber-200 bg-white px-3 py-2 text-xs font-semibold text-amber-800 hover:bg-amber-50"
                            @click="suspendOpen=true; suspendUrl='{{ route('admin.subscriptions.suspend', $s) }}'; reason=''">
                      Suspend
                    </button>
                  @endif

                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-10 text-center text-slate-500">
                No subscriptions found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-slate-200 px-5 py-4">
      {{ $subs->links() }}
    </div>
  </div>

  {{-- ✅ SUSPEND MODAL (matched with your Industries modal style) --}}
  <div x-show="suspendOpen" x-transition.opacity
       class="fixed inset-0 z-50 flex items-center justify-center p-4"
       @click.self="suspendOpen=false">

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative w-full max-w-md">
      <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
          <div class="text-base font-semibold text-slate-900">Suspend subscription</div>
          <p class="mt-1 text-sm text-slate-600">Reason is required.</p>
        </div>

        <form method="POST" :action="suspendUrl" class="p-6 space-y-3">
          @csrf

          <textarea name="reason" x-model="reason" rows="4" required
                    class="w-full rounded-xl border border-slate-200 bg-white p-3 text-sm
                           focus:outline-none focus:ring-2 focus:ring-amber-200"
                    placeholder="e.g., payment dispute / policy violation"></textarea>

          <div class="mt-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
            <button type="button"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    @click="suspendOpen=false">
              Cancel
            </button>

            <button type="submit"
                    class="rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-amber-700">
              Suspend
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>

</div>
@endsection