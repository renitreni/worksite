@extends('adminpage.layout')
@section('title','Edit User')
@section('page_title','Edit User')

@section('content')
<div class="max-w-3xl space-y-6">

  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-start justify-between">
      <div>
        <div class="text-sm font-semibold text-slate-900">{{ $user->name }}</div>
        <div class="text-xs text-slate-500">{{ $user->email }}</div>
      </div>

      <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
        {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-rose-50 text-rose-700 ring-rose-200' }}">
        {{ $user->is_active ? 'Active' : 'Disabled' }}
      </span>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-6 space-y-4">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label class="text-xs font-semibold text-slate-600">First name</label>
          <input name="first_name" value="{{ old('first_name', $user->first_name) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" />
          @error('first_name') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-600">Last name</label>
          <input name="last_name" value="{{ old('last_name', $user->last_name) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" />
          @error('last_name') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
        </div>
      </div>

      <div>
        <label class="text-xs font-semibold text-slate-600">Email</label>
        <input name="email" value="{{ old('email', $user->email) }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" />
        @error('email') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="text-xs font-semibold text-slate-600">Phone</label>
        <input name="phone" value="{{ old('phone', $user->phone) }}"
               class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" />
        @error('phone') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
      </div>

      @if($user->role === 'employer')
        <div>
          <label class="text-xs font-semibold text-slate-600">Employer approval status</label>
          <select name="employer_status"
                  class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
            @php $st = old('employer_status', optional($user->employerProfile)->status ?? 'pending'); @endphp
            <option value="pending"  {{ $st==='pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ $st==='approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ $st==='rejected' ? 'selected' : '' }}>Rejected</option>
          </select>
          @error('employer_status') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
        </div>
      @endif

      <div class="flex gap-2 pt-2">
        <button type="submit"
                class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Save changes
        </button>

        <a href="{{ route('admin.users.index') }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Back
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
