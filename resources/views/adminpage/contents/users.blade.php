@extends('adminpage.layout')
@section('title', 'Users')
@section('page_title', 'Manage Users')

@section('content')
  @php
    $sub_plan = $sub_plan ?? '';
    $sub_status = $sub_status ?? '';
    $arch = $archived ?? '0';

    // ✅ default role is candidate (matches controller)
    $role = $role ?? 'candidate';
    if (!in_array($role, ['candidate', 'employer'], true))
      $role = 'candidate';

    $isEmployerTable = ($role === 'employer');
  @endphp

  <div class="space-y-6" x-data="usersUI()" x-cloak>

    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <form method="GET" action="{{ route('admin.users.index') }}"
        class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

        <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">

          {{-- Search --}}
          <div class="w-full sm:w-96">
            <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
              <span class="text-slate-400">⌕</span>
              <input name="q" value="{{ $q ?? '' }}"
                class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                placeholder="{{ $isEmployerTable ? 'Search employers by company/name/email' : 'Search candidates by name/email' }}" />
            </div>
          </div>

          {{-- Role (auto submit on change so it switches instantly) --}}
          <select name="role" onchange="this.form.submit()"
            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
            <option value="candidate" {{ $role === 'candidate' ? 'selected' : '' }}>Candidate</option>
            <option value="employer" {{ $role === 'employer' ? 'selected' : '' }}>Employer</option>
          </select>

          {{-- ✅ Candidate-only filter (only render if candidate table) --}}
          @if(!$isEmployerTable)
            <select name="verified"
              class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
              <option value="" {{ empty($verified) ? 'selected' : '' }}>Candidate verification</option>
              <option value="verified" {{ ($verified ?? '') === 'verified' ? 'selected' : '' }}>Verified</option>
              <option value="unverified" {{ ($verified ?? '') === 'unverified' ? 'selected' : '' }}>Not verified</option>
            </select>
          @endif

          {{-- ✅ Employer-only filters (only render if employer table) --}}
          @if($isEmployerTable)
            <select name="sub_plan"
              class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
              <option value="" {{ $sub_plan === '' ? 'selected' : '' }}>Plan</option>
              <option value="standard" {{ $sub_plan === 'standard' ? 'selected' : '' }}>Standard</option>
              <option value="gold" {{ $sub_plan === 'gold' ? 'selected' : '' }}>Gold</option>
              <option value="platinum" {{ $sub_plan === 'platinum' ? 'selected' : '' }}>Platinum</option>
            </select>

            <select name="sub_status"
              class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
              <option value="" {{ $sub_status === '' ? 'selected' : '' }}>Sub status</option>
              <option value="inactive" {{ $sub_status === 'inactive' ? 'selected' : '' }}>Inactive</option>
              <option value="active" {{ $sub_status === 'active' ? 'selected' : '' }}>Active</option>
              <option value="expired" {{ $sub_status === 'expired' ? 'selected' : '' }}>Expired</option>
              <option value="canceled" {{ $sub_status === 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
          @endif

          {{-- Archived --}}
          <select name="archived"
            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
            <option value="0" {{ $arch === '0' ? 'selected' : '' }}>Active users</option>
            <option value="1" {{ $arch === '1' ? 'selected' : '' }}>Archived users</option>
          </select>

          <button type="submit"
            class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
            Apply
          </button>

          {{-- Reset keeps the current role --}}
          <a href="{{ route('admin.users.index', ['role' => $role]) }}"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
            Reset
          </a>
        </div>
      </form>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold">
          {{ $isEmployerTable ? 'Employers' : 'Candidates' }}
        </div>
        <div class="mt-1 text-xs text-slate-500">
          Showing {{ $isEmployerTable ? 'Employers (with subscription)' : 'Candidates (no subscription column)' }}
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-slate-50 text-xs font-semibold text-slate-600">
            <tr>
              <th class="px-5 py-3">User</th>
              <th class="px-5 py-3">Status</th>

              {{-- ✅ Show subscription column ONLY on employer table --}}
              @if($isEmployerTable)
                <th class="px-5 py-3">Subscription</th>
              @endif

              <th class="px-5 py-3">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-200">
            @forelse($users as $u)
                    @php
                      $name = $u->name ?? trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? ''));
                      $accStatus = $u->account_status ?? 'active';
                      $isActive = ($accStatus === 'active');
                      $isVerified = !is_null($u->email_verified_at);

                      $empStatus = null;
                      $plan = null;
                      $subStatus = null;
                      $subStarts = null;
                      $subEnds = null;

                      if ($u->role === 'employer') {
                        $ep = $u->employerProfile;
                        $empStatus = $ep?->verification?->status ?? 'pending';
                        $plan = $ep?->subscription?->plan;
                        $subStatus = $ep?->subscription?->subscription_status;
                        $subStarts = $ep?->subscription?->starts_at;
                        $subEnds = $ep?->subscription?->ends_at;
                      }

                      $planLabel = $plan ? ucfirst($plan) : '—';
                      $subStatusLabel = $subStatus ? ucfirst($subStatus) : '—';

                      $displayName = $u->role === 'employer'
                        ? ($u->employerProfile?->company_name ?: $name)
                        : $name;

                      // pass to modal
                      $payload = [
                        'id' => $u->id,
                        'name' => $displayName,
                        'role' => $u->role,
                        'archived' => ($archived ?? '0'),
                        'accStatus' => $accStatus,
                        'isActive' => $isActive,
                        'isVerified' => $isVerified,
                        'empStatus' => $empStatus,
                        'plan' => $plan,
                        'subStatus' => $subStatus,
                        'subStarts' => $subStarts ? $subStarts->format('Y-m-d') : '',
                        'subEnds' => $subEnds ? $subEnds->format('Y-m-d') : '',
                      ];
                    @endphp

                    <tr class="hover:bg-slate-50">
                      <td class="px-5 py-4">
                        <div class="font-semibold text-slate-900">{{ $displayName }}</div>
                        <div class="text-xs text-slate-500">{{ $u->email }}</div>
                      </td>

                      <td class="px-5 py-4">
                        <div class="flex flex-wrap gap-2">
                          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                                          {{ $accStatus === 'active'
              ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
              : ($accStatus === 'hold'
                ? 'bg-amber-50 text-amber-800 ring-amber-200'
                : 'bg-rose-50 text-rose-700 ring-rose-200') }}">
                            {{ $accStatus === 'active' ? 'Active' : ($accStatus === 'hold' ? 'On hold' : 'Disabled') }}
                          </span>

                          @if($u->role === 'candidate')
                            <span
                              class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                                                {{ $isVerified ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                              {{ $isVerified ? 'Verified' : 'Not verified' }}
                            </span>
                          @endif

                          @if($u->role === 'employer')
                                      <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                                                                    {{ $empStatus === 'approved'
                            ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                            : ($empStatus === 'rejected'
                              ? 'bg-rose-50 text-rose-700 ring-rose-200'
                              : ($empStatus === 'suspended'
                                ? 'bg-slate-900 text-white ring-slate-900'
                                : 'bg-amber-50 text-amber-800 ring-amber-200')) }}">
                                        {{ ucfirst($empStatus ?? 'pending') }}
                                      </span>
                          @endif
                        </div>
                      </td>

                      {{-- ✅ Subscription column ONLY on employer table --}}
                      @if($isEmployerTable)
                              <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                  <span
                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                                                            {{ $plan ? 'bg-slate-50 text-slate-700 ring-slate-200' : 'bg-white text-slate-400 ring-slate-200' }}">
                                    Plan: {{ $planLabel }}
                                  </span>

                                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                                                            {{ ($subStatus === 'active')
                        ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                        : (($subStatus === 'expired')
                          ? 'bg-amber-50 text-amber-800 ring-amber-200'
                          : (($subStatus === 'canceled')
                            ? 'bg-rose-50 text-rose-700 ring-rose-200'
                            : 'bg-slate-50 text-slate-700 ring-slate-200')) }}">
                                    Sub: {{ $subStatusLabel }}
                                  </span>

                                  <span
                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                                                            {{ $subEnds ? 'bg-slate-50 text-slate-700 ring-slate-200' : 'bg-white text-slate-400 ring-slate-200' }}">
                                    Ends: {{ $subEnds ? $subEnds->format('Y-m-d') : '—' }}
                                  </span>
                                </div>
                              </td>
                      @endif

                      <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                          <a href="{{ route('admin.users.show', $u) }}"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            View
                          </a>

                          <button type="button" @click="openActions(@js($payload))"
                            class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                            Actions
                          </button>
                        </div>
                      </td>
                    </tr>
            @empty
              <tr>
                <td colspan="{{ $isEmployerTable ? 4 : 3 }}" class="px-5 py-10 text-center text-sm text-slate-500">
                  No results.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="border-t border-slate-200 p-4">
        {{ $users->links() }}
      </div>
    </div>



    {{-- Keep your existing modals (approve/reject/suspend/archive/toggle/subscription) --}}
    @include('adminpage.contents.partials.users.modals')

  </div>

  <script>
    function usersUI() {
      return {
        // existing modals
        approveOpen: false,
        rejectOpen: false,
        suspendOpen: false,
        archiveOpen: false,
        toggleOpen: false,
        subOpen: false,

        // ✅ new actions modal
        actionsOpen: false,

        selectedId: null,
        selectedName: '',
        selectedRole: '',
        selectedArchived: '0',
        selectedEmpStatus: null,

        selectedPlan: '',
        selectedSubStatus: '',
        selectedSubStarts: '',
        selectedSubEnds: '',

        toggleNextActive: true,

        // reject/suspend states
        rejectReason: '',
        rejectError: false,

        suspendReason: '',
        suspendError: false,
        suspendAlsoHold: false,

        archiveMode: 'archive',

        // subscription modal state
        subPlan: '',
        subStatus: 'inactive',
        subStarts: '',
        subEnds: '',
        subAlsoHold: false,

        get isMobile() {
          return window.matchMedia && window.matchMedia('(max-width: 640px)').matches;
        },

        // routes
        get approveAction() { return this.selectedId ? `/admin/users/${this.selectedId}/approve` : '#'; },
        get rejectAction() { return this.selectedId ? `/admin/users/${this.selectedId}/reject` : '#'; },
        get suspendAction() { return this.selectedId ? `/admin/users/${this.selectedId}/suspend` : '#'; },
        get unsuspendAction() { return this.selectedId ? `/admin/users/${this.selectedId}/unsuspend` : '#'; },
        get toggleAction() { return this.selectedId ? `/admin/users/${this.selectedId}/toggle` : '#'; },
        get archiveAction() {
          if (!this.selectedId) return '#';
          return this.archiveMode === 'archive'
            ? `/admin/users/${this.selectedId}/archive`
            : `/admin/users/${this.selectedId}/restore`;
        },
        get subAction() { return this.selectedId ? `/admin/users/${this.selectedId}/subscription` : '#'; },

        closeAll() {
          this.approveOpen = false;
          this.rejectOpen = false;
          this.suspendOpen = false;
          this.archiveOpen = false;
          this.toggleOpen = false;
          this.subOpen = false;
          this.actionsOpen = false;

          this.rejectError = false;
          this.suspendError = false;
        },

        // ✅ actions modal open
        openActions(payload) {
          this.selectedId = payload.id;
          this.selectedName = payload.name || 'this user';
          this.selectedRole = payload.role || '';
          this.selectedArchived = payload.archived ?? '0';
          this.selectedEmpStatus = payload.empStatus ?? null;

          this.selectedPlan = payload.plan || '';
          this.selectedSubStatus = payload.subStatus || '';
          this.selectedSubStarts = payload.subStarts || '';
          this.selectedSubEnds = payload.subEnds || '';

          // enable/disable text
          this.toggleNextActive = !(payload.isActive === true);

          this.closeAll();
          this.actionsOpen = true;
        },

        // Optional: you can keep your confirm modal, but this lets the actions modal submit directly
        openToggleConfirm(e) {
          // if you still want the old confirm modal, use your existing openToggle here
          e.target.submit();
        },

        // existing methods (keep yours)
        openReject(id, name) {
          this.selectedId = id;
          this.selectedName = name || 'this employer';
          this.rejectReason = '';
          this.rejectError = false;
          this.closeAll();
          this.rejectOpen = true;
        },

        openSuspend(id, name) {
          this.selectedId = id;
          this.selectedName = name || 'this employer';
          this.suspendReason = '';
          this.suspendAlsoHold = true;
          this.suspendError = false;
          this.closeAll();
          this.suspendOpen = true;
        },

        openArchive(id, name, mode) {
          this.selectedId = id;
          this.selectedName = name || 'this user';
          this.archiveMode = (mode === 'restore') ? 'restore' : 'archive';
          this.closeAll();
          this.archiveOpen = true;
        },

        submitReject(e) {
          // reset error
          this.rejectError = false;

          // validate
          if (!this.rejectReason || this.rejectReason.trim().length < 3) {
            this.rejectError = true;
            return;
          }

          // submit the form normally
          e.target.submit();
        },

        submitSuspend(e) {
          // reset error
          this.suspendError = false;

          // validate
          if (!this.suspendReason || this.suspendReason.trim().length < 3) {
            this.suspendError = true;
            return;
          }

          // submit the form normally
          e.target.submit();
        },

        openSubscription(id, name, plan, status, startsAt, endsAt) {
          this.selectedId = id;
          this.selectedName = name || 'this employer';
          this.subPlan = plan || '';
          this.subStatus = status || 'inactive';
          this.subStarts = startsAt || '';
          this.subEnds = endsAt || '';
          this.subAlsoHold = false;
          this.closeAll();
          this.subOpen = true;
        },
      }
    }
  </script>
@endsection