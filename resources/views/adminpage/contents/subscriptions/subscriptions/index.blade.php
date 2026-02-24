@extends('adminpage.layout')
@section('title','Employer Subscriptions')
@section('page_title','Employer Subscriptions')

@section('content')
<div class="space-y-6" x-data="{ suspendOpen:false, suspendUrl:'', reason:'' }" x-cloak>

  <form method="GET" action="{{ route('admin.subscriptions.index') }}"
        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
      <input type="text" name="q" value="{{ $q ?? '' }}"
             class="w-full sm:w-80 rounded-xl border border-slate-200 bg-white px-3 py-2"
             placeholder="Search employer name/email...">

      <select name="status" class="w-full sm:w-56 rounded-xl border border-slate-200 bg-white px-3 py-2">
        <option value="">All statuses</option>
        <option value="pending_activation" {{ ($status ?? '')==='pending_activation' ? 'selected' : '' }}>pending_activation</option>
        <option value="active"             {{ ($status ?? '')==='active' ? 'selected' : '' }}>active</option>
        <option value="suspended"          {{ ($status ?? '')==='suspended' ? 'selected' : '' }}>suspended</option>
        <option value="expired"            {{ ($status ?? '')==='expired' ? 'selected' : '' }}>expired</option>
      </select>

      <button class="rounded-xl bg-slate-900 px-4 py-2 text-white">Filter</button>

      <a href="{{ route('admin.subscriptions.expired') }}"
         class="rounded-xl border border-slate-200 px-4 py-2">View Expired</a>
    </div>
  </form>

  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
          <tr class="text-left text-sm font-semibold text-slate-700">
            <th class="px-4 py-3">Employer</th>
            <th class="px-4 py-3">Plan</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Start</th>
            <th class="px-4 py-3">End</th>
            <th class="px-4 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
          @forelse($subs as $s)
            <tr>
              <td class="px-4 py-3">
                <div class="font-medium text-slate-900">{{ $s->employer->name ?? '—' }}</div>
                <div class="text-xs text-slate-500">{{ $s->employer->email ?? '' }}</div>
              </td>
              <td class="px-4 py-3">
                <div class="font-medium text-slate-900">{{ $s->plan->name ?? '—' }}</div>
                <div class="text-xs font-mono text-slate-500">{{ $s->plan->code ?? '' }}</div>
              </td>
              <td class="px-4 py-3">{{ $s->status }}</td>
              <td class="px-4 py-3 text-slate-600">{{ optional($s->starts_at)->format('Y-m-d') ?? '—' }}</td>
              <td class="px-4 py-3 text-slate-600">{{ optional($s->ends_at)->format('Y-m-d') ?? '—' }}</td>
              <td class="px-4 py-3">
                <div class="flex justify-end gap-2">
                  @if($s->status !== 'active')
                    <form method="POST" action="{{ route('admin.subscriptions.activate', $s) }}">
                      @csrf
                      <button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-white">Activate (30 days)</button>
                    </form>
                  @endif

                  @if($s->status === 'active')
                    <button class="rounded-lg border border-amber-200 px-3 py-1.5 text-amber-800 hover:bg-amber-50"
                      @click="suspendOpen=true; suspendUrl='{{ route('admin.subscriptions.suspend', $s) }}'; reason=''">
                      Suspend
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No subscriptions found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-4">{{ $subs->links() }}</div>
  </div>

  {{-- Suspend modal --}}
  <div x-show="suspendOpen" class="fixed inset-0 z-50 grid place-items-center bg-black/40 p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-5 shadow-lg">
      <h3 class="text-lg font-semibold text-slate-900">Suspend subscription</h3>
      <p class="mt-2 text-sm text-slate-600">Reason is required.</p>

      <form method="POST" :action="suspendUrl" class="mt-4 space-y-3">
        @csrf
        <textarea name="reason" x-model="reason"
                  class="w-full rounded-xl border border-slate-200 p-3"
                  rows="4" required placeholder="e.g., payment dispute / policy violation"></textarea>

        <div class="flex justify-end gap-2">
          <button type="button" class="rounded-xl border border-slate-200 px-4 py-2" @click="suspendOpen=false">Cancel</button>
          <button class="rounded-xl bg-amber-600 px-4 py-2 text-white">Suspend</button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection