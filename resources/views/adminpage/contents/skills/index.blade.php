@extends('adminpage.layout')
@section('title', 'Skills')
@section('page_title', 'Manage Skills')

@section('content')
@php
  /** @var \Illuminate\Pagination\LengthAwarePaginator $skills */
  $q = $q ?? request('q', '');
  $active = $active ?? request('active', '');        // '', '1', '0'
  $industryId = $industryId ?? request('industry_id', ''); // '' or id
@endphp

<div class="space-y-6">

  @include('adminpage.components.flash')

  {{-- ================= SEARCH + FILTERS ================= --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="GET"
          action="{{ route('admin.skills.index') }}"
          class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:flex-wrap">

        {{-- Search input --}}
        <div class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-96">
          <span class="text-slate-400">⌕</span>
          <input
            name="q"
            value="{{ $q }}"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
            placeholder="Search skills..."
          />
        </div>

        {{-- Industry filter --}}
        <select
          name="industry_id"
          class="w-full sm:w-72 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
        >
          <option value="" {{ ($industryId ?? '') === '' ? 'selected' : '' }}>All industries</option>
          @foreach ($industries as $ind)
            <option value="{{ $ind->id }}" {{ (string)($industryId ?? '') === (string)$ind->id ? 'selected' : '' }}>
              {{ $ind->name }}
            </option>
          @endforeach
        </select>

        {{-- Active filter --}}
        <select
          name="active"
          class="w-full sm:w-56 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
        >
          <option value=""  {{ ($active ?? '') === '' ? 'selected' : '' }}>All</option>
          <option value="1" {{ ($active ?? '') === '1' ? 'selected' : '' }}>Active only</option>
          <option value="0" {{ ($active ?? '') === '0' ? 'selected' : '' }}>Inactive only</option>
        </select>

      </div>

      {{-- Buttons --}}
      <div class="flex items-center gap-2">
        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Search
        </button>

        @if($q || $active !== '' || ($industryId ?? '') !== '')
          <a
            href="{{ route('admin.skills.index') }}"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
          >
            Clear
          </a>
        @endif
      </div>

    </form>
  </div>
  {{-- ================= END SEARCH BLOCK ================= --}}


  {{-- Add Skill --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-3 flex items-center justify-between">
      <h2 class="text-sm font-semibold text-slate-900">Add Skill</h2>
      <p class="text-xs text-slate-500">Fields: name, industry, active, sort order</p>
    </div>

    {{-- ✅ alignment fix: make last column span full width and right-align controls --}}
    <form method="POST" action="{{ route('admin.skills.store') }}"
          class="grid grid-cols-1 gap-4 md:grid-cols-12 md:items-end">
      @csrf

      {{-- Name --}}
      <div class="md:col-span-5">
        <label class="text-xs font-semibold text-slate-700">Name</label>
        <input
          name="name"
          value="{{ old('name') }}"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
          placeholder="e.g. Welder"
          required
        />
      </div>

      {{-- Industry --}}
      <div class="md:col-span-4">
        <label class="text-xs font-semibold text-slate-700">Industry</label>
        <select
          name="industry_id"
          class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
          required
        >
          <option value="" disabled {{ old('industry_id') ? '' : 'selected' }}>Select industry</option>
          @foreach ($industries as $ind)
            <option value="{{ $ind->id }}" {{ (string)old('industry_id') === (string)$ind->id ? 'selected' : '' }}>
              {{ $ind->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Sort order --}}
      <div class="md:col-span-1">
        <label class="text-xs font-semibold text-slate-700">Order</label>
        <input
          type="number"
          name="sort_order"
          value="{{ old('sort_order', 0) }}"
          min="0"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
        />
      </div>

      {{-- Active + Add button (✅ stays aligned, no drop) --}}
      <div class="md:col-span-2 flex items-center justify-between gap-3 md:justify-end">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
          <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300"
                 {{ old('is_active', 1) ? 'checked' : '' }}>
          Active
        </label>

        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Add
        </button>
      </div>

    </form>
  </div>


  {{-- ================= SKILLS LIST ================= --}}
  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-4">
      <div class="flex items-center justify-between">
        <h2 class="text-sm font-semibold text-slate-900">Skills</h2>
        <p class="text-xs text-slate-500">Total: {{ $skills->total() }}</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
          <tr>
            <th class="px-5 py-3">Name</th>
            <th class="px-5 py-3">Industry</th>
            <th class="px-5 py-3">Order & Status</th>
            <th class="px-5 py-3 text-right">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-200">
          @forelse($skills as $skill)
            <tr class="hover:bg-slate-50">

              <td class="px-5 py-3 font-semibold text-slate-900">
                {{ $skill->name }}
              </td>

              {{-- ✅ simple text only, no highlight --}}
              <td class="px-5 py-3 text-slate-700">
                {{ $skill->industry->name ?? '—' }}
              </td>

              <td class="px-5 py-3">
                <form method="POST"
                      action="{{ route('admin.skills.meta', $skill) }}"
                      class="flex items-center gap-2">
                  @csrf
                  @method('PATCH')

                  <input
                    type="number"
                    name="sort_order"
                    value="{{ $skill->sort_order }}"
                    min="0"
                    class="w-20 rounded-xl border border-slate-200 px-2 py-1 text-xs"
                  />

                  <select
                    name="is_active"
                    class="rounded-xl border border-slate-200 px-2 py-1 text-xs"
                  >
                    <option value="1" {{ $skill->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$skill->is_active ? 'selected' : '' }}>Inactive</option>
                  </select>

                  <button class="rounded-xl bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800">
                    Save
                  </button>
                </form>
              </td>

              <td class="px-5 py-3">
                <div class="flex justify-end gap-2">
                  <a href="{{ route('admin.skills.edit', $skill) }}"
                     class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Edit
                  </a>

                  <form method="POST"
                        action="{{ route('admin.skills.destroy', $skill) }}"
                        onsubmit="return confirm('Delete this skill?')">
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
              <td colspan="4" class="px-5 py-8 text-center text-slate-500">
                No skills found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-slate-200 px-5 py-4">
      {{ $skills->links() }}
    </div>
  </div>
  {{-- ================= END SKILLS LIST ================= --}}

</div>
@endsection