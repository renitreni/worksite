@extends('adminpage.layout')
@section('title','Edit Industry')
@section('page_title','Edit Industry')

@section('content')
<div class="space-y-6">

  @include('adminpage.components.flash')

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-center justify-between">
      <h2 class="text-sm font-semibold text-slate-900">Edit Industry</h2>
      <a href="{{ route('admin.industries.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
        ← Back
      </a>
    </div>

    <form method="POST" action="{{ route('admin.industries.update', $industry) }}" enctype="multipart/form-data"
          class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      @csrf
      @method('PUT')

      <div class="sm:col-span-2">
        <label class="text-xs font-semibold text-slate-700">Name</label>
        <input name="name" value="{{ old('name', $industry->name) }}" required
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" />
      </div>

      <div>
        <label class="text-xs font-semibold text-slate-700">Sort order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $industry->sort_order) }}" min="0"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" />
      </div>

      <div class="flex items-end">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
          <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300"
                 {{ old('is_active', $industry->is_active) ? 'checked' : '' }}>
          Active
        </label>
      </div>

      <div class="sm:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <p class="text-xs font-semibold text-slate-700">Current image</p>
        <div class="mt-2 flex items-center gap-4">
          @if(!empty($industry->image))
            <img src="{{ asset('storage/'.$industry->image) }}"
                 alt="{{ $industry->name }}"
                 class="h-16 w-16 rounded-2xl object-cover ring-1 ring-slate-200" />
            <p class="text-xs text-slate-600">Upload a new file to replace it.</p>
          @else
            <p class="text-xs font-semibold text-slate-500">No image uploaded.</p>
          @endif
        </div>

        <div class="mt-4">
          <label class="text-xs font-semibold text-slate-700">Replace image</label>
          <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
                 class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" />
          <p class="mt-1 text-xs text-slate-500">jpg/jpeg/png/webp • max 2MB • if empty, keeps existing</p>
        </div>
      </div>

      <div class="sm:col-span-2 flex justify-end gap-2">
        <a href="{{ route('admin.industries.index') }}"
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