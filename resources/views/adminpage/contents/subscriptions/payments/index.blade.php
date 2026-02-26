@extends('adminpage.layout')
@section('title', 'Payments')
@section('page_title', 'Payments')

@section('content')
@php
  $q = $q ?? request('q', '');
  $status = $status ?? request('status', '');
  $method = $method ?? request('method', '');
@endphp

<div class="space-y-6" x-data="{ failOpen:false, failUrl:'', failReason:'' }" x-cloak
     @keydown.escape.window="failOpen=false">

  @include('adminpage.components.flash')

  {{-- Filters --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="GET"
          action="{{ route('admin.subscriptions.payments.index') }}"
          class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

      <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">

        <div class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-96">
          <span class="text-slate-400">⌕</span>
          <input
            name="q"
            value="{{ $q }}"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
            placeholder="Search company name / email..."
          />
        </div>

        <select name="status"
          class="w-full sm:w-56 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
          <option value="">All statuses</option>
          <option value="pending" {{ $status==='pending'?'selected':'' }}>Pending</option>
          <option value="completed" {{ $status==='completed'?'selected':'' }}>Completed</option>
          <option value="failed" {{ $status==='failed'?'selected':'' }}>Failed</option>
        </select>

        <select name="method"
          class="w-full sm:w-56 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
          <option value="">All methods</option>
          <option value="gcash" {{ $method==='gcash'?'selected':'' }}>GCash</option>
          <option value="cash" {{ $method==='cash'?'selected':'' }}>Cash</option>
        </select>

      </div>

      <div class="flex items-center gap-2">
        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Search
        </button>

        @if($q || $status || $method)
          <a href="{{ route('admin.subscriptions.payments.index') }}"
             class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Clear
          </a>
        @endif
      </div>

    </form>
  </div>

  {{-- Table --}}
  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-4 flex items-center justify-between">
      <div>
        <h2 class="text-sm font-semibold text-slate-900">Subscription Payments</h2>
        <p class="text-xs text-slate-500">Review and verify employer payments.</p>
      </div>
      <p class="text-xs text-slate-500">Total: {{ $payments->total() }}</p>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
          <tr>
            <th class="px-5 py-3">Company</th>
            <th class="px-5 py-3">Plan</th>
            <th class="px-5 py-3">Amount</th>
            <th class="px-5 py-3">Method</th>
            <th class="px-5 py-3">Status</th>
            <th class="px-5 py-3 text-right">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-200">
          @forelse($payments as $p)
            @php
              $company = $p->employer?->employerProfile?->company_name ?? '—';
              $email = $p->employer?->email ?? '';
            @endphp

            <tr class="hover:bg-slate-50">
              {{-- Company --}}
              <td class="px-5 py-3">
                <div class="font-semibold text-slate-900">{{ $company }}</div>
                <div class="text-xs text-slate-500">{{ $email }}</div>
              </td>

              {{-- Plan --}}
              <td class="px-5 py-3">
                <div class="font-semibold text-slate-900">{{ $p->plan?->name ?? '—' }}</div>
                <div class="text-xs font-mono text-slate-500">{{ $p->plan?->code ?? '' }}</div>
              </td>

              {{-- Amount --}}
              <td class="px-5 py-3 font-semibold text-slate-900">
                ₱{{ number_format((float)$p->amount, 2) }}
              </td>

              {{-- Method --}}
              <td class="px-5 py-3">
                @if($p->method === 'gcash')
                  <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                    GCash
                  </span>
                @else
                  <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 border border-slate-200">
                    Cash
                  </span>
                @endif
              </td>

              {{-- Status --}}
              <td class="px-5 py-3">
                @if($p->status === 'pending')
                  <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800 border border-amber-200">
                    Pending
                  </span>
                @elseif($p->status === 'completed')
                  <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                    Completed
                  </span>
                @else
                  <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-200">
                    Failed
                  </span>
                @endif
              </td>

              {{-- Actions --}}
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
                        Approve
                      </button>
                    </form>

                    <button type="button"
                      class="rounded-xl border border-rose-200 bg-white px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50"
                      @click="failOpen=true; failUrl='{{ route('admin.subscriptions.payments.fail', $p) }}'; failReason=''">
                      Reject
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

  {{-- FAIL MODAL --}}
  <div x-show="failOpen" x-transition.opacity
       class="fixed inset-0 z-50 flex items-center justify-center p-4"
       @click.self="failOpen=false">

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl p-6">
      <h3 class="text-sm font-semibold text-slate-900 mb-3">Reject Payment</h3>

      <form method="POST" :action="failUrl" class="space-y-3">
        @csrf
        <textarea name="fail_reason" x-model="failReason" required rows="3"
          class="w-full rounded-xl border border-slate-200 p-3 text-sm focus:ring-2 focus:ring-rose-200"
          placeholder="Reason for rejection"></textarea>

        <div class="flex justify-end gap-2">
          <button type="button"
            class="rounded-xl border px-4 py-2 text-sm"
            @click="failOpen=false">Cancel</button>

          <button type="submit"
            class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
            Confirm
          </button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection