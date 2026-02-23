@extends('adminpage.layout')
@section('title','Industries')
@section('page_title','Manage Industries')

@section('content')
@php
  $q = $q ?? request('q', '');
  $active = $active ?? request('active', '');
@endphp

<div class="space-y-6">

  @include('adminpage.components.flash')

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="GET"
          action="{{ route('admin.industries.index') }}"
          class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:flex-wrap">

        <div class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-96">
          <span class="text-slate-400">⌕</span>
          <input
            name="q"
            value="{{ $q }}"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
            placeholder="Search industries..."
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
            href="{{ route('admin.industries.index') }}"
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
      <h2 class="text-sm font-semibold text-slate-900">Add Industry</h2>
      <p class="text-xs text-slate-500">Image optional (but recommended for landing page)</p>
    </div>

    <form method="POST"
          action="{{ route('admin.industries.store') }}"
          enctype="multipart/form-data"
          class="grid grid-cols-1 gap-4 md:grid-cols-5">
      @csrf

      <div class="md:col-span-2">
        <label class="text-xs font-semibold text-slate-700">Name</label>
        <input name="name" value="{{ old('name') }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
               placeholder="e.g. IT / Software" required />
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
        <p class="mt-1 text-xs text-slate-500">jpg/jpeg/png/webp • max 2MB</p>
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
        <h2 class="text-sm font-semibold text-slate-900">Industries</h2>
        <p class="text-xs text-slate-500">Total: {{ $industries->total() }}</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
          <tr>
            <th class="px-5 py-3">Image</th>
            <th class="px-5 py-3">Name</th>
            <th class="px-5 py-3">Order & Status</th>
            <th class="px-5 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse($industries as $industry)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3">
                @if(!empty($industry->image))
                  <img src="{{ asset('storage/'.$industry->image) }}"
                       alt="{{ $industry->name }}"
                       class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200" />
                @else
                  <span class="text-xs font-semibold text-slate-500">No image</span>
                @endif
              </td>

              <td class="px-5 py-3 font-semibold text-slate-900">{{ $industry->name }}</td>

              <td class="px-5 py-3">
                <form method="POST"
                      action="{{ route('admin.industries.meta', $industry) }}"
                      class="flex items-center gap-2">
                  @csrf
                  @method('PATCH')

                  <input
                    type="number"
                    name="sort_order"
                    value="{{ $industry->sort_order }}"
                    min="0"
                    class="w-20 rounded-xl border border-slate-200 px-2 py-1 text-xs"
                  />

                  <select
                    name="is_active"
                    class="rounded-xl border border-slate-200 px-2 py-1 text-xs"
                  >
                    <option value="1" {{ $industry->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$industry->is_active ? 'selected' : '' }}>Inactive</option>
                  </select>

                  <button
                    class="rounded-xl bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800"
                  >
                    Save
                  </button>
                </form>
              </td>

              <td class="px-5 py-3">
                <div class="flex justify-end gap-2">
                  <a href="{{ route('admin.industries.edit', $industry) }}"
                     class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Edit
                  </a>

                  <form method="POST"
                        action="{{ route('admin.industries.destroy', $industry) }}"
                        onsubmit="return confirm('Delete this industry?')">
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
              <td colspan="4" class="px-5 py-8 text-center text-slate-500">No industries found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-slate-200 px-5 py-4">
      {{ $industries->links() }}
    </div>
  </div>

</div>
@endsection