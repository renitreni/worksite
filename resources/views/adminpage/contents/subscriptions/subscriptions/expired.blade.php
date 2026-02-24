@extends('adminpage.layout')
@section('title','Expired Subscriptions')
@section('page_title','Expired Subscriptions')

@section('content')
<div class="space-y-6">

  <form method="GET" action="{{ route('admin.subscriptions.expired') }}"
        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex gap-2 w-full sm:w-auto">
      <input type="text" name="q" value="{{ $q ?? '' }}"
             class="w-full sm:w-80 rounded-xl border border-slate-200 bg-white px-3 py-2"
             placeholder="Search employer name/email...">
      <button class="rounded-xl bg-slate-900 px-4 py-2 text-white">Search</button>
    </div>

    <a href="{{ route('admin.subscriptions.index') }}"
       class="rounded-xl border border-slate-200 px-4 py-2">Back to all</a>
  </form>

  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
          <tr class="text-left text-sm font-semibold text-slate-700">
            <th class="px-4 py-3">Employer</th>
            <th class="px-4 py-3">Plan</th>
            <th class="px-4 py-3">Ended</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3 text-right">Reminder</th>
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
              <td class="px-4 py-3 text-slate-600">{{ optional($s->ends_at)->format('Y-m-d') ?? '—' }}</td>
              <td class="px-4 py-3">{{ $s->status }}</td>
              <td class="px-4 py-3">
                <div class="flex justify-end">
                  <form method="POST" action="{{ route('admin.subscriptions.remind', $s) }}">
                    @csrf
                    <button class="rounded-lg border border-slate-200 px-3 py-1.5 hover:bg-slate-50">
                      Send reminder (stub)
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">No expired subscriptions.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="p-4">{{ $subs->links() }}</div>
  </div>

</div>
@endsection