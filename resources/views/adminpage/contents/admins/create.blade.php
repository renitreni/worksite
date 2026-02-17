@extends('adminpage.layout')
@section('title','Add Admin')
@section('page_title','Add Admin')

@section('content')
<div class="space-y-6">

  @if($errors->any())
    <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
      <div class="font-semibold">Please fix the errors:</div>
      <ul class="mt-2 list-disc pl-5">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="POST" action="{{ route('admin.admins.store') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      @csrf

      <div>
        <label class="text-xs font-semibold text-slate-700">First name</label>
        <input name="first_name" value="{{ old('first_name') }}"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>

      <div>
        <label class="text-xs font-semibold text-slate-700">Last name</label>
        <input name="last_name" value="{{ old('last_name') }}"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>

      <div class="sm:col-span-2">
        <label class="text-xs font-semibold text-slate-700">Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>

      <div class="sm:col-span-2">
        <label class="text-xs font-semibold text-slate-700">Password</label>
        <input type="password" name="password"
          class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
        <div class="mt-1 text-[11px] text-slate-500">Min 8 characters.</div>
      </div>

      <div class="sm:col-span-2 flex gap-2">
        <a href="{{ route('admin.admins.index') }}"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Cancel
        </a>
        <button type="submit"
          class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Create Admin
        </button>
      </div>
    </form>
  </div>

</div>
@endsection
