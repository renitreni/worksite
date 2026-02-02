@extends('adminpage.layout')
@section('title','Categories / Skills / Locations')
@section('page_title','Manage Categories, Skills, Locations')

@section('content')
@php
  // sample rows (replace later)
  $categories = ['IT & Software','Healthcare','Logistics','Construction','Customer Service'];
  $skills = ['JavaScript','Laravel','MySQL','Customer Support','MS Excel'];
  $locations = [
    ['city'=>'Santa Rosa', 'barangays'=>['Balibago','Tagapo','Pulong Santa Cruz']],
    ['city'=>'Parañaque', 'barangays'=>['San Dionisio','BF Homes','Tambo']],
  ];
@endphp

<div class="space-y-6">

  {{-- Header --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <div>
        <div class="text-sm font-semibold">Standardize searchable data</div>
        <div class="mt-1 text-xs text-slate-500">
          Add / edit / delete categories, skills, and locations. Changes reflect across the system (backend later).
        </div>
      </div>

      <div class="flex flex-col gap-2 sm:flex-row">
        <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
          <span class="text-slate-400">⌕</span>
          <input class="w-64 bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                 placeholder="Search category, skill, city…" />
        </div>
        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          + Add New
        </button>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">

    {{-- Categories --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold">Job Categories</div>
        <div class="mt-1 text-xs text-slate-500">Used for job posting and search filters</div>
      </div>

      <div class="p-5">
        <div class="flex gap-2">
          <input class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                 placeholder="Add category (e.g., Finance)" />
          <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Add</button>
        </div>

        <div class="mt-4 space-y-2">
          @foreach($categories as $c)
            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
              <div class="text-sm font-semibold text-slate-800">{{ $c }}</div>
              <div class="flex gap-2">
                <button class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold hover:bg-slate-50">Edit</button>
                <button class="rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Delete</button>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-4 text-xs text-slate-500">Tip: Keep names short and consistent.</div>
      </div>
    </div>

    {{-- Skills --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold">Skills</div>
        <div class="mt-1 text-xs text-slate-500">Suggested skills for job requirements</div>
      </div>

      <div class="p-5">
        <div class="flex gap-2">
          <input class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                 placeholder="Add skill (e.g., React)" />
          <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Add</button>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
          @foreach($skills as $s)
            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-semibold text-slate-700">
              {{ $s }}
              <button class="text-slate-400 hover:text-slate-700" title="Edit">✎</button>
              <button class="text-rose-500 hover:text-rose-700" title="Delete">×</button>
            </span>
          @endforeach
        </div>

        <div class="mt-4 text-xs text-slate-500">Tip: Avoid duplicates; use a standard naming format.</div>
      </div>
    </div>

    {{-- Locations --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold">Locations</div>
        <div class="mt-1 text-xs text-slate-500">Cities and barangays for location filters</div>
      </div>

      <div class="p-5 space-y-4">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs font-semibold text-slate-700">Add City</div>
          <div class="mt-2 flex gap-2">
            <input class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                   placeholder="City (e.g., Manila)" />
            <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Add</button>
          </div>
        </div>

        <div class="space-y-3">
          @foreach($locations as $loc)
            <div class="rounded-2xl border border-slate-200 p-4">
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-sm font-semibold">{{ $loc['city'] }}</div>
                  <div class="text-xs text-slate-500">{{ count($loc['barangays']) }} barangays</div>
                </div>
                <div class="flex gap-2">
                  <button class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold hover:bg-slate-50">Edit</button>
                  <button class="rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">Delete</button>
                </div>
              </div>

              <div class="mt-3 flex flex-wrap gap-2">
                @foreach($loc['barangays'] as $b)
                  <span class="rounded-full bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                    {{ $b }}
                  </span>
                @endforeach
              </div>

              <div class="mt-3 flex gap-2">
                <input class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                       placeholder="Add barangay to {{ $loc['city'] }}" />
                <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
                  Add
                </button>
              </div>
            </div>
          @endforeach
        </div>

      </div>
    </div>

  </div>
</div>
@endsection
