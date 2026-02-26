@extends('adminpage.layout')
@section('title', 'View User')
@section('page_title', 'View User')

@section('content')
  @php
    $accStatus = $user->account_status ?? 'active'; // active|disabled|hold
    $isVerified = !is_null($user->email_verified_at);

    // ✅ NEW: employer status now comes from employer_verifications table
    $empStatus = $user->employerProfile?->verification?->status ?? 'pending';

    $name = $user->name ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));

    // ✅ OPTIONAL: subscription details (new table)
    $subPlan = $user->employerProfile?->subscription?->plan ?? 'basic';
    $subStatus = $user->employerProfile?->subscription?->subscription_status ?? 'inactive';
    $subEndsAt = $user->employerProfile?->subscription?->ends_at;
  @endphp

  <div class="w-full space-y-6">

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

      {{-- Header (simple + readable) --}}
      <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
          <div class="text-lg font-semibold text-slate-900">
            {{ $user->role === 'employer'
    ? ($user->employerProfile?->company_name ?: $name)
    : $name }}
          </div>
          <div class="text-sm text-slate-500">{{ $user->email }}</div>
        </div>

        <div class="flex flex-wrap gap-2 sm:justify-end">

          {{-- Account Status --}}
          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
            {{ $accStatus === 'active'
    ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
    : ($accStatus === 'hold'
      ? 'bg-amber-50 text-amber-800 ring-amber-200'
      : 'bg-rose-50 text-rose-700 ring-rose-200') }}">
            {{ $accStatus === 'active' ? 'Active' : ($accStatus === 'hold' ? 'On hold' : 'Disabled') }}
          </span>

          {{-- Candidate Verification --}}
          @if($user->role === 'candidate')
            <span
              class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                {{ $isVerified ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-50 text-slate-700 ring-slate-200' }}">
              {{ $isVerified ? 'Verified' : 'Not verified' }}
            </span>
          @endif

          {{-- Employer Approval --}}
          @if($user->role === 'employer')
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                    {{ $empStatus === 'approved'
            ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
            : ($empStatus === 'rejected'
              ? 'bg-rose-50 text-rose-700 ring-rose-200'
              : ($empStatus === 'suspended'
                ? 'bg-slate-900 text-white ring-slate-900'
                : 'bg-amber-50 text-amber-800 ring-amber-200')) }}">
                  {{ ucfirst($empStatus) }}
                </span>
          @endif

          {{-- Archived --}}
          @if($user->archived_at)
            <span
              class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 bg-slate-900 text-white ring-slate-900">
              Archived
            </span>
          @endif

        </div>
      </div>

      {{-- Details --}}
      <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs font-semibold text-slate-500">Role</div>
          <div class="mt-1 font-semibold text-slate-900">{{ ucfirst($user->role) }}</div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs font-semibold text-slate-500">Phone</div>
          <div class="mt-1 font-semibold text-slate-900">{{ $user->phone ?? '—' }}</div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs font-semibold text-slate-500">Joined</div>
          <div class="mt-1 font-semibold text-slate-900">{{ optional($user->created_at)->format('Y-m-d') ?? '—' }}</div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs font-semibold text-slate-500">Archived</div>
          <div class="mt-1 font-semibold text-slate-900">{{ $user->archived_at ? 'Yes' : 'No' }}</div>
        </div>
      </div>

      {{-- Employer Section --}}
      @if($user->role === 'employer')
        <div class="mt-6 pt-6 border-t border-slate-200">
          <div class="text-sm font-semibold text-slate-900 mb-3">Employer Profile</div>

          {{-- ✅ OPTIONAL: show subscription --}}
          <div class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
              <div class="text-xs font-semibold text-slate-500">Plan</div>
              <div class="mt-1 font-semibold text-slate-900">{{ ucfirst($user->employerProfile?->subscription?->plan?->name ?? '—') }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
              <div class="text-xs font-semibold text-slate-500">Subscription Status</div>
              <div class="mt-1 font-semibold text-slate-900">{{ ucfirst($user->employerProfile?->subscription?->subscription_status ?? '—') }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
              <div class="text-xs font-semibold text-slate-500">Ends At</div>
              <div class="mt-1 font-semibold text-slate-900">
                {{ $subEndsAt ? $subEndsAt->format('Y-m-d') : '—' }}
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
            <div class="rounded-xl border border-slate-200 p-4">
              <div class="text-xs font-semibold text-slate-500">Company</div>
              <div class="mt-1 font-semibold text-slate-900">{{ $user->employerProfile?->company_name ?? '—' }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 p-4">
              <div class="text-xs font-semibold text-slate-500">Company Email</div>
              <div class="mt-1 font-semibold text-slate-900">{{ $user->email ?? '—' }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 p-4">
              <div class="text-xs font-semibold text-slate-500">Contact</div>
              <div class="mt-1 font-semibold text-slate-900">{{ $user->employerProfile?->company_contact ?? '—' }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 p-4 lg:col-span-3">
              <div class="text-xs font-semibold text-slate-500">Address</div>
              <div class="mt-1 font-semibold text-slate-900">{{ $user->employerProfile?->company_address ?? '—' }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 p-4">
              <div class="text-xs font-semibold text-slate-500">Representative</div>
              <div class="mt-1 font-semibold text-slate-900">{{ $user->employerProfile?->representative_name ?? '—' }}</div>
            </div>

            <div class="rounded-xl border border-slate-200 p-4">
              <div class="text-xs font-semibold text-slate-500">Position</div>
              <div class="mt-1 font-semibold text-slate-900">{{ $user->employerProfile?->position ?? '—' }}</div>
            </div>
          </div>
        </div>
      @endif

      {{-- Actions --}}
      <div class="mt-6 ">
        <a href="javascript:history.back()"
          class="rounded-xl border border-slate-200 bg-white px-8 py-3 text-sm font-semibold hover:bg-slate-50">
          Back
        </a>
      </div>
    </div>
  </div>
@endsection