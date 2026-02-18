@extends('adminpage.layout')
@section('title','Admin Accounts')
@section('page_title','Admin Accounts')

@section('content')
@php
  // expects: $admins, $q
  $isArchived = request('archived') === '1';
@endphp

<div class="space-y-6" x-data="adminsUI()" x-cloak>

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
            @php
              $adminName = $a->name ?? trim(($a->first_name ?? '').' '.($a->last_name ?? ''));
              $accStatus = $a->account_status ?? 'active';
              $active = ($accStatus === 'active');
            @endphp

            <tr class="hover:bg-slate-50">
              <td class="px-5 py-4">
                <div class="font-semibold text-slate-900">{{ $adminName }}</div>
                <div class="text-xs text-slate-500">{{ $a->email }}</div>
              </td>

              <td class="px-5 py-4">
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                  {{ $accStatus === 'active'
                      ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                      : ($accStatus === 'hold'
                          ? 'bg-amber-50 text-amber-800 ring-amber-200'
                          : 'bg-rose-50 text-rose-700 ring-rose-200') }}">
                  {{ $accStatus === 'active' ? 'Active' : ($accStatus === 'hold' ? 'On hold' : 'Disabled') }}
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
                    <button type="button"
                      @click="openRestore({{ $a->id }}, @js($adminName))"
                      class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                      Restore
                    </button>
                  @else
                    <a href="{{ route('admin.admins.edit', $a) }}"
                      class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                      Edit
                    </a>

                    <button type="button"
                      @click="openToggle({{ $a->id }}, @js($adminName), {{ $active ? 'true' : 'false' }})"
                      class="rounded-xl px-3 py-2 text-xs font-semibold ring-1
                      {{ $active
                          ? 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'
                          : 'bg-emerald-600 text-white ring-emerald-600 hover:bg-emerald-700' }}">
                      {{ $active ? 'Disable' : 'Enable' }}
                    </button>

                    <button type="button"
                      @click="openResetPw({{ $a->id }}, @js($adminName))"
                      class="rounded-xl bg-amber-600 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-700">
                      Reset PW
                    </button>

                    <button type="button"
                      @click="openArchive({{ $a->id }}, @js($adminName))"
                      class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                      Archive
                    </button>
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

  {{-- TOGGLE (ENABLE/DISABLE) MODAL --}}
  <div x-show="toggleOpen" class="fixed inset-0 z-50" x-transition.opacity @keydown.escape.window="closeAll()">
    <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>

    <div class="relative mx-auto w-[92%] max-w-lg" :class="isMobile ? 'mt-auto mb-4' : 'mt-16'">
      <div class="rounded-2xl bg-white p-5 shadow-xl" x-trap.noscroll="toggleOpen">
        <div class="text-sm font-semibold text-slate-900">
          <span x-text="toggleNextActive ? 'Enable admin' : 'Disable admin'"></span>
        </div>

        <div class="mt-1 text-sm text-slate-600">
          Are you sure you want to
          <span class="font-semibold" x-text="toggleNextActive ? 'enable' : 'disable'"></span>
          <span class="font-semibold" x-text="selectedName"></span>?
        </div>

        <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
          <button type="button" @click="closeAll()"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
            Cancel
          </button>

          <form method="POST" :action="toggleAction">
            @csrf
            @method('PATCH')
            <button type="submit"
              class="rounded-xl px-4 py-2 text-sm font-semibold text-white"
              :class="toggleNextActive ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700'">
              <span x-text="toggleNextActive ? 'Enable' : 'Disable'"></span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- ARCHIVE MODAL --}}
  <div x-show="archiveOpen" class="fixed inset-0 z-50" x-transition.opacity @keydown.escape.window="closeAll()">
    <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>

    <div class="relative mx-auto w-[92%] max-w-lg" :class="isMobile ? 'mt-auto mb-4' : 'mt-16'">
      <div class="rounded-2xl bg-white p-5 shadow-xl" x-trap.noscroll="archiveOpen">
        <div class="text-sm font-semibold text-slate-900">Archive admin</div>
        <div class="mt-1 text-sm text-slate-600">
          This will move <span class="font-semibold" x-text="selectedName"></span> to Archived.
        </div>

        <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
          <button type="button" @click="closeAll()"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
            Cancel
          </button>

          <form method="POST" :action="archiveAction">
            @csrf
            @method('PATCH')
            <button type="submit"
              class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
              Archive
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- RESTORE MODAL --}}
  <div x-show="restoreOpen" class="fixed inset-0 z-50" x-transition.opacity @keydown.escape.window="closeAll()">
    <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>

    <div class="relative mx-auto w-[92%] max-w-lg" :class="isMobile ? 'mt-auto mb-4' : 'mt-16'">
      <div class="rounded-2xl bg-white p-5 shadow-xl" x-trap.noscroll="restoreOpen">
        <div class="text-sm font-semibold text-slate-900">Restore admin</div>
        <div class="mt-1 text-sm text-slate-600">
          Restore <span class="font-semibold" x-text="selectedName"></span> back to Active list?
        </div>

        <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
          <button type="button" @click="closeAll()"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
            Cancel
          </button>

          <form method="POST" :action="restoreAction">
            @csrf
            @method('PATCH')
            <button type="submit"
              class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
              Restore
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- RESET PASSWORD MODAL --}}
  <div x-show="resetOpen" class="fixed inset-0 z-50" x-transition.opacity @keydown.escape.window="closeAll()">
    <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>

    <div class="relative mx-auto w-[92%] max-w-lg" :class="isMobile ? 'mt-auto mb-4' : 'mt-16'">
      <div class="rounded-2xl bg-white p-5 shadow-xl" x-trap.noscroll="resetOpen">
        <div class="text-sm font-semibold text-slate-900">Reset password</div>
        <div class="mt-1 text-sm text-slate-600">
          This will reset the password for <span class="font-semibold" x-text="selectedName"></span>.
        </div>

        <div class="mt-4">
          <label class="text-xs font-semibold text-slate-700">New password</label>

          <div class="relative mt-1">
            <input :type="showPw ? 'text' : 'password'" x-model="newPassword" autocomplete="new-password"
              class="w-full rounded-xl border border-slate-200 px-3 py-2 pr-16 text-sm focus:border-amber-400 focus:ring-4 focus:ring-amber-100"
              placeholder="Enter new password (min 8 chars)">

            <button type="button"
              class="absolute inset-y-0 right-2 my-1 rounded-lg px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50"
              @click="showPw = !showPw"
              x-text="showPw ? 'Hide' : 'Show'">
            </button>
          </div>

          <div class="mt-2 text-[11px] text-rose-600" x-show="pwError">
            Please enter at least 8 characters.
          </div>
        </div>

        <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
          <button type="button" @click="closeAll()"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
            Cancel
          </button>

          <form method="POST" :action="resetAction" @submit.prevent="submitReset($event)">
            @csrf
            <input type="hidden" name="password" :value="newPassword">
            <input type="hidden" name="password_confirmation" :value="newPassword">
            <button type="submit"
              class="rounded-xl bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">
              Reset
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
function adminsUI(){
  return {
    toggleOpen:false,
    archiveOpen:false,
    restoreOpen:false,
    resetOpen:false,

    selectedId:null,
    selectedName:'',

    toggleNextActive:true,

    // Reset PW state
    newPassword:'',
    pwError:false,
    showPw:false,

    get isMobile(){
      return window.matchMedia && window.matchMedia('(max-width: 640px)').matches;
    },

    get toggleAction(){
      return this.selectedId ? `/admin/admins/${this.selectedId}/toggle` : '#';
    },
    get archiveAction(){
      return this.selectedId ? `/admin/admins/${this.selectedId}/archive` : '#';
    },
    get restoreAction(){
      return this.selectedId ? `/admin/admins/${this.selectedId}/restore` : '#';
    },
    get resetAction(){
      return this.selectedId ? `/admin/admins/${this.selectedId}/reset-password` : '#';
    },

    closeAll(){
      this.toggleOpen=false;
      this.archiveOpen=false;
      this.restoreOpen=false;
      this.resetOpen=false;
      this.pwError=false;
      this.showPw=false;
    },

    openToggle(id, name, isCurrentlyActive){
      this.selectedId = id;
      this.selectedName = name || 'this admin';
      this.toggleNextActive = !Boolean(isCurrentlyActive);
      this.closeAll();
      this.toggleOpen = true;
    },

    openArchive(id, name){
      this.selectedId = id;
      this.selectedName = name || 'this admin';
      this.closeAll();
      this.archiveOpen = true;
    },

    openRestore(id, name){
      this.selectedId = id;
      this.selectedName = name || 'this admin';
      this.closeAll();
      this.restoreOpen = true;
    },

    openResetPw(id, name){
      this.selectedId = id;
      this.selectedName = name || 'this admin';
      this.newPassword = '';
      this.pwError = false;
      this.showPw = false;
      this.closeAll();
      this.resetOpen = true;
    },

    submitReset(e){
      const pw = (this.newPassword || '').trim();
      if(pw.length < 8){
        this.pwError = true;
        return;
      }
      this.pwError = false;
      e.target.submit();
    }
  }
}
</script>

@endsection
