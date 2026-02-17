@extends('adminpage.layout')
@section('title','View User')
@section('page_title','View User')

@section('content')
<div class="max-w-3xl space-y-6">
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
    <div class="flex items-start justify-between">
      <div>
        <div class="text-sm font-semibold text-slate-900">{{ $user->name }}</div>
        <div class="text-xs text-slate-500">{{ $user->email }}</div>
      </div>
      <div class="flex gap-2">
        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
          {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-rose-50 text-rose-700 ring-rose-200' }}">
          {{ $user->is_active ? 'Active' : 'Disabled' }}
        </span>

        @if($user->role === 'candidate')
          @php $isVerified = !is_null($user->email_verified_at); @endphp
          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
            {{ $isVerified ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-50 text-slate-700 ring-slate-200' }}">
            {{ $isVerified ? 'Verified' : 'Not verified' }}
          </span>
        @endif
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
      <div><div class="text-xs text-slate-500">Role</div><div class="font-semibold">{{ ucfirst($user->role) }}</div></div>
      <div><div class="text-xs text-slate-500">Phone</div><div class="font-semibold">{{ $user->phone ?? '—' }}</div></div>
      <div><div class="text-xs text-slate-500">Joined</div><div class="font-semibold">{{ optional($user->created_at)->format('Y-m-d') }}</div></div>
      <div><div class="text-xs text-slate-500">Archived</div><div class="font-semibold">{{ $user->archived_at ? 'Yes' : 'No' }}</div></div>
    </div>

    @if($user->role === 'employer')
      <div class="pt-4 border-t border-slate-200">
        <div class="text-sm font-semibold mb-2">Employer Profile</div>
        <div class="text-sm text-slate-700 space-y-1">
          <div>Status: <span class="font-semibold">{{ ucfirst(optional($user->employerProfile)->status ?? 'pending') }}</span></div>
          <div>Company: <span class="font-semibold">{{ optional($user->employerProfile)->company_name ?? '—' }}</span></div>
          <div>Company Email: <span class="font-semibold">{{ optional($user->employerProfile)->company_email ?? '—' }}</span></div>
          <div>Address: <span class="font-semibold">{{ optional($user->employerProfile)->company_address ?? '—' }}</span></div>
          <div>Contact: <span class="font-semibold">{{ optional($user->employerProfile)->company_contact ?? '—' }}</span></div>
          <div>Representative: <span class="font-semibold">{{ optional($user->employerProfile)->representative_name ?? '—' }}</span></div>
          <div>Position: <span class="font-semibold">{{ optional($user->employerProfile)->position ?? '—' }}</span></div>
        </div>
      </div>
    @endif

    <div class="flex gap-2 pt-2">
      <a href="{{ route('admin.users.edit', $user) }}"
         class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
        Edit
      </a>
      <a href="{{ route('admin.users.index') }}"
         class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
        Back
      </a>
    </div>
  </div>
</div>
@endsection
