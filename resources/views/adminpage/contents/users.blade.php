@extends('adminpage.layout')
@section('title','Users')
@section('page_title','Manage Users')

@section('content')
@php
  $stats = [
    ['label'=>'Total users','value'=>'2,314'],
    ['label'=>'Active employers','value'=>'128'],
    ['label'=>'Employers for approval','value'=>'6'],
    ['label'=>'Suspended (billing)','value'=>'14'],
  ];

  // Sample data (replace later with DB)
  $users = [
    [
      'id'=>1,'name'=>'John Doe','email'=>'john@example.com',
      'role'=>'Candidate','status'=>'Active','plan'=>null,'payment'=>null,'joined'=>'2026-01-20',
      'jobs_posted'=>0,
      'applications_count'=>7,
    ],
    [
      'id'=>2,'name'=>'ACME Corp','email'=>'hr@acme.com',
      'role'=>'Employer','status'=>'Pending','plan'=>'Free','payment'=>'Pending','joined'=>'2026-02-01',
      'jobs_posted'=>3,
      'applications_count'=>0,
    ],
    [
      'id'=>3,'name'=>'QuickShip PH','email'=>'admin@quickship.ph',
      'role'=>'Employer','status'=>'Active','plan'=>'Pro','payment'=>'Completed','joined'=>'2026-01-05',
      'jobs_posted'=>12,
      'applications_count'=>0,
    ],
    [
      'id'=>4,'name'=>'Maria Santos','email'=>'maria@gmail.com',
      'role'=>'Candidate','status'=>'Active','plan'=>null,'payment'=>null,'joined'=>'2026-01-11',
      'jobs_posted'=>0,
      'applications_count'=>2,
    ],
    [
      'id'=>5,'name'=>'TechTalent Hub','email'=>'ops@techtalent.io',
      'role'=>'Employer','status'=>'Expired','plan'=>'Pro','payment'=>'Failed','joined'=>'2025-12-29',
      'jobs_posted'=>21,
      'applications_count'=>0,
    ],
  ];
@endphp

<div
  x-data="manageUsers(@js($users))"
  x-init="init()"
  class="space-y-6"
