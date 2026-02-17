@extends('adminpage.layout')
@section('title','Admin Accounts')
@section('page_title','Admin Accounts')

@section('content')
@php
  // expects: $admins, $q
  $isArchived = request('archived') === '1';
@endphp

<div class="space-y-6">

  {{-- Filters --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <form method="GET" action="{{ route('admin.admins.index') }}"
          class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:flex-wrap">
        {{-- Search --}}
        <div class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-80">
          <span class="text-slate-400">⌕</span>
          <input
            name="q"
            value="{{ $q ?? '' }}"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
            placeholder="Search name or email"
          />
        </div>

        {{-- Keep archived filter on submit --}}
        <input type="hidden" name="archived" value="{{ $isArchived ? '1' : '0' }}">

        <button type="submit"
          class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Apply
        </button>

        <a href="{{ route('admin.admins.index', ['archived' => $isArchived ? 1 : 0]) }}"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Reset
        </a>

        {{-- Active / Archived toggle --}}
        <div class="flex items-center gap-2">
          <a href="{{ route('admin.admins.index', ['q' => $q ?? '', 'archived' => 0]) }}"
            class="rounded-xl px-4 py-2 text-sm font-semibold ring-1
            {{ !$isArchived ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50' }}">
            Active
          </a>

          <a href="{{ route('admin.admins.index', ['q' => $q ?? '', 'archived' => 1]) }}"
            class="rounded-xl px-4 py-2 text-sm font-semibold ring-1
            {{ $isArchived ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50' }}">
            Archived
          </a>
        </div>
      </div>

      {{-- Right button --}}
      @if(!$isArchived)
        <a href="{{ route('admin.admins.create') }}"
          class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
          + Add admin
        </a>
      @endif

    </form>
  </div>

  {{-- Table --}}
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 p-5">
      <div class="text-sm font-semibold">{{ $isArchived ? 'Archived Admins' : 'Admins' }}</div>
      <div class="mt-1 text-xs text-slate-500">Admin accounts only</div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
          <tr>
            <th class="px-5 py-3">Admin</th>
            <th class="px-5 py-3">Status</th>
            <th class="px-5 py-3">Joined</th>
            <th class="px-5 py-3">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-200">
          @forelse($admins as $a)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-4">
                <div class="font-semibold text-slate-900">{{ $a->name ?? ($a->first_name.' '.$a->last_name) }}</div>
                <div class="text-xs text-slate-500">{{ $a->email }}</div>
              </td>

              <td class="px-5 py-4">
                @php $active = (bool) ($a->is_active ?? true); @endphp
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                  {{ $active ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-rose-50 text-rose-700 ring-rose-200' }}">
                  {{ $active ? 'Active' : 'Disabled' }}
                </span>

                @if($a->archived_at)
                  <div class="mt-1 text-[11px] text-slate-500">
                    Archived: {{ optional($a->archived_at)->format('Y-m-d') }}
                  </div>
                @endif
              </td>

              <td class="px-5 py-4 text-slate-700">
                {{ optional($a->created_at)->format('Y-m-d') ?? '—' }}
              </td>

              <td class="px-5 py-4">
                <div class="flex flex-wrap gap-2">

                  @if($isArchived)
                    {{-- Restore --}}
                    <form method="POST" action="{{ route('admin.admins.restore', $a) }}"
                          onsubmit="return confirm('Restore this admin?')">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                        class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                        Restore
                      </button>
                    </form>
                  @else
                    <a href="{{ route('admin.admins.edit', $a) }}"
                      class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                      Edit
                    </a>

                    {{-- Toggle --}}
                    <form method="POST" action="{{ route('admin.admins.toggle', $a) }}"
                          onsubmit="return confirm('{{ ($a->is_active ?? true) ? 'Disable' : 'Enable' }} this admin?')">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                        class="rounded-xl px-3 py-2 text-xs font-semibold ring-1
                        {{ ($a->is_active ?? true)
                            ? 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'
                            : 'bg-emerald-600 text-white ring-emerald-600 hover:bg-emerald-700' }}">
                        {{ ($a->is_active ?? true) ? 'Disable' : 'Enable' }}
                      </button>
                    </form>

                    {{-- Reset password (NOTE: needs password_confirmation in controller validation if confirmed) --}}
                    <form method="POST" action="{{ route('admin.admins.reset_password', $a) }}"
                          onsubmit="return confirm('Reset password for this admin?')">
                      @csrf
                      <input type="hidden" name="password" value="Admin12345">
                      <input type="hidden" name="password_confirmation" value="Admin12345">
                      <button type="submit"
                        class="rounded-xl bg-amber-600 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-700">
                        Reset PW
                      </button>
                    </form>

                    {{-- Archive --}}
                    <form method="POST" action="{{ route('admin.admins.archive', $a) }}"
                          onsubmit="return confirm('Archive this admin?')">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                        class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                        Archive
                      </button>
                    </form>
                  @endif

                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-500">
                {{ $isArchived ? 'No archived admins found.' : 'No admins found.' }}
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-slate-200 p-4">
      {{ $admins->links() }}
    </div>
  </div>

</div>
@endsection
