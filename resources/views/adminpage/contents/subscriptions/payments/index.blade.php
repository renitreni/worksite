@extends('adminpage.layout')
@section('title', 'Payments')
@section('page_title', 'Payments')

@section('content')
  <div class="w-full max-w-7xl mx-auto space-y-6" x-data="{ failOpen:false, failUrl:'', failReason:'' }" x-cloak
    @keydown.escape.window="failOpen=false">

    {{-- Top bar / Filters --}}
    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
      <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">

          {{-- Left description (layout already prints title) --}}
          <div class="min-w-0">
            <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Subscription Payments</h1>
            <p class="text-sm text-slate-600">
              Review payment submissions and update statuses.
            </p>
          </div>

          {{-- Right filters --}}
          <form method="GET" action="{{ route('admin.subscriptions.payments.index') }}" class="w-full lg:w-auto">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full">

              <input type="text" name="q" value="{{ $q ?? '' }}" class="h-11 w-full sm:w-80 rounded-xl border border-slate-200 bg-white px-4 text-sm
                            focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                placeholder="Search employer name/email...">

              <select name="status" class="h-11 w-full sm:w-56 rounded-xl border border-slate-200 bg-white px-3 text-sm
                             focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400">
                <option value="">All statuses</option>
                <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>pending</option>
                <option value="completed" {{ ($status ?? '') === 'completed' ? 'selected' : '' }}>completed</option>
                <option value="failed" {{ ($status ?? '') === 'failed' ? 'selected' : '' }}>failed</option>
              </select>

              <button type="submit"
                class="h-11 inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 text-sm font-semibold text-white hover:bg-slate-800">
                Filter
              </button>

              @if(($q ?? '') !== '' || ($status ?? '') !== '')
                <a href="{{ route('admin.subscriptions.payments.index') }}"
                  class="h-11 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                  Reset
                </a>
              @endif

            </div>
          </form>

        </div>
      </div>

      {{-- Table --}}
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
          <thead class="bg-slate-50">
            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
              <th class="px-6 py-4">Employer</th>
              <th class="px-6 py-4">Plan</th>
              <th class="px-6 py-4">Amount</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4">Created</th>
              <th class="px-6 py-4 text-right">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-100 bg-white text-sm">
            @forelse($payments as $p)
              <tr class="hover:bg-slate-50/70">
                <td class="px-6 py-4">
                  <div class="font-semibold text-slate-900">{{ $p->employer->name ?? '—' }}</div>
                  <div class="text-xs text-slate-500">{{ $p->employer->email ?? '' }}</div>
                </td>

                <td class="px-6 py-4">
                  <div class="font-semibold text-slate-900">{{ $p->plan->name ?? '—' }}</div>
                  <div class="text-xs font-mono text-slate-500">{{ $p->plan->code ?? '' }}</div>
                </td>

                <td class="px-6 py-4 text-slate-700">
                  ₱{{ number_format((int) $p->amount) }}
                </td>

                <td class="px-6 py-4">
                  @php $st = $p->status; @endphp
                  @if($st === 'pending')
                    <span
                      class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800 border border-amber-200">
                      Pending
                    </span>
                  @elseif($st === 'completed')
                    <span
                      class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                      Completed
                    </span>
                  @else
                    <span
                      class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-200">
                      Failed
                    </span>
                  @endif
                </td>

                <td class="px-6 py-4 text-slate-600">
                  {{ optional($p->created_at)->format('M d, Y • h:i A') }}
                </td>

                <td class="px-6 py-4">
                  <div class="flex justify-end gap-2">

                    <a href="{{ route('admin.subscriptions.payments.show', $p) }}"
                      class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                      View
                    </a>

                    @if($p->status === 'pending')
                      <form method="POST" action="{{ route('admin.subscriptions.payments.complete', $p) }}">
                        @csrf
                        <button type="submit"
                          class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                          Mark Completed
                        </button>
                      </form>

                      <button type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50"
                        @click="failOpen=true; failUrl='{{ route('admin.subscriptions.payments.fail', $p) }}'; failReason=''">
                        Mark Failed
                      </button>
                    @endif

                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-14 text-center text-slate-500">
                  No payments found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      <div class="px-6 py-4 border-t border-slate-200 bg-white">
        {{ $payments->links() }}
      </div>
    </div>

    {{-- Fail modal --}}
    <div x-show="failOpen" x-transition.opacity class="fixed inset-0 z-50 grid place-items-center bg-black/40 p-4"
      @click.self="failOpen=false">
      <div class="w-full max-w-md rounded-3xl bg-white shadow-xl border border-slate-200 overflow-hidden">
        <div class="p-5 sm:p-6">
          <div class="text-lg font-semibold text-slate-900">Mark payment as failed</div>
          <p class="mt-2 text-sm text-slate-600">Add a short reason (required).</p>

          <form method="POST" :action="failUrl" class="mt-4 space-y-3">
            @csrf

            <textarea name="fail_reason" x-model="failReason" rows="4" required class="w-full rounded-xl border border-slate-200 bg-white p-3 text-sm
                             focus:outline-none focus:ring-2 focus:ring-rose-500/25 focus:border-rose-300"
              placeholder="e.g., invalid proof / payment not received"></textarea>

            <div class="mt-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
              <button type="button"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                @click="failOpen=false">
                Cancel
              </button>

              <button type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700">
                Confirm
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
@endsection