>

  {{-- Top quick stats --}}
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach($stats as $s)
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="text-sm text-slate-500">{{ $s['label'] }}</div>
        <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $s['value'] }}</div>
        <div class="mt-3 h-2 w-full rounded-full bg-slate-100">
          <div class="h-2 w-2/3 rounded-full bg-emerald-600"></div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Controls --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
      <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <div class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-80">
          <span class="text-slate-400">⌕</span>
          <input
            x-model="filters.q"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
            placeholder="Search by name or email"
          />
        </div>

        <select
          x-model="filters.role"
          class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
        >
          <option value="All">All roles</option>
          <option value="Candidate">Candidate</option>
          <option value="Employer">Employer</option>
        </select>

        <select
          x-model="filters.status"
          class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
        >
          <option value="All">All statuses</option>
          <option value="Active">Active</option>
          <option value="Pending">Pending</option>
          <option value="Suspended">Suspended</option>
          <option value="Expired">Expired</option>
          <option value="Inactive">Inactive</option>
        </select>

        <select
          x-model="filters.payment"
          class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
        >
          <option value="All">All payments</option>
          <option value="Completed">Completed</option>
          <option value="Pending">Pending</option>
          <option value="Failed">Failed</option>
          <option value="—">No payment</option>
        </select>
      </div>

      <div class="flex flex-wrap gap-2">
        <button
          type="button"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50"
          @click="toast('info','Export (UI only)')"
        >
          Export
        </button>

        <button
          type="button"
          class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
          @click="toast('info','Add user (UI only)')"
        >
          Add user
        </button>
      </div>
    </div>
  </div>

  {{-- Table + details panel --}}
  <div class="grid grid-cols-1 gap-4 xl:grid-cols-5">

    {{-- Users table --}}
    <div class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold">Users</div>
        <div class="mt-1 text-xs text-slate-500">Click a row to view details and take action</div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
            <tr>
              <th class="px-5 py-3">User</th>
              <th class="px-5 py-3">Role</th>
              <th class="px-5 py-3">Status</th>
              <th class="px-5 py-3">Plan</th>
              <th class="px-5 py-3">Payment</th>
              <th class="px-5 py-3">Joined</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-200">
            <template x-for="u in filteredUsers()" :key="u.id">
              <tr
                class="cursor-pointer hover:bg-slate-50"
                :class="selected?.id === u.id ? 'bg-emerald-50/40' : ''"
                @click="select(u)"
              >
                <td class="px-5 py-4">
                  <div class="font-semibold text-slate-900" x-text="u.name"></div>
                  <div class="text-xs text-slate-500" x-text="u.email"></div>
                </td>

                <td class="px-5 py-4 text-slate-700" x-text="u.role"></td>

                <td class="px-5 py-4">
                  <span
                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1"
                    :class="statusPill(u.status)"
                    x-text="u.status"
                  ></span>
                </td>

                <td class="px-5 py-4 text-slate-700" x-text="u.plan ?? '—'"></td>

                <td class="px-5 py-4">
                  <span
                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1"
                    :class="paymentPill(u.payment)"
                    x-text="u.payment ?? '—'"
                  ></span>
                </td>

                <td class="px-5 py-4 text-slate-700" x-text="u.joined"></td>
              </tr>
            </template>

            <tr x-show="filteredUsers().length === 0">
              <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">
                No results.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="flex flex-col gap-2 border-t border-slate-200 p-4 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between">
        <span>Showing <span x-text="Math.min(filteredUsers().length, 10)"></span> of <span x-text="filteredUsers().length"></span></span>
        <div class="flex gap-2">
          <button class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold hover:bg-slate-50" @click="toast('info','Prev (UI only)')">Prev</button>
          <button class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold hover:bg-slate-50" @click="toast('info','Next (UI only)')">Next</button>
        </div>
      </div>
    </div>

    {{-- Details panel --}}
    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex items-start justify-between">
        <div>
          <div class="text-sm font-semibold">User details</div>
          <div class="mt-1 text-xs text-slate-500">Preview and actions (UI only)</div>
        </div>

        <template x-if="selected">
          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1"
                :class="statusPill(selected.status)">
            <span x-text="selected.status"></span>
          </span>
        </template>
      </div>

      {{-- Placeholder when none selected --}}
      <div x-show="!selected" class="mt-10 rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center">
        <div class="text-sm font-semibold text-slate-800">Select a user</div>
        <div class="mt-1 text-xs text-slate-500">Pick a row from the table to see more.</div>
      </div>

      {{-- Selected content --}}
      <div x-show="selected" x-transition class="mt-5">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-sm font-semibold text-slate-900" x-text="selected?.name"></div>
          <div class="mt-1 text-xs text-slate-600" x-text="selected?.email"></div>

          <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
            <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
              <div class="text-xs text-slate-500">Role</div>
              <div class="mt-1 font-semibold" x-text="selected?.role"></div>
            </div>

            <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
              <div class="text-xs text-slate-500">Joined</div>
              <div class="mt-1 font-semibold" x-text="selected?.joined"></div>
            </div>

            {{-- Employer-only --}}
            <div x-show="selected?.role === 'Employer'" class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
              <div class="text-xs text-slate-500">Jobs posted</div>
              <div class="mt-1 font-semibold" x-text="selected?.jobs_posted ?? 0"></div>
            </div>

            <div x-show="selected?.role === 'Employer'" class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
              <div class="text-xs text-slate-500">Plan</div>
              <div class="mt-1 font-semibold" x-text="selected?.plan ?? '—'"></div>
            </div>

            <div x-show="selected?.role === 'Employer'" class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
              <div class="text-xs text-slate-500">Payment</div>
              <div class="mt-1 font-semibold" x-text="selected?.payment ?? '—'"></div>
            </div>

            {{-- Candidate-only --}}
            <div x-show="selected?.role === 'Candidate'" class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
              <div class="text-xs text-slate-500">Applications</div>
              <div class="mt-1 font-semibold" x-text="selected?.applications_count ?? 0"></div>
            </div>
          </div>
        </div>

        {{-- Actions --}}
        <div class="mt-5 space-y-2">

          {{-- Approve employer only when Pending employer --}}
          <button
            x-show="selected?.role==='Employer' && selected?.status==='Pending'"
            @click="approveEmployer()"
            class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
            type="button"
          >
            Approve employer
          </button>

          {{-- Activate --}}
          <button
            x-show="selected?.status!=='Active' && !(selected?.role==='Employer' && selected?.status==='Pending')"
            @click="activateUser()"
            class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
            type="button"
          >
            Set to active
          </button>

          <button
            x-show="selected?.status!=='Suspended'"
            @click="suspendUser()"
            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50"
            type="button"
          >
            Suspend
          </button>

          <button
            @click="deactivateUser()"
            class="w-full rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700"
            type="button"
          >
            Deactivate
          </button>
        </div>

        {{-- Notes --}}
        <div class="mt-5 rounded-2xl border border-slate-200 p-4">
          <div class="text-sm font-semibold">Notes</div>
          <textarea
            class="mt-2 h-24 w-full rounded-xl border border-slate-200 bg-white p-3 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
            placeholder="Internal notes (optional)"
          ></textarea>
          <button
            class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50"
            type="button"
            @click="toast('success','Notes saved (UI only)')"
          >
            Save notes
          </button>
        </div>
      </div>
    </div>

  </div>

