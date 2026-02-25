@extends('adminpage.layout')
@section('title', 'Payments')
@section('page_title', 'Payments')

@section('content')
@php
  $q = $q ?? request('q', '');
  $status = $status ?? request('status', '');
@endphp

<div class="space-y-6" x-data="{ failOpen:false, failUrl:'', failReason:'' }" x-cloak
     @keydown.escape.window="failOpen=false">

  @include('adminpage.components.flash')

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="GET"
          action="{{ route('admin.subscriptions.payments.index') }}"
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
          <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="completed" {{ ($status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
          <option value="failed" {{ ($status ?? '') === 'failed' ? 'selected' : '' }}>Failed</option>
        </select>

      </div>

      <div class="flex items-center gap-2">
        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Search
        </button>

        @if(($q ?? '') !== '' || ($status ?? '') !== '')
          <a
            href="{{ route('admin.subscriptions.payments.index') }}"
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
          <h2 class="text-sm font-semibold text-slate-900">Subscription Payments</h2>
          <p class="mt-0.5 text-xs text-slate-500">Review payment submissions and update statuses.</p>
        </div>
        <p class="text-xs text-slate-500">Total: {{ $payments->total() }}</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
          <tr>
            <th class="px-5 py-3">Employer</th>
            <th class="px-5 py-3">Plan</th>
            <th class="px-5 py-3">Amount</th>
            <th class="px-5 py-3">Status</th>
            <th class="px-5 py-3">Created</th>
            <th class="px-5 py-3 text-right">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-200">
          @forelse($payments as $p)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3">
                <div class="font-semibold text-slate-900">
                  {{ $p->employer->name ?? '—' }}
                </div>
                <div class="text-xs text-slate-500">
                  {{ $p->employer->email ?? '' }}
                </div>
              </td>

              <td class="px-5 py-3">
                <div class="font-semibold text-slate-900">
                  {{ $p->plan->name ?? '—' }}
                </div>
                <div class="text-xs font-mono text-slate-500">
                  {{ $p->plan->code ?? '' }}
                </div>
              </td>

              <td class="px-5 py-3 text-slate-700">
                ₱{{ number_format((int) $p->amount) }}
              </td>

              <td class="px-5 py-3">
                @php $st = $p->status; @endphp

                @if($st === 'pending')
                  <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800 border border-amber-200">
                    Pending
                  </span>
                @elseif($st === 'completed')
                  <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                    Completed
                  </span>
                @else
                  <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-200">
                    Failed
                  </span>
                @endif
              </td>

              <td class="px-5 py-3 text-slate-600">
                {{ optional($p->created_at)->format('M d, Y • h:i A') }}
              </td>

              <td class="px-5 py-3">
                <div class="flex justify-end gap-2">

                  <a href="{{ route('admin.subscriptions.payments.show', $p) }}"
                     class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    View
                  </a>

                  @if($p->status === 'pending')
                    <form method="POST" action="{{ route('admin.subscriptions.payments.complete', $p) }}">
                      @csrf
                      <button type="submit"
                        class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                        Mark Completed
                      </button>
                    </form>

                    <button type="button"
                      class="rounded-xl border border-rose-200 bg-white px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50"
                      @click="failOpen=true; failUrl='{{ route('admin.subscriptions.payments.fail', $p) }}'; failReason=''">
                      Mark Failed
                    </button>
                  @endif

                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-10 text-center text-slate-500">
                No payments found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-slate-200 px-5 py-4">
      {{ $payments->links() }}
    </div>
  </div>

  {{-- ✅ FAIL MODAL (match your modern style) --}}
  <div x-show="failOpen" x-transition.opacity
       class="fixed inset-0 z-50 flex items-center justify-center p-4"
       @click.self="failOpen=false">

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative w-full max-w-md">
      <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
          <div class="text-base font-semibold text-slate-900">Mark payment as failed</div>
          <p class="mt-1 text-sm text-slate-600">Add a short reason (required).</p>
        </div>

        <form method="POST" :action="failUrl" class="p-6 space-y-3">
          @csrf

          <textarea name="fail_reason" x-model="failReason" rows="4" required
            class="w-full rounded-xl border border-slate-200 bg-white p-3 text-sm
                   focus:outline-none focus:ring-2 focus:ring-rose-200"
            placeholder="e.g., invalid proof / payment not received"></textarea>

          <div class="mt-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
            <button type="button"
              class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
              @click="failOpen=false">
              Cancel
            </button>

            <button type="submit"
              class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700">
              Confirm
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>

</div>
@endsection