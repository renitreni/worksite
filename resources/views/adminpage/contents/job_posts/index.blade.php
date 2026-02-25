@extends('adminpage.layout')
@section('title','Skills')
@section('page_title','Manage Jobs')

@section('content')
@php
  // If your layout reads a $pageTitle variable, set it:
  $pageTitle = 'Job Postings';
@endphp

<div class="space-y-6">

  

  @if (session('success'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
      {{ session('success') }}
    </div>
  @endif

  {{-- Filters card (match Users layout) --}}
  <div class="rounded-3xl bg-white border border-slate-200 shadow-sm">
    <form method="GET" action="{{ route('admin.job-posts.index') }}" class="p-5 space-y-3">

      {{-- Search bar (full width like Users) --}}
      {{-- Search --}}
          <div class="w-full sm:w-96">
            <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
              <span class="text-slate-400">⌕</span>
              <input name="q" value="{{ $q ?? '' }}"
                class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                placeholder="search jobs"/>
            </div>
          </div>

      {{-- Filter row (same compact style as Users) --}}
      <div class="flex flex-wrap items-center gap-2">
        <select name="status"
          class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <option value="">Status</option>
          <option value="open" {{ ($status ?? '') === 'open' ? 'selected' : '' }}>Open</option>
          <option value="closed" {{ ($status ?? '') === 'closed' ? 'selected' : '' }}>Closed</option>
        </select>

        <select name="held"
          class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <option value="">Held</option>
          <option value="1" {{ ($held ?? '') === '1' ? 'selected' : '' }}>Held</option>
          <option value="0" {{ ($held ?? '') === '0' ? 'selected' : '' }}>Not held</option>
        </select>

        <select name="disabled"
          class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <option value="">Disabled</option>
          <option value="1" {{ ($disabled ?? '') === '1' ? 'selected' : '' }}>Disabled</option>
          <option value="0" {{ ($disabled ?? '') === '0' ? 'selected' : '' }}>Enabled</option>
        </select>

        <button type="submit"
          class="rounded-2xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
          Apply
        </button>

        <a href="{{ route('admin.job-posts.index') }}"
          class="rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
          Reset
        </a>

        <div class="ml-auto text-sm text-slate-500">
          Showing {{ $jobPosts->firstItem() ?? 0 }}–{{ $jobPosts->lastItem() ?? 0 }} of {{ $jobPosts->total() }}
        </div>
      </div>

    </form>
  </div>

  {{-- Table card (match Users: header divider line + row separators) --}}
  <div class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-5">
      <div class="font-semibold text-slate-900">Job Posts</div>
      <div class="text-sm text-slate-500">Employer job postings queue</div>
    </div>

    {{-- Divider line like Users --}}
    <div class="border-t border-slate-200"></div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="text-left font-semibold px-6 py-3">Job</th>
            <th class="text-left font-semibold px-6 py-3">Location</th>
            <th class="text-left font-semibold px-6 py-3">Status</th>
            <th class="text-left font-semibold px-6 py-3">Held</th>
            <th class="text-left font-semibold px-6 py-3">Disabled</th>
            <th class="text-left font-semibold px-6 py-3">Posted</th>
            <th class="text-left font-semibold px-6 py-3">Actions</th>
          </tr>
        </thead>

        {{-- Strong row separators like Users --}}
        <tbody class="divide-y divide-slate-200">
          @forelse($jobPosts as $jp)
            <tr class="hover:bg-slate-50">
              <td class="px-6 py-4">
                <div class="font-semibold text-slate-900">{{ $jp->title }}</div>
                <div class="text-slate-500 text-xs">{{ $jp->industry ?? '—' }}</div>
              </td>

              <td class="px-6 py-4 text-slate-700">
                {{ $jp->country ?? '—' }}{{ $jp->city ? ', '.$jp->city : '' }}{{ $jp->area ? ', '.$jp->area : '' }}
              </td>

              <td class="px-6 py-4">
                @if($jp->status === 'open')
                  <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-emerald-700 font-semibold">Open</span>
                @else
                  <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-slate-700 font-semibold">Closed</span>
                @endif
              </td>

              <td class="px-6 py-4">
                @if($jp->is_held)
                  <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-amber-800 font-semibold">Held</span>
                @else
                  <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-slate-700 font-semibold">—</span>
                @endif
              </td>

              <td class="px-6 py-4">
                @if($jp->is_disabled)
                  <span class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-rose-700 font-semibold">Disabled</span>
                @else
                  <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-slate-700 font-semibold">Enabled</span>
                @endif
              </td>

              <td class="px-6 py-4 text-slate-700">
                {{ optional($jp->posted_at)->format('Y-m-d') ?? optional($jp->created_at)->format('Y-m-d') ?? '—' }}
              </td>

              <td class="px-6 py-4">
                <div class="flex gap-2">
                  <a href="{{ route('admin.job-posts.show', $jp) }}"
                     class="rounded-2xl border border-slate-200 bg-white px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50">
                    View
                  </a>

                  @if(!$jp->is_held)
                    <form method="POST" action="{{ route('admin.job-posts.hold', $jp) }}">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                        class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-2 font-semibold text-amber-800 hover:bg-amber-100">
                        Hold
                      </button>
                    </form>
                  @else
                    <form method="POST" action="{{ route('admin.job-posts.unhold', $jp) }}">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                        class="rounded-2xl border border-slate-200 bg-white px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50">
                        Unhold
                      </button>
                    </form>
                  @endif

                  @if(!$jp->is_disabled)
                    <a href="{{ route('admin.job-posts.show', $jp) }}#disable"
                       class="rounded-2xl bg-rose-600 px-4 py-2 font-semibold text-white hover:bg-rose-700">
                      Disable
                    </a>
                  @else
                    <form method="POST" action="{{ route('admin.job-posts.enable', $jp) }}">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                        class="rounded-2xl border border-slate-200 bg-white px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50">
                        Enable
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                No job posts found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-slate-200"></div>

    <div class="px-6 py-4">
      {{ $jobPosts->links() }}
    </div>
  </div>

</div>
@endsection