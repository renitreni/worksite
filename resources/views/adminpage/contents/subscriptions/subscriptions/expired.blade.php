@extends('adminpage.layout')
@section('title','Expired Subscriptions')
@section('page_title','Expired Subscriptions')

@section('content')
<div class="w-full max-w-7xl mx-auto space-y-6">

  {{-- Top bar --}}
  <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="px-6 sm:px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-white">
      <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">

        <div>
          <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Expired Subscriptions</h1>
          <p class="text-sm text-slate-600">
            These subscriptions have passed their end date.
          </p>
        </div>

        <form method="GET" action="{{ route('admin.subscriptions.expired') }}"
              class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">

          <input type="text"
                 name="q"
                 value="{{ $q ?? '' }}"
                 placeholder="Search employer name/email..."
                 class="h-11 w-full sm:w-80 rounded-xl border border-slate-200 bg-white px-4 text-sm
                        focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400">

          <button type="submit"
                  class="h-11 inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 text-sm font-semibold text-white hover:bg-slate-800">
            Search
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
            <th class="px-6 py-4">Ended</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-right">Reminder</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-100 bg-white text-sm">
          @forelse($subs as $s)
            <tr class="hover:bg-slate-50/70">

              {{-- Employer --}}
              <td class="px-6 py-4">
                <div class="font-semibold text-slate-900">
                  {{ $s->employer->name ?? '—' }}
                </div>
                <div class="text-xs text-slate-500">
                  {{ $s->employer->email ?? '' }}
                </div>
              </td>

              {{-- Plan --}}
              <td class="px-6 py-4">
                <div class="font-semibold text-slate-900">
                  {{ $s->plan->name ?? '—' }}
                </div>
                <div class="text-xs font-mono text-slate-500">
                  {{ $s->plan->code ?? '' }}
                </div>
              </td>

              {{-- End date --}}
              <td class="px-6 py-4 text-slate-600">
                {{ optional($s->ends_at)->format('M d, Y') ?? '—' }}
              </td>

              {{-- Status --}}
              <td class="px-6 py-4">
                @php $st = $s->subscription_status; @endphp

                @if($st === 'expired')
                  <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-200">
                    Expired
                  </span>
                @elseif($st === 'active')
                  <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                    Active
                  </span>
                @else
                  <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                    {{ ucfirst($st) }}
                  </span>
                @endif
              </td>

              {{-- Reminder --}}
              <td class="px-6 py-4">
                <div class="flex justify-end">
                  <form method="POST" action="{{ route('admin.subscriptions.remind', $s) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                      Send Reminder
                    </button>
                  </form>
                </div>
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-14 text-center text-slate-500">
                No expired subscriptions.
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

</div>
@endsection