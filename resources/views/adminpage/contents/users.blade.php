@extends('adminpage.layout')
@section('title','Users')
@section('page_title','Manage Users')

@section('content')
@php
  // $users, $q, $role, $verified, $archived should come from UserController@index
@endphp

<div class="space-y-6">

  {{-- Filters --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="GET" action="{{ route('admin.users.index') }}"
          class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

      <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <div class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-80">
          <span class="text-slate-400">⌕</span>
          <input
            name="q"
            value="{{ $q ?? '' }}"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
            placeholder="Search by name or email"
          />
        </div>

        <select
          name="role"
          class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
        >
          <option value="" {{ empty($role) ? 'selected' : '' }}>All roles</option>
          <option value="candidate" {{ ($role ?? '') === 'candidate' ? 'selected' : '' }}>Candidate</option>
          <option value="employer" {{ ($role ?? '') === 'employer' ? 'selected' : '' }}>Employer</option>
        </select>

        {{-- Candidate verification filter --}}
        <select
          name="verified"
          class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
        >
          <option value="" {{ empty($verified) ? 'selected' : '' }}>Candidate verification</option>
          <option value="verified" {{ ($verified ?? '') === 'verified' ? 'selected' : '' }}>Verified</option>
          <option value="unverified" {{ ($verified ?? '') === 'unverified' ? 'selected' : '' }}>Not verified</option>
        </select>

        {{-- Archived filter --}}
        <select
          name="archived"
          class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
        >
          @php $arch = $archived ?? '0'; @endphp
          <option value="0" {{ $arch === '0' ? 'selected' : '' }}>Active users</option>
          <option value="1" {{ $arch === '1' ? 'selected' : '' }}>Archived users</option>
        </select>

        <button
          type="submit"
          class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
        >
          Apply
        </button>

        <a
          href="{{ route('admin.users.index') }}"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50"
        >
          Reset
        </a>
      </div>

    </form>
  </div>

  {{-- Table --}}
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 p-5">
      <div class="text-sm font-semibold">Users</div>
      <div class="mt-1 text-xs text-slate-500">Employer + Candidate only</div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
          <tr>
            <th class="px-5 py-3">User</th>
            <th class="px-5 py-3">Role</th>
            <th class="px-5 py-3">Status</th>
            <th class="px-5 py-3">Joined</th>
            <th class="px-5 py-3">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-200">
          @forelse($users as $u)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-4">
                <div class="font-semibold text-slate-900">{{ $u->name ?? ($u->first_name.' '.$u->last_name) }}</div>
                <div class="text-xs text-slate-500">{{ $u->email }}</div>
              </td>

              <td class="px-5 py-4 text-slate-700">
                {{ ucfirst($u->role) }}
              </td>

              {{-- Status (ONE column only): Employer approval OR Candidate active + verification --}}
              <td class="px-5 py-4">
                @if($u->role === 'employer')
                  @php $status = optional($u->employerProfile)->status ?? 'pending'; @endphp
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                    {{ $status === 'approved'
                        ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                        : ($status === 'rejected'
                            ? 'bg-rose-50 text-rose-700 ring-rose-200'
                            : 'bg-amber-50 text-amber-800 ring-amber-200') }}">
                    {{ ucfirst($status) }}
                  </span>
                @else
                  @php
                    $active = (bool) ($u->is_active ?? true);
                    $isVerified = !is_null($u->email_verified_at);
                  @endphp

                  <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                      {{ $active
                          ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                          : 'bg-rose-50 text-rose-700 ring-rose-200' }}">
                      {{ $active ? 'Active' : 'Disabled' }}
                    </span>

                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                      {{ $isVerified
                          ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                          : 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                      {{ $isVerified ? 'Verified' : 'Not verified' }}
                    </span>
                  </div>
                @endif
              </td>

              <td class="px-5 py-4 text-slate-700">
                {{ optional($u->created_at)->format('Y-m-d') ?? '—' }}
              </td>

              {{-- Actions --}}
              <td class="px-5 py-4">
                <div class="flex flex-wrap gap-2">

                  {{-- View --}}
                  <a href="{{ route('admin.users.show', $u) }}"
                     class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    View
                  </a>

                  {{-- Edit --}}
                  <a href="{{ route('admin.users.edit', $u) }}"
                     class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Edit
                  </a>

                  {{-- Approve employer (treat missing profile as pending) --}}
                  @if($u->role === 'employer' && (optional($u->employerProfile)->status ?? 'pending') === 'pending')
                    <form method="POST" action="{{ route('admin.users.approve', $u) }}"
                          onsubmit="return confirm('Approve this employer?')">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                        class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                        Approve
                      </button>
                    </form>
                  @endif

                  {{-- Enable/Disable --}}
                  <form method="POST" action="{{ route('admin.users.toggle', $u) }}"
                        onsubmit="return confirm('{{ $u->is_active ? 'Disable' : 'Enable' }} this user?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                      class="rounded-xl px-3 py-2 text-xs font-semibold ring-1
                      {{ $u->is_active
                          ? 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'
                          : 'bg-emerald-600 text-white ring-emerald-600 hover:bg-emerald-700' }}">
                      {{ $u->is_active ? 'Disable' : 'Enable' }}
                    </button>
                  </form>

                  {{-- Archive / Restore --}}
                  @if(($archived ?? '0') === '0')
                    <form method="POST" action="{{ route('admin.users.archive', $u) }}"
                          onsubmit="return confirm('Archive this user?')">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                        class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                        Archive
                      </button>
                    </form>
                  @else
                    <form method="POST" action="{{ route('admin.users.restore', $u) }}"
                          onsubmit="return confirm('Restore this user?')">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                        class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                        Restore
                      </button>
                    </form>
                  @endif

                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">
                No results.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="border-t border-slate-200 p-4">
      {{ $users->links() }}
    </div>
  </div>

</div>
@endsection
