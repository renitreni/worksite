@extends('adminpage.layout')
@section('title','Add Admin')
@section('page_title','Add Admin')

@section('content')
<div class="space-y-6">

  @include('adminpage.components.flash')

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

    <div class="mb-4 rounded-lg bg-blue-50 border border-blue-200 p-3 text-sm text-blue-800">
      The admin will receive an email invitation to set their password and activate their account.
    </div>

    <form method="POST" action="{{ route('admin.admins.store') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      @csrf

      <div>
        <label class="text-xs font-semibold text-slate-700">First name</label>
        <input name="first_name"
               value="{{ old('first_name') }}"
               autocomplete="given-name"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>

      <div>
        <label class="text-xs font-semibold text-slate-700">Last name</label>
        <input name="last_name"
               value="{{ old('last_name') }}"
               autocomplete="family-name"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>

      <div class="sm:col-span-2">
        <label class="text-xs font-semibold text-slate-700">Email</label>
        <input type="email"
               name="email"
               value="{{ old('email') }}"
               autocomplete="email"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" />
      </div>

      <div class="sm:col-span-2 flex gap-2">
        <a href="{{ route('admin.admins.index') }}"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Cancel
        </a>

        <button type="submit"
          class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Send Invitation
        </button>
      </div>

    </form>
  </div>

</div>
@endsection