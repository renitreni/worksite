@extends('adminpage.layout')
@section('title','Location Suggestions')
@section('page_title','Location Suggestions')

@section('content')
@php
  $q = $q ?? request('q', '');
  $status = $status ?? request('status', '');
@endphp

<div class="space-y-6">

  @include('adminpage.components.flash')

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="GET"
          action="{{ route('admin.location_suggestions.index') }}"
          class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:flex-wrap">

        <div class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-96">
          <span class="text-slate-400">⌕</span>
          <input
            name="q"
            value="{{ $q }}"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
            placeholder="Search country/city/area..."
          />
        </div>

        <select
          name="status"
          class="w-full sm:w-56 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
        >
          <option value="" {{ $status === '' ? 'selected' : '' }}>All statuses</option>
          <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
          <option value="ignored" {{ $status === 'ignored' ? 'selected' : '' }}>Ignored</option>
        </select>

      </div>

      <div class="flex items-center gap-2">
        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Search
        </button>

        @if($q || $status !== '')
          <a
            href="{{ route('admin.location_suggestions.index') }}"
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
        <h2 class="text-sm font-semibold text-slate-900">Suggestions</h2>
        <p class="text-xs text-slate-500">Total: {{ $suggestions->total() }}</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
          <tr>
            <th class="px-5 py-3">Country</th>
            <th class="px-5 py-3">City</th>
            <th class="px-5 py-3">Area</th>
            <th class="px-5 py-3">Count</th>
            <th class="px-5 py-3">Status</th>
            <th class="px-5 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse($suggestions as $s)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3 font-semibold text-slate-900">{{ $s->country }}</td>
              <td class="px-5 py-3 text-slate-700">{{ $s->city ?? '—' }}</td>
              <td class="px-5 py-3 text-slate-700">{{ $s->area ?? '—' }}</td>
              <td class="px-5 py-3 text-slate-700">{{ $s->count }}</td>

              <td class="px-5 py-3">
                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold
                  {{ $s->status === 'approved' ? 'bg-emerald-50 text-emerald-700' : '' }}
                  {{ $s->status === 'pending' ? 'bg-amber-50 text-amber-800' : '' }}
                  {{ $s->status === 'ignored' ? 'bg-slate-100 text-slate-600' : '' }}
                ">
                  {{ ucfirst($s->status) }}
                </span>
              </td>

              <td class="px-5 py-3">
                <div class="flex justify-end gap-2 flex-wrap">

                  @if($s->status === 'pending')
                    <form method="POST" action="{{ route('admin.location_suggestions.approve', $s) }}">
                      @csrf
                      @method('PATCH')
                      <button class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                        Approve → Add to Locations
                      </button>
                    </form>
                  @endif

                  <form method="POST" action="{{ route('admin.location_suggestions.update', $s) }}">
                    @csrf
                    @method('PUT')
                    <select name="status"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700"
                            onchange="this.form.submit()">
                      <option value="pending"  {{ $s->status === 'pending' ? 'selected' : '' }}>Pending</option>
                      <option value="approved" {{ $s->status === 'approved' ? 'selected' : '' }}>Approved</option>
                      <option value="ignored"  {{ $s->status === 'ignored' ? 'selected' : '' }}>Ignored</option>
                    </select>
                  </form>

                  <form method="POST"
                        action="{{ route('admin.location_suggestions.destroy', $s) }}"
                        onsubmit="return confirm('Delete this suggestion row?')">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                      Delete
                    </button>
                  </form>

                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-8 text-center text-slate-500">
                No suggestions found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-slate-200 px-5 py-4">
      {{ $suggestions->links() }}
    </div>
  </div>

</div>
@endsection