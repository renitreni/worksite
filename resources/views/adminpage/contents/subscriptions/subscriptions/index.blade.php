@extends('adminpage.layout')
@section('title','Employer Subscriptions')
@section('page_title','Employer Subscriptions')

@section('content')
<div class="w-full max-w-7xl mx-auto space-y-6"
     x-data="{ suspendOpen:false, suspendUrl:'', reason:'' }"
     x-cloak
     @keydown.escape.window="suspendOpen=false">

  {{-- Top bar / Filters --}}
  <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
      <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
          <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Employer Subscriptions</h1>
          <p class="mt-1 text-sm text-slate-600">Monitor subscription status and manage activation/suspension.</p>
        </div>

        <form method="GET" action="{{ route('admin.subscriptions.index') }}"
              class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">

          <input type="text" name="q" value="{{ $q ?? '' }}"
                 class="w-full sm:w-80 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                        focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400"
                 placeholder="Search employer name/email...">

          <select name="status"
                  class="w-full sm:w-56 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm
                         focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400">
            <option value="">All statuses</option>
            <option value="pending_activation" {{ ($status ?? '')==='pending_activation' ? 'selected' : '' }}>pending_activation</option>
            <option value="active"             {{ ($status ?? '')==='active' ? 'selected' : '' }}>active</option>
            <option value="suspended"          {{ ($status ?? '')==='suspended' ? 'selected' : '' }}>suspended</option>
            <option value="expired"            {{ ($status ?? '')==='expired' ? 'selected' : '' }}>expired</option>
          </select>

          <button type="submit"
                  class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
            Filter
          </button>

  
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
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Start</th>
            <th class="px-6 py-4">End</th>
            <th class="px-6 py-4 text-right">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-100 bg-white text-sm">
          @forelse($subs as $s)
            <tr class="hover:bg-slate-50/70">
              <td class="px-6 py-4">
                <div class="font-semibold text-slate-900">{{ $s->employer->name ?? '—' }}</div>
                <div class="text-xs text-slate-500">{{ $s->employer->email ?? '' }}</div>
              </td>

              <td class="px-6 py-4">
                <div class="font-semibold text-slate-900">{{ $s->plan->name ?? '—' }}</div>
                <div class="text-xs font-mono text-slate-500">{{ $s->plan->code ?? '' }}</div>
              </td>

              <td class="px-6 py-4">
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

              <td class="px-6 py-4 text-slate-600">
                {{ optional($s->starts_at)->format('M d, Y') ?? '—' }}
              </td>

              <td class="px-6 py-4 text-slate-600">
                {{ optional($s->ends_at)->format('M d, Y') ?? '—' }}
              </td>

              <td class="px-6 py-4">
                <div class="flex justify-end gap-2">
                  @if(($s->status ?? '') !== 'active')
                    <form method="POST" action="{{ route('admin.subscriptions.activate', $s) }}">
                      @csrf
                      <button type="submit"
                              class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                        Activate (30 days)
                      </button>
                    </form>
                  @endif

                  @if(($s->status ?? '') === 'active')
                    <button type="button"
                            class="inline-flex items-center justify-center rounded-xl border border-amber-200 px-3 py-2 text-xs font-semibold text-amber-800 hover:bg-amber-50"
                            @click="suspendOpen=true; suspendUrl='{{ route('admin.subscriptions.suspend', $s) }}'; reason=''">
                      Suspend
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                No subscriptions found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="px-6 py-4 border-t border-slate-200 bg-white">
      {{ $subs->links() }}
    </div>
  </div>

  {{-- Suspend modal --}}
  <div x-show="suspendOpen"
       x-transition.opacity
       class="fixed inset-0 z-50 grid place-items-center bg-black/40 p-4"
       @click.self="suspendOpen=false">
    <div class="w-full max-w-md rounded-3xl bg-white shadow-xl border border-slate-200 overflow-hidden">
      <div class="p-5 sm:p-6">
        <div class="text-lg font-semibold text-slate-900">Suspend subscription</div>
        <p class="mt-2 text-sm text-slate-600">Reason is required.</p>

        <form method="POST" :action="suspendUrl" class="mt-4 space-y-3">
          @csrf

          <textarea name="reason" x-model="reason" rows="4" required
                    class="w-full rounded-xl border border-slate-200 bg-white p-3 text-sm
                           focus:outline-none focus:ring-2 focus:ring-amber-500/25 focus:border-amber-300"
                    placeholder="e.g., payment dispute / policy violation"></textarea>

          <div class="mt-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
            <button type="button"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    @click="suspendOpen=false">
              Cancel
            </button>

            <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-amber-700">
              Suspend
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
@endsection