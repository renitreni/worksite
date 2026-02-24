@extends('adminpage.layout')
@section('title','Payments')
@section('page_title','Payments')

@section('content')
<div class="space-y-6" x-data="{ failOpen:false, failUrl:'', failReason:'' }" x-cloak>

  <form method="GET" action="{{ route('admin.subscriptions.payments.index') }}"
        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
      <input type="text" name="q" value="{{ $q ?? '' }}"
             class="w-full sm:w-80 rounded-xl border border-slate-200 bg-white px-3 py-2"
             placeholder="Search employer name/email...">

      <select name="status" class="w-full sm:w-56 rounded-xl border border-slate-200 bg-white px-3 py-2">
        <option value="">All statuses</option>
        <option value="pending"   {{ ($status ?? '')==='pending' ? 'selected' : '' }}>pending</option>
        <option value="completed" {{ ($status ?? '')==='completed' ? 'selected' : '' }}>completed</option>
        <option value="failed"    {{ ($status ?? '')==='failed' ? 'selected' : '' }}>failed</option>
      </select>

      <button class="rounded-xl bg-slate-900 px-4 py-2 text-white">Filter</button>

      @if(($q ?? '') !== '' || ($status ?? '') !== '')
        <a href="{{ route('admin.subscriptions.payments.index') }}"
           class="rounded-xl border border-slate-200 px-4 py-2">Reset</a>
      @endif
    </div>
  </form>

  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
          <tr class="text-left text-sm font-semibold text-slate-700">
            <th class="px-4 py-3">Employer</th>
            <th class="px-4 py-3">Plan</th>
            <th class="px-4 py-3">Amount</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Created</th>
            <th class="px-4 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
          @forelse($payments as $p)
            <tr>
              <td class="px-4 py-3">
                <div class="font-medium text-slate-900">{{ $p->employer->name ?? '—' }}</div>
                <div class="text-xs text-slate-500">{{ $p->employer->email ?? '' }}</div>
              </td>
              <td class="px-4 py-3">
                <div class="font-medium text-slate-900">{{ $p->plan->name ?? '—' }}</div>
                <div class="text-xs font-mono text-slate-500">{{ $p->plan->code ?? '' }}</div>
              </td>
              <td class="px-4 py-3">₱{{ number_format((int)$p->amount) }}</td>
              <td class="px-4 py-3">
                @php $st = $p->status; @endphp
                @if($st === 'pending')
                  <span class="rounded-full bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-700">pending</span>
                @elseif($st === 'completed')
                  <span class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700">completed</span>
                @else
                  <span class="rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-700">failed</span>
                @endif
              </td>
              <td class="px-4 py-3 text-slate-600">{{ optional($p->created_at)->format('Y-m-d H:i') }}</td>
              <td class="px-4 py-3">
                <div class="flex justify-end gap-2">
                  <a href="{{ route('admin.subscriptions.payments.show', $p) }}"
                     class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-50">View</a>

                  @if($p->status === 'pending')
                    <form method="POST" action="{{ route('admin.subscriptions.payments.complete', $p) }}">
                      @csrf
                      <button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-white">Mark Completed</button>
                    </form>

                    <button class="rounded-lg border border-red-200 px-3 py-1.5 text-red-700 hover:bg-red-50"
                      @click="failOpen=true; failUrl='{{ route('admin.subscriptions.payments.fail', $p) }}'; failReason=''">
                      Mark Failed
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No payments found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-4">{{ $payments->links() }}</div>
  </div>

  {{-- Fail modal --}}
  <div x-show="failOpen" class="fixed inset-0 z-50 grid place-items-center bg-black/40 p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-5 shadow-lg">
      <h3 class="text-lg font-semibold text-slate-900">Mark payment as failed</h3>
      <p class="mt-2 text-sm text-slate-600">Add a short reason (required).</p>

      <form method="POST" :action="failUrl" class="mt-4 space-y-3">
        @csrf
        <textarea name="fail_reason" x-model="failReason"
                  class="w-full rounded-xl border border-slate-200 p-3"
                  rows="4" required placeholder="e.g., invalid proof / payment not received"></textarea>

        <div class="flex justify-end gap-2">
          <button type="button" class="rounded-xl border border-slate-200 px-4 py-2" @click="failOpen=false">Cancel</button>
          <button class="rounded-xl bg-red-600 px-4 py-2 text-white">Confirm</button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection