@extends('adminpage.layout')
@section('title','Edit Area')
@section('page_title','Edit Area')

@section('content')
<div class="space-y-6">

  @include('adminpage.components.flash')

  <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <p class="text-xs text-slate-500">Country / City</p>
      <h2 class="text-lg font-semibold text-slate-900">{{ $country->name }} / {{ $city->name }}</h2>
    </div>
    <a href="{{ route('admin.locations.areas.index', [$country, $city]) }}"
       class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
      ← Back
    </a>
  </div>

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <h2 class="text-sm font-semibold text-slate-900">Edit Area / Barangay</h2>

    <form method="POST" action="{{ route('admin.locations.areas.update', [$country, $city, $area]) }}"
          class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
      @csrf
      @method('PUT')

      <div class="sm:col-span-2">
        <label class="text-xs font-semibold text-slate-700">Name</label>
        <input name="name" value="{{ old('name', $area->name) }}" required
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" />
      </div>

      <div>
        <label class="text-xs font-semibold text-slate-700">Sort order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $area->sort_order) }}" min="0"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" />
      </div>

      <div class="flex items-end">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
          <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300"
                 {{ old('is_active', $area->is_active) ? 'checked' : '' }}>
          Active
        </label>
      </div>

      <div class="sm:col-span-2 flex justify-end gap-2">
        <a href="{{ route('admin.locations.areas.index', [$country, $city]) }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
          Cancel
        </a>
        <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Save changes
        </button>
      </div>
    </form>
  </div>

</div>
@endsection