</div>

{{-- Alpine component --}}
<script>
  function manageUsers(initialUsers){
    return {
      users: initialUsers,
      selected: null,
      filters: { q: '', role: 'All', status: 'All', payment: 'All' },

      
      toast(type, msg, title = ''){
        if (!window.notify) return;
        const allowed = ['success','info','warning','error'];
        const safeType = allowed.includes(type) ? type : 'info';
        window.notify(safeType, String(msg || ''), String(title || ''));
      },

      

      select(u){
        this.selected = JSON.parse(JSON.stringify(u));
        this.toast('info', 'Selected: ' + (this.selected?.name || 'User'));
      },

      statusPill(s){
        if(s === 'Active') return 'bg-emerald-50 text-emerald-700 ring-emerald-200';
        if(s === 'Pending') return 'bg-amber-50 text-amber-700 ring-amber-200';
        if(s === 'Suspended') return 'bg-rose-50 text-rose-700 ring-rose-200';
        if(s === 'Expired') return 'bg-slate-100 text-slate-700 ring-slate-200';
        if(s === 'Inactive') return 'bg-slate-100 text-slate-700 ring-slate-200';
        return 'bg-slate-100 text-slate-700 ring-slate-200';
      },

      paymentPill(p){
        if(p === 'Completed') return 'bg-emerald-50 text-emerald-700 ring-emerald-200';
        if(p === 'Pending') return 'bg-amber-50 text-amber-700 ring-amber-200';
        if(p === 'Failed') return 'bg-rose-50 text-rose-700 ring-rose-200';
        return 'bg-slate-100 text-slate-700 ring-slate-200';
      },

      filteredUsers(){
        const q = (this.filters.q || '').toLowerCase().trim();
        return this.users.filter(u => {
          const matchesQ = !q || (u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q));
          const matchesRole = this.filters.role === 'All' || u.role === this.filters.role;
          const matchesStatus = this.filters.status === 'All' || u.status === this.filters.status;
          const pay = u.payment ?? '—';
          const matchesPayment = this.filters.payment === 'All' || pay === this.filters.payment;
          return matchesQ && matchesRole && matchesStatus && matchesPayment;
        });
      },

      syncSelectedToTable(){
        if(!this.selected) return;
        const idx = this.users.findIndex(x => x.id === this.selected.id);
        if(idx !== -1) this.users[idx] = JSON.parse(JSON.stringify(this.selected));
      },

      approveEmployer(){
        if(!this.selected) return;

        // UI demo update
        this.selected.status = 'Active';
        this.selected.payment = this.selected.payment ?? 'Completed';
        this.syncSelectedToTable();

        this.toast('success', 'Employer approved (UI only)');
      },

      activateUser(){
        if(!this.selected) return;

        this.selected.status = 'Active';
        this.syncSelectedToTable();

        this.toast('success', 'User set to active (UI only)');
      },

      suspendUser(){
        if(!this.selected) return;

        this.selected.status = 'Suspended';
        this.syncSelectedToTable();

        this.toast('warning', 'User suspended (UI only)');
      },

      deactivateUser(){
        if(!this.selected) return;

        if(!confirm('Deactivate this user?')) {
          this.toast('info', 'Cancelled');
          return;
        }

        this.selected.status = 'Inactive';
        this.syncSelectedToTable();

        this.toast('error', 'User deactivated (UI only)');
      },
    }
  }
</script>

@endsection
