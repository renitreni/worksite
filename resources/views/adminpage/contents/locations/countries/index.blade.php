@extends('adminpage.layout')
@section('title','Countries')
@section('page_title','Manage Countries')

@section('content')
@php
  $q = $q ?? request('q', '');
  $active = $active ?? request('active', '');
@endphp

<div class="space-y-6">
  @include('adminpage.components.flash')

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="GET"
          action="{{ route('admin.locations.countries.index') }}"
          class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:flex-wrap">
        <div class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-96">
          <span class="text-slate-400">⌕</span>
          <input
            name="q"
            value="{{ $q }}"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
            placeholder="Search countries..."
          />
        </div>

        <select
          name="active"
          class="w-full sm:w-56 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
        >
          <option value=""  {{ ($active ?? '') === '' ? 'selected' : '' }}>All</option>
          <option value="1" {{ ($active ?? '') === '1' ? 'selected' : '' }}>Active only</option>
          <option value="0" {{ ($active ?? '') === '0' ? 'selected' : '' }}>Inactive only</option>
        </select>
      </div>

      <div class="flex items-center gap-2">
        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Search
        </button>

        @if($q || $active !== '')
          <a
            href="{{ route('admin.locations.countries.index') }}"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
          >
            Clear
          </a>
        @endif
      </div>
    </form>
  </div>

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-3 flex items-center justify-between">
      <h2 class="text-sm font-semibold text-slate-900">Add Country</h2>
      <p class="text-xs text-slate-500">Image optional</p>
    </div>

    <form method="POST"
          action="{{ route('admin.locations.countries.store') }}"
          enctype="multipart/form-data"
          class="grid grid-cols-1 gap-4 md:grid-cols-6">
      @csrf

      <div class="md:col-span-2">
        <label class="text-xs font-semibold text-slate-700">Name</label>
        <input name="name" value="{{ old('name') }}" required
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
               placeholder="e.g. Philippines" />
      </div>

      <div>
        <label class="text-xs font-semibold text-slate-700">Code</label>
        <input name="code" value="{{ old('code') }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
               placeholder="PH" maxlength="5" />
      </div>

      <div>
        <label class="text-xs font-semibold text-slate-700">Sort order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" />
      </div>

      <div class="md:col-span-1">
        <label class="text-xs font-semibold text-slate-700">Image</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
               class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" />
        <p class="mt-1 text-xs text-slate-500">max 2MB</p>
      </div>

      <div class="flex items-end gap-3">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
          <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300"
                 {{ old('is_active', 1) ? 'checked' : '' }}>
          Active
        </label>

        <button class="ml-auto rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Add
        </button>
      </div>
    </form>
  </div>

  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-4">
      <div class="flex items-center justify-between">
        <h2 class="text-sm font-semibold text-slate-900">Countries</h2>
        <p class="text-xs text-slate-500">Total: {{ $countries->total() }}</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
          <tr>
            <th class="px-5 py-3">Image</th>
            <th class="px-5 py-3">Name</th>
            <th class="px-5 py-3">Code</th>
            <th class="px-5 py-3">Order & Status</th>
            <th class="px-5 py-3">Cities</th>
            <th class="px-5 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse($countries as $country)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3">
                @if(!empty($country->image))
                  <img src="{{ asset('storage/'.$country->image) }}"
                       alt="{{ $country->name }}"
                       class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200" />
                @else
                  <span class="text-xs font-semibold text-slate-500">No image</span>
                @endif
              </td>

              <td class="px-5 py-3 font-semibold text-slate-900">{{ $country->name }}</td>

              <td class="px-5 py-3 text-slate-700">{{ $country->code }}</td>

              <td class="px-5 py-3">
                <form method="POST"
                      action="{{ route('admin.locations.countries.meta', $country) }}"
                      class="flex items-center gap-2">
                  @csrf
                  @method('PATCH')

                  <input
                    type="number"
                    name="sort_order"
                    value="{{ $country->sort_order }}"
                    min="0"
                    class="w-20 rounded-xl border border-slate-200 px-2 py-1 text-xs"
                  />

                  <select
                    name="is_active"
                    class="rounded-xl border border-slate-200 px-2 py-1 text-xs"
                  >
                    <option value="1" {{ $country->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$country->is_active ? 'selected' : '' }}>Inactive</option>
                  </select>

                  <button
                    class="rounded-xl bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800"
                  >
                    Save
                  </button>
                </form>
              </td>

              <td class="px-5 py-3">
                <a href="{{ route('admin.locations.cities.index', $country) }}"
                   class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                  Manage cities
                </a>
              </td>

              <td class="px-5 py-3">
                <div class="flex justify-end gap-2">
                  <a href="{{ route('admin.locations.countries.edit', $country) }}"
                     class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Edit
                  </a>

                  <form method="POST"
                        action="{{ route('admin.locations.countries.destroy', $country) }}"
                        onsubmit="return confirm('Delete this country? This will be blocked if it has cities.')">
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
              <td colspan="6" class="px-5 py-8 text-center text-slate-500">No countries found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-slate-200 px-5 py-4">
      {{ $countries->links() }}
    </div>
  </div>

</div>
@endsection