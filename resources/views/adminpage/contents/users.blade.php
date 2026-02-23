@extends('adminpage.layout')
@section('title', 'Users')
@section('page_title', 'Manage Users')

@section('content')
  @php
    // expects from controller:
    // $users, $q, $role, $verified, $archived
    // ✅ NEW:
    // $sub_plan, $sub_status
  @endphp

  <div class="space-y-6" x-data="usersUI()" x-cloak>

    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <form method="GET" action="{{ route('admin.users.index') }}"
        class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

        <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
          <div class="flex w-full items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 sm:w-80">
            <span class="text-slate-400">⌕</span>
            <input name="q" value="{{ $q ?? '' }}"
              class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
              placeholder="Search by name or email" />
          </div>

          <select name="role"
            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
            <option value="" {{ empty($role) ? 'selected' : '' }}>All roles</option>
            <option value="candidate" {{ ($role ?? '') === 'candidate' ? 'selected' : '' }}>Candidate</option>
            <option value="employer" {{ ($role ?? '') === 'employer' ? 'selected' : '' }}>Employer</option>
          </select>

          {{-- Candidate verification filter --}}
          <select name="verified"
            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
            <option value="" {{ empty($verified) ? 'selected' : '' }}>Candidate verification</option>
            <option value="verified" {{ ($verified ?? '') === 'verified' ? 'selected' : '' }}>Verified</option>
            <option value="unverified" {{ ($verified ?? '') === 'unverified' ? 'selected' : '' }}>Not verified</option>
          </select>

          {{-- ✅ NEW: Employer subscription filters --}}
          @php
            $sub_plan = $sub_plan ?? '';
            $sub_status = $sub_status ?? '';
          @endphp

          <select name="sub_plan"
            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
            <option value="" {{ $sub_plan === '' ? 'selected' : '' }}>Plan (employers)</option>
            <option value="standard" {{ $sub_plan === 'standard' ? 'selected' : '' }}>Standard</option>
            <option value="gold" {{ $sub_plan === 'gold' ? 'selected' : '' }}>Gold</option>
            <option value="platinum" {{ $sub_plan === 'platinum' ? 'selected' : '' }}>Platinum</option>
          </select>

          <select name="sub_status"
            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
            <option value="" {{ $sub_status === '' ? 'selected' : '' }}>Sub status (employers)</option>
            <option value="active" {{ $sub_status === 'active' ? 'selected' : '' }}>Active</option>
            <option value="expired" {{ $sub_status === 'expired' ? 'selected' : '' }}>Expired</option>
            <option value="suspended" {{ $sub_status === 'suspended' ? 'selected' : '' }}>Suspended</option>
            <option value="none" {{ $sub_status === 'none' ? 'selected' : '' }}>None</option>
          </select>

          {{-- Archived filter --}}
          <select name="archived"
            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
            @php $arch = $archived ?? '0'; @endphp
            <option value="0" {{ $arch === '0' ? 'selected' : '' }}>Active users</option>
            <option value="1" {{ $arch === '1' ? 'selected' : '' }}>Archived users</option>
          </select>

          <button type="submit"
            class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
            Apply
          </button>

          <a href="{{ route('admin.users.index') }}"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
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
              <th class="px-5 py-3">Subscription</th> {{-- ✅ NEW --}}
              <th class="px-5 py-3">Joined</th>
              <th class="px-5 py-3">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-200">
            @forelse($users as $u)
              @php
                $name = $u->name ?? trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? ''));
                $roleLabel = ucfirst($u->role);

                $accStatus = $u->account_status ?? 'active';
                $isActive = ($accStatus === 'active');

                $empStatus = null;
                $plan = null;
                $subStatus = null;
                $subStarts = null;
                $subEnds = null;

                if ($u->role === 'employer') {
                  $ep = $u->employerProfile;
                  $empStatus = optional($ep)->status ?? 'pending';

                  // ✅ NEW subscription fields (from employer_profiles)
                  $plan = optional($ep)->plan; // standard|gold|platinum|null
                  $subStatus = optional($ep)->subscription_status; // active|expired|suspended|none|null
                  $subStarts = optional($ep)->starts_at;
                  $subEnds = optional($ep)->ends_at;
                }

                $isVerified = !is_null($u->email_verified_at);

                // badges
                $planLabel = $plan ? ucfirst($plan) : '—';
                $subStatusLabel = $subStatus ? ucfirst($subStatus) : '—';
              @endphp

              <tr class="hover:bg-slate-50">
                <td class="px-5 py-4">
                  <div class="font-semibold text-slate-900">{{ $name }}</div>
                  <div class="text-xs text-slate-500">{{ $u->email }}</div>
                </td>

                <td class="px-5 py-4 text-slate-700">
                  {{ $roleLabel }}
                </td>

                <td class="px-5 py-4">
                  <div class="flex flex-wrap gap-2">
                    {{-- Account Status --}}
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                      {{ $accStatus === 'active'
                        ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                        : ($accStatus === 'hold'
                          ? 'bg-amber-50 text-amber-800 ring-amber-200'
                          : 'bg-rose-50 text-rose-700 ring-rose-200') }}">
                      {{ $accStatus === 'active' ? 'Active' : ($accStatus === 'hold' ? 'On hold' : 'Disabled') }}
                    </span>

                    {{-- Candidate: Email Verification --}}
                    @if($u->role === 'candidate')
                      <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                        {{ $isVerified ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                        {{ $isVerified ? 'Verified' : 'Not verified' }}
                      </span>
                    @endif

                    {{-- Employer: Approval Status --}}
                    @if($u->role === 'employer')
                      <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                        {{ $empStatus === 'approved'
                          ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                          : ($empStatus === 'rejected'
                            ? 'bg-rose-50 text-rose-700 ring-rose-200'
                            : 'bg-amber-50 text-amber-800 ring-amber-200') }}">
                        {{ ucfirst($empStatus ?? 'pending') }}
                      </span>
                    @endif
                  </div>
                </td>

                {{-- ✅ NEW: Subscription column --}}
                <td class="px-5 py-4">
                  @if($u->role !== 'employer')
                    <span class="text-slate-400">—</span>
                  @else
                    <div class="flex flex-col gap-1">
                      <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                          {{ $plan ? 'bg-slate-50 text-slate-700 ring-slate-200' : 'bg-white text-slate-400 ring-slate-200' }}">
                          Plan: {{ $planLabel }}
                        </span>

                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                          {{ ($subStatus === 'active')
                            ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                            : (($subStatus === 'expired')
                              ? 'bg-amber-50 text-amber-800 ring-amber-200'
                              : (($subStatus === 'suspended')
                                ? 'bg-rose-50 text-rose-700 ring-rose-200'
                                : 'bg-slate-50 text-slate-700 ring-slate-200')) }}">
                          Sub: {{ $subStatusLabel }}
                        </span>
                      </div>

                      <div class="text-xs text-slate-500">
                        Ends: {{ $subEnds ? \Carbon\Carbon::parse($subEnds)->format('Y-m-d') : '—' }}
                      </div>
                    </div>
                  @endif
                </td>

                <td class="px-5 py-4 text-slate-700">
                  {{ optional($u->created_at)->format('Y-m-d') ?? '—' }}
                </td>

                <td class="px-5 py-4">
                  <div class="flex flex-wrap gap-2">

                    <a href="{{ route('admin.users.show', $u) }}"
                      class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                      View
                    </a>

                    {{-- Enable/Disable --}}
                    <form method="POST" action="{{ route('admin.users.toggle', $u) }}"
                      @submit.prevent="openToggle('{{ $u->id }}', @js($name), {{ $isActive ? 'true' : 'false' }})">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="rounded-xl px-3 py-2 text-xs font-semibold ring-1
                        {{ $isActive
                          ? 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'
                          : 'bg-emerald-600 text-white ring-emerald-600 hover:bg-emerald-700' }}">
                        {{ $isActive ? 'Disable' : 'Enable' }}
                      </button>
                    </form>

                    {{-- Employer Approve/Reject --}}
                    @if($u->role === 'employer' && ($empStatus ?? 'pending') === 'pending' && ($archived ?? '0') === '0')
                      <button type="button" @click="openApprove({{ $u->id }}, @js($name))"
                        class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                        Approve
                      </button>

                      <button type="button" @click="openReject({{ $u->id }}, @js($name))"
                        class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                        Reject
                      </button>
                    @endif

                    {{-- ✅ NEW: Subscription manage button (employer only) --}}
                    @if($u->role === 'employer' && ($archived ?? '0') === '0')
                      <button type="button"
                        @click="openSubscription(
                          {{ $u->id }},
                          @js($name),
                          @js($plan),
                          @js($subStatus),
                          @js($subStarts ? \Carbon\Carbon::parse($subStarts)->format('Y-m-d') : ''),
                          @js($subEnds ? \Carbon\Carbon::parse($subEnds)->format('Y-m-d') : '')
                        )"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Subscription
                      </button>
                    @endif

                    {{-- Archive / Restore --}}
                    @if(($archived ?? '0') === '0')
                      <button type="button" @click="openArchive({{ $u->id }}, @js($name), 'archive')"
                        class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                        Archive
                      </button>
                    @else
                      <button type="button" @click="openArchive({{ $u->id }}, @js($name), 'restore')"
                        class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                        Restore
                      </button>
                    @endif

                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">
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

    {{-- APPROVE MODAL --}}
    <div x-show="approveOpen" class="fixed inset-0 z-50" x-transition.opacity @keydown.escape.window="closeAll()">
      <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>
      <div class="relative mx-auto w-[92%] max-w-lg" :class="isMobile ? 'mt-auto mb-4' : 'mt-16'">
        <div class="rounded-2xl bg-white p-5 shadow-xl" x-trap.noscroll="approveOpen">
          <div class="text-sm font-semibold text-slate-900">Approve employer</div>
          <div class="mt-1 text-sm text-slate-600">
            Do you want to approve <span class="font-semibold" x-text="selectedName"></span>?
          </div>
          <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button type="button" @click="closeAll()"
              class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
              Cancel
            </button>
            <form method="POST" :action="approveAction">
              @csrf
              @method('PATCH')
              <button type="submit"
                class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                Approve
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- REJECT MODAL --}}
    <div x-show="rejectOpen" class="fixed inset-0 z-50" x-transition.opacity @keydown.escape.window="closeAll()">
      <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>
      <div class="relative mx-auto w-[92%] max-w-lg" :class="isMobile ? 'mt-auto mb-4' : 'mt-16'">
        <div class="rounded-2xl bg-white p-5 shadow-xl" x-trap.noscroll="rejectOpen">
          <div class="text-sm font-semibold text-slate-900">Reject employer</div>
          <div class="mt-1 text-sm text-slate-600">
            Do you want to reject <span class="font-semibold" x-text="selectedName"></span>?
          </div>

          <div class="mt-4">
            <label class="text-xs font-semibold text-slate-700">Reason</label>
            <textarea x-model="rejectReason" rows="4"
              class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-400 focus:ring-4 focus:ring-rose-100"
              placeholder="Write the reason for rejection..."></textarea>

            <div class="mt-1 text-[11px] text-slate-500">
              This will be saved and can be shown to the employer.
            </div>

            <div class="mt-2 text-[11px] text-rose-600" x-show="rejectError">
              Please enter a reason before rejecting.
            </div>
          </div>

          <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button type="button" @click="closeAll()"
              class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
              Cancel
            </button>

            <form method="POST" :action="rejectAction" @submit.prevent="submitReject($event)">
              @csrf
              @method('PATCH')
              <input type="hidden" name="reason" :value="rejectReason">
              <button type="submit"
                class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                Reject
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- ARCHIVE/RESTORE MODAL --}}
    <div x-show="archiveOpen" class="fixed inset-0 z-50" x-transition.opacity @keydown.escape.window="closeAll()">
      <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>
      <div class="relative mx-auto w-[92%] max-w-lg" :class="isMobile ? 'mt-auto mb-4' : 'mt-16'">
        <div class="rounded-2xl bg-white p-5 shadow-xl" x-trap.noscroll="archiveOpen">
          <div class="text-sm font-semibold text-slate-900"
            x-text="archiveMode==='archive' ? 'Archive user' : 'Restore user'"></div>

          <div class="mt-1 text-sm text-slate-600">
            <span x-text="archiveMode==='archive'
                ? ('Do you want to archive ' + selectedName + '?')
                : ('Do you want to restore ' + selectedName + '?')"></span>
          </div>

          <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button type="button" @click="closeAll()"
              class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
              Cancel
            </button>

            <form method="POST" :action="archiveAction">
              @csrf
              @method('PATCH')
              <button type="submit" class="rounded-xl px-4 py-2 text-sm font-semibold text-white"
                :class="archiveMode==='archive' ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700'">
                <span x-text="archiveMode==='archive' ? 'Archive' : 'Restore'"></span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- TOGGLE MODAL --}}
    <div x-show="toggleOpen" class="fixed inset-0 z-50" x-transition.opacity @keydown.escape.window="closeAll()">
      <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>
      <div class="relative mx-auto w-[92%] max-w-lg" :class="isMobile ? 'mt-auto mb-4' : 'mt-16'">
        <div class="rounded-2xl bg-white p-5 shadow-xl" x-trap.noscroll="toggleOpen">
          <div class="text-sm font-semibold text-slate-900" x-text="toggleNextActive ? 'Enable user' : 'Disable user'"></div>
          <div class="mt-1 text-sm text-slate-600">
            <span x-text="toggleNextActive
                ? ('Do you want to enable ' + selectedName + '?')
                : ('Do you want to disable ' + selectedName + '?')"></span>
          </div>
          <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button type="button" @click="closeAll()"
              class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
              Cancel
            </button>
            <form method="POST" :action="toggleAction">
              @csrf
              @method('PATCH')
              <button type="submit" class="rounded-xl px-4 py-2 text-sm font-semibold text-white"
                :class="toggleNextActive ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-slate-900 hover:bg-slate-800'">
                <span x-text="toggleNextActive ? 'Enable' : 'Disable'"></span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- ✅ NEW: SUBSCRIPTION MODAL --}}
    <div x-show="subOpen" class="fixed inset-0 z-50" x-transition.opacity @keydown.escape.window="closeAll()">
      <div class="absolute inset-0 bg-black/40" @click="closeAll()"></div>

      <div class="relative mx-auto w-[92%] max-w-lg" :class="isMobile ? 'mt-auto mb-4' : 'mt-16'">
        <div class="rounded-2xl bg-white p-5 shadow-xl" x-trap.noscroll="subOpen">

          <div class="text-sm font-semibold text-slate-900">Manage subscription</div>
          <div class="mt-1 text-sm text-slate-600">
            Update subscription for <span class="font-semibold" x-text="selectedName"></span>.
          </div>

          <form method="POST" :action="subAction" class="mt-4 space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
              <div>
                <label class="text-xs font-semibold text-slate-700">Plan</label>
                <select name="plan" x-model="subPlan"
                  class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                  <option value="">—</option>
                  <option value="standard">Standard</option>
                  <option value="gold">Gold</option>
                  <option value="platinum">Platinum</option>
                </select>
              </div>

              <div>
                <label class="text-xs font-semibold text-slate-700">Subscription status</label>
                <select name="subscription_status" x-model="subStatus"
                  class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
                  <option value="">—</option>
                  <option value="active">Active</option>
                  <option value="expired">Expired</option>
                  <option value="suspended">Suspended</option>
                  <option value="none">None</option>
                </select>
              </div>

              <div>
                <label class="text-xs font-semibold text-slate-700">Starts at</label>
                <input type="date" name="starts_at" x-model="subStarts"
                  class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
              </div>

              <div>
                <label class="text-xs font-semibold text-slate-700">Ends at</label>
                <input type="date" name="ends_at" x-model="subEnds"
                  class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
              </div>
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-700">Suspend reason (optional)</label>
              <textarea name="suspended_reason" x-model="subReason" rows="3"
                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-rose-400 focus:ring-4 focus:ring-rose-100"
                placeholder="e.g. Expired subscription / payment issue / manual review..."></textarea>

              <label class="mt-2 flex items-center gap-2 text-xs text-slate-600">
                <input type="checkbox" name="also_hold_account" value="1" x-model="subAlsoHold"
                  class="rounded border-slate-300">
                Also put the user account on hold
              </label>
            </div>

            <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
              <button type="button" @click="closeAll()"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
                Cancel
              </button>

              <button type="submit"
                class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Save
              </button>

              <button type="button" @click="quickSuspend()"
                class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                Suspend
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>

  </div>

  <script>
    function usersUI() {
      return {
        approveOpen: false,
        rejectOpen: false,
        archiveOpen: false,
        toggleOpen: false,
        subOpen: false,

        selectedId: null,
        selectedName: '',

        rejectReason: '',
        rejectError: false,

        archiveMode: 'archive',

        toggleNextActive: true,

        // ✅ subscription modal state
        subPlan: '',
        subStatus: '',
        subStarts: '',
        subEnds: '',
        subReason: '',
        subAlsoHold: false,

        get isMobile() {
          return window.matchMedia && window.matchMedia('(max-width: 640px)').matches;
        },

        get approveAction() {
          return this.selectedId ? `/admin/users/${this.selectedId}/approve` : '#';
        },
        get rejectAction() {
          return this.selectedId ? `/admin/users/${this.selectedId}/reject` : '#';
        },
        get archiveAction() {
          if (!this.selectedId) return '#';
          return this.archiveMode === 'archive'
            ? `/admin/users/${this.selectedId}/archive`
            : `/admin/users/${this.selectedId}/restore`;
        },
        get toggleAction() {
          return this.selectedId ? `/admin/users/${this.selectedId}/toggle` : '#';
        },
        get subAction() {
          return this.selectedId ? `/admin/users/${this.selectedId}/subscription` : '#';
        },

        closeAll() {
          this.approveOpen = false;
          this.rejectOpen = false;
          this.archiveOpen = false;
          this.toggleOpen = false;
          this.subOpen = false;
          this.rejectError = false;
        },

        openApprove(id, name) {
          this.selectedId = id;
          this.selectedName = name || 'this employer';
          this.closeAll();
          this.approveOpen = true;
        },

        openReject(id, name) {
          this.selectedId = id;
          this.selectedName = name || 'this employer';
          this.rejectReason = '';
          this.rejectError = false;
          this.closeAll();
          this.rejectOpen = true;
        },

        submitReject(e) {
          const reason = (this.rejectReason || '').trim();
          if (!reason) {
            this.rejectError = true;
            return;
          }
          this.rejectError = false;
          e.target.submit();
        },

        openArchive(id, name, mode) {
          this.selectedId = id;
          this.selectedName = name || 'this user';
          this.archiveMode = (mode === 'restore') ? 'restore' : 'archive';
          this.closeAll();
          this.archiveOpen = true;
        },

        openToggle(id, name, isCurrentlyActive) {
          this.selectedId = id;
          this.selectedName = name || 'this user';
          this.toggleNextActive = !Boolean(isCurrentlyActive);
          this.closeAll();
          this.toggleOpen = true;
        },

        // ✅ subscription modal
        openSubscription(id, name, plan, status, startsAt, endsAt) {
          this.selectedId = id;
          this.selectedName = name || 'this employer';
          this.subPlan = plan || '';
          this.subStatus = status || '';
          this.subStarts = startsAt || '';
          this.subEnds = endsAt || '';
          this.subReason = '';
          this.subAlsoHold = false;
          this.closeAll();
          this.subOpen = true;
        },

        quickSuspend() {
          // sets status to suspended and submits the form
          this.subStatus = 'suspended';
          if (!this.subReason || !this.subReason.trim()) {
            this.subReason = 'Suspended by admin';
          }
          // force hold if you want:
          // this.subAlsoHold = true;

          const form = document.querySelector('div[x-show="subOpen"] form');
          if (form) form.submit();
        },
      }
    }
  </script>
@endsection