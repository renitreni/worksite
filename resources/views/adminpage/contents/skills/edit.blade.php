@extends('adminpage.layout')
@section('title','Edit Skill')
@section('page_title','Edit Skill')

@section('content')
<div class="space-y-6">

  @include('adminpage.components.flash')

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-center justify-between">
      <h2 class="text-sm font-semibold text-slate-900">Edit Skill</h2>
      <a href="{{ route('admin.skills.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
        ← Back
      </a>
    </div>

    <form method="POST" action="{{ route('admin.skills.update', $skill) }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      @csrf
      @method('PUT')

      {{-- Name --}}
      <div class="sm:col-span-2">
        <label class="text-xs font-semibold text-slate-700">Name</label>
        <input
          name="name"
          value="{{ old('name', $skill->name) }}"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
          required
        />
      </div>

      {{-- Industry --}}
      <div class="sm:col-span-2">
        <label class="text-xs font-semibold text-slate-700">Industry</label>
        <select
          name="industry_id"
          class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
          required
        >
          <option value="" disabled>Select industry</option>
          @foreach ($industries as $ind)
            <option value="{{ $ind->id }}"
              {{ (string) old('industry_id', $skill->industry_id) === (string) $ind->id ? 'selected' : '' }}>
              {{ $ind->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Sort order --}}
      <div>
        <label class="text-xs font-semibold text-slate-700">Sort order</label>
        <input
          type="number"
          name="sort_order"
          value="{{ old('sort_order', $skill->sort_order) }}"
          min="0"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
        />
      </div>

      {{-- Active --}}
      <div class="flex items-end">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
          <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300"
                 {{ old('is_active', $skill->is_active) ? 'checked' : '' }}>
          Active
        </label>
      </div>

      {{-- Buttons --}}
      <div class="sm:col-span-2 flex justify-end gap-2">
        <a href="{{ route('admin.skills.index') }}"
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