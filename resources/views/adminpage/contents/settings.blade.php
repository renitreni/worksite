@extends('adminpage.layout')
@section('title','System Settings')
@section('page_title','System Configuration')

@section('content')
@php
  // Demo values for UI only (wire to DB later)
  $sys = [
    'site_name' => 'JobFinder Admin',
    'timezone' => 'Asia/Manila',
    'maintenance_mode' => false,

    'notify_admin_new_employer' => true,
    'notify_admin_pending_payment' => true,
    'notify_employer_subscription_expiry' => true,
    'notify_candidate_application_updates' => false,

    'session_timeout_mins' => 30,
    'password_min_len' => 8,
    'password_require_upper' => true,
    'password_require_number' => true,
    'password_require_symbol' => false,
    'enforce_2fa_admin' => false,
  ];

  $emailTemplates = [
    [
      'key' => 'employer_payment_received',
      'name' => 'Employer: Payment Received',
      'subject' => 'We received your payment',
      'body' => "Hi {employer_name},\n\nWe received your payment (Ref: {payment_ref}).\nWe will verify it shortly.\n\nThanks,\n{site_name}"
    ],
    [
      'key' => 'subscription_activated',
      'name' => 'Employer: Subscription Activated',
      'subject' => 'Your subscription is now active',
      'body' => "Hi {employer_name},\n\nYour {plan_name} subscription is active until {end_date}.\n\nThanks,\n{site_name}"
    ],
    [
      'key' => 'subscription_expired',
      'name' => 'Employer: Subscription Expired',
      'subject' => 'Your subscription has expired',
      'body' => "Hi {employer_name},\n\nYour subscription expired on {end_date}.\nRenew to continue posting jobs.\n\nThanks,\n{site_name}"
    ],
  ];

  $roles = ['Super Admin','Admin','Employer','Candidate'];

  $modules = [
    ['name'=>'Dashboard', 'key'=>'mod_dashboard'],
    ['name'=>'Taxonomy (Categories/Skills/Locations)', 'key'=>'mod_taxonomy'],
    ['name'=>'Employers', 'key'=>'mod_employers'],
    ['name'=>'Jobs', 'key'=>'mod_jobs'],
    ['name'=>'Billing / Subscriptions', 'key'=>'mod_billing'],
    ['name'=>'Reports', 'key'=>'mod_reports'],
    ['name'=>'System Settings', 'key'=>'mod_settings'],
    ['name'=>'Admin Accounts', 'key'=>'mod_admin_accounts'],
  ];

  // ✅ Demo: make the Super Admin data consistent
  $adminAccounts = [
    [
      'id' => 1,
      'name' => 'Super Admin',
      'email' => 'superadmin@jobfinder.test',
      'role' => 'Super Admin',
      'status' => 'Active',
      'created_at' => '2026-02-01 09:30',
      'last_login' => '2026-02-10 16:55',
    ],
    [
      'id' => 2,
      'name' => 'Admin One',
      'email' => 'admin1@jobfinder.test',
      'role' => 'Admin',
      'status' => 'Active',
      'created_at' => '2026-02-03 14:10',
      'last_login' => '2026-02-09 11:05',
    ],
  ];
@endphp

<div class="space-y-6"
  x-data="settingsUI({
    sys: @js($sys),
    templates: @js($emailTemplates),
    roles: @js($roles),
    modules: @js($modules),
    admins: @js($adminAccounts),
  })"
  x-init="init()"
>

  {{-- Header --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <div class="min-w-0">
        <div class="text-sm font-semibold text-slate-900">System configuration</div>
        <div class="mt-1 text-xs text-slate-500">
          Update platform settings, templates, maintenance, and access rules.
        </div>
      </div>

      <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <button type="button" @click="resetDraft(true)"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Reset
        </button>
        <button type="button" @click="saveAll()"
          class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
          Save Changes
        </button>
      </div>
    </div>

    {{-- Tabs --}}
    <div class="mt-4 flex flex-wrap gap-2">
      <button type="button" @click="tab='parameters'"
        class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
        :class="tab==='parameters' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
        Parameters
      </button>

      <button type="button" @click="tab='templates'"
        class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
        :class="tab==='templates' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
        Email Templates
      </button>

      <button type="button" @click="tab='maintenance'"
        class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
        :class="tab==='maintenance' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
        Maintenance
      </button>

      <button type="button" @click="tab='security'"
        class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
        :class="tab==='security' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
        Security & Access
      </button>

      {{-- ✅ Demo-only gating: show Admin Accounts tab only if Super Admin has access --}}
      <template x-if="canSeeAdminsTab">
        <button type="button" @click="tab='admins'"
          class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
          :class="tab==='admins' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
          Admin Accounts
        </button>
      </template>
    </div>
  </div>

  {{-- PARAMETERS --}}
  <div x-show="tab==='parameters'" x-transition class="grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">System Parameters</div>
      <div class="mt-1 text-xs text-slate-500">General settings for the platform</div>

      <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div class="sm:col-span-2">
          <label class="text-xs font-semibold text-slate-700">Site name</label>
          <input x-model.trim="draft.sys.site_name"
            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
            placeholder="e.g., JobFinder Admin" />
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-700">Timezone</label>
          <select x-model="draft.sys.timezone"
            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
            <option>Asia/Manila</option>
            <option>Asia/Singapore</option>
            <option>Asia/Tokyo</option>
            <option>UTC</option>
          </select>
          <div class="mt-1 text-[11px] text-slate-500">Used for emails, logs, and reports.</div>
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-700">Maintenance mode</label>
          <div class="mt-1 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
            <div class="text-sm font-semibold text-slate-800">Enable</div>
            <button type="button"
              @click="draft.sys.maintenance_mode = !draft.sys.maintenance_mode; toast('info', draft.sys.maintenance_mode ? 'Maintenance mode ON (demo)' : 'Maintenance mode OFF (demo)')"
              class="rounded-full px-3 py-2 text-xs font-semibold ring-1"
              :class="draft.sys.maintenance_mode ? 'bg-rose-600 text-white ring-rose-600' : 'bg-white text-slate-700 ring-slate-200'">
              <span x-text="draft.sys.maintenance_mode ? 'ON' : 'OFF'"></span>
            </button>
          </div>
          <div class="mt-1 text-[11px] text-slate-500">When ON, non-admin users should see a maintenance page.</div>
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Notifications</div>
      <div class="mt-1 text-xs text-slate-500">Choose what the system sends</div>

      <div class="mt-4 space-y-3">
        <template x-for="n in notifRows" :key="n.key">
          <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-3">
            <div class="min-w-0">
              <div class="text-sm font-semibold text-slate-800" x-text="n.label"></div>
              <div class="mt-0.5 text-xs text-slate-500" x-text="n.hint"></div>
            </div>
            <button type="button"
              @click="draft.sys[n.key] = !draft.sys[n.key]; toast('info', (draft.sys[n.key] ? 'Enabled: ' : 'Disabled: ') + n.label)"
              class="rounded-full px-3 py-2 text-xs font-semibold ring-1"
              :class="draft.sys[n.key] ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200'">
              <span x-text="draft.sys[n.key] ? 'ON' : 'OFF'"></span>
            </button>
          </div>
        </template>
      </div>
    </div>
  </div>

  {{-- EMAIL TEMPLATES --}}
  <div x-show="tab==='templates'" x-transition class="grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold text-slate-900">Templates</div>
        <div class="mt-1 text-xs text-slate-500">Select a template to edit</div>
      </div>

      <div class="p-3 space-y-2">
        <template x-for="t in draft.templates" :key="t.key">
          <button type="button" @click="selectTemplate(t.key)"
            class="w-full rounded-xl border px-4 py-3 text-left hover:bg-slate-50"
            :class="selectedTemplateKey===t.key ? 'border-emerald-300 bg-emerald-50' : 'border-slate-200 bg-white'">
            <div class="text-sm font-semibold text-slate-900" x-text="t.name"></div>
            <div class="mt-1 text-xs text-slate-500" x-text="t.subject"></div>
          </button>
        </template>
      </div>
    </div>

    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex items-start justify-between gap-3">
        <div>
          <div class="text-sm font-semibold text-slate-900">Template Editor</div>
          <div class="mt-1 text-xs text-slate-500">Edit subject and message body.</div>
        </div>
        <button type="button" @click="previewOpen=true; toast('info','Preview opened')"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold hover:bg-slate-50">
          Preview
        </button>
      </div>

      <template x-if="activeTemplate">
        <div class="mt-4 space-y-3">
          <div>
            <label class="text-xs font-semibold text-slate-700">Subject</label>
            <input x-model.trim="activeTemplate.subject"
              class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100" />
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-700">Body</label>
            <textarea x-model="activeTemplate.body" rows="10"
              class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-mono focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"></textarea>
            <div class="mt-2 text-[11px] text-slate-500">
              Variables: {site_name}, {employer_name}, {plan_name}, {payment_ref}, {end_date}
            </div>
          </div>

          <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
            <button type="button" @click="revertTemplate()"
              class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
              Revert
            </button>
            <button type="button" @click="saveTemplate()"
              class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
              Save Template
            </button>
          </div>
        </div>
      </template>

      <template x-if="!activeTemplate">
        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
          Select a template to start editing.
        </div>
      </template>
    </div>
  </div>

  {{-- MAINTENANCE --}}
  <div x-show="tab==='maintenance'" x-transition class="grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Backups & Recovery</div>
      <div class="mt-1 text-xs text-slate-500">Controls for backups and restores</div>

      <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs text-slate-500">Create backup</div>
          <div class="mt-1 text-sm font-semibold text-slate-900">Database + uploads snapshot</div>
          <button type="button" @click="fakeAction('Backup started (frontend demo).')"
            class="mt-3 w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
            Run Backup
          </button>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs text-slate-500">Restore backup</div>
          <div class="mt-1 text-sm font-semibold text-slate-900">Upload a backup file</div>
          <input type="file" class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" />
          <button type="button" @click="fakeAction('Restore queued (frontend demo).')"
            class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
            Restore
          </button>
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Maintenance Tasks</div>
      <div class="mt-1 text-xs text-slate-500">Common admin actions</div>

      <div class="mt-4 space-y-2">
        <button type="button" @click="fakeAction('Cache cleared (frontend demo).')"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Clear Cache
        </button>
        <button type="button" @click="fakeAction('Logs rotated (frontend demo).')"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Rotate Logs
        </button>
        <button type="button" @click="fakeAction('Queue restarted (frontend demo).')"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
          Restart Queue Workers
        </button>
      </div>

      <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs text-amber-800">
        Tip: Lock these behind Admin permissions and store audit logs.
      </div>
    </div>
  </div>

  {{-- SECURITY & ACCESS --}}
  <div x-show="tab==='security'" x-transition class="grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Security Rules</div>
      <div class="mt-1 text-xs text-slate-500">Password and session policy</div>

      <div class="mt-4 space-y-3">
        <div>
          <label class="text-xs font-semibold text-slate-700">Session timeout (minutes)</label>
          <input type="number" min="5" x-model.number="draft.sys.session_timeout_mins"
            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" />
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-700">Minimum password length</label>
          <input type="number" min="6" x-model.number="draft.sys.password_min_len"
            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" />
        </div>

        <div class="space-y-2">
          <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-3">
            <div class="text-sm font-semibold text-slate-800">Require uppercase</div>
            <button type="button" @click="draft.sys.password_require_upper = !draft.sys.password_require_upper; toast('info', draft.sys.password_require_upper ? 'Uppercase required (demo)' : 'Uppercase not required (demo)')"
              class="rounded-full px-3 py-2 text-xs font-semibold ring-1"
              :class="draft.sys.password_require_upper ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200'">
              <span x-text="draft.sys.password_require_upper ? 'ON' : 'OFF'"></span>
            </button>
          </div>

          <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-3">
            <div class="text-sm font-semibold text-slate-800">Require number</div>
            <button type="button" @click="draft.sys.password_require_number = !draft.sys.password_require_number; toast('info', draft.sys.password_require_number ? 'Number required (demo)' : 'Number not required (demo)')"
              class="rounded-full px-3 py-2 text-xs font-semibold ring-1"
              :class="draft.sys.password_require_number ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200'">
              <span x-text="draft.sys.password_require_number ? 'ON' : 'OFF'"></span>
            </button>
          </div>

          <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-3">
            <div class="text-sm font-semibold text-slate-800">Require symbol</div>
            <button type="button" @click="draft.sys.password_require_symbol = !draft.sys.password_require_symbol; toast('info', draft.sys.password_require_symbol ? 'Symbol required (demo)' : 'Symbol not required (demo)')"
              class="rounded-full px-3 py-2 text-xs font-semibold ring-1"
              :class="draft.sys.password_require_symbol ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200'">
              <span x-text="draft.sys.password_require_symbol ? 'ON' : 'OFF'"></span>
            </button>
          </div>
        </div>

        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-3">
          <div class="min-w-0">
            <div class="text-sm font-semibold text-slate-800">Enforce 2FA for admins</div>
            <div class="mt-0.5 text-xs text-slate-500">Requires auth integration</div>
          </div>
          <button type="button" @click="draft.sys.enforce_2fa_admin = !draft.sys.enforce_2fa_admin; toast('info', draft.sys.enforce_2fa_admin ? '2FA enforced (demo)' : '2FA not enforced (demo)')"
            class="rounded-full px-3 py-2 text-xs font-semibold ring-1"
            :class="draft.sys.enforce_2fa_admin ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200'">
            <span x-text="draft.sys.enforce_2fa_admin ? 'ON' : 'OFF'"></span>
          </button>
        </div>
      </div>
    </div>

    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Access Controls</div>
      <div class="mt-1 text-xs text-slate-500">Module access per role</div>

      <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-left">
          <thead>
            <tr class="text-xs text-slate-500">
              <th class="py-2 pr-4">Module</th>
              <template x-for="r in roles" :key="'rh'+r">
                <th class="py-2 pr-4" x-text="r"></th>
              </template>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            <template x-for="m in modules" :key="m.key">
              <tr class="text-sm">
                <td class="py-3 pr-4 font-semibold text-slate-800" x-text="m.name"></td>
                <template x-for="r in roles" :key="m.key+'-'+r">
                  <td class="py-3 pr-4">
                    <button type="button"
                      @click="toggleAccess(m.key, r)"
                      class="rounded-full px-3 py-2 text-xs font-semibold ring-1"
                      :class="access[m.key] && access[m.key][r] ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200'">
                      <span x-text="access[m.key] && access[m.key][r] ? 'Allow' : 'Deny'"></span>
                    </button>
                  </td>
                </template>
              </tr>
            </template>
          </tbody>
        </table>
      </div>

      <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-600">
        Backend idea: store role-module permissions in a table and enforce via middleware.
      </div>
    </div>
  </div>

  {{-- ADMIN ACCOUNTS --}}
  <div x-show="tab==='admins'" x-transition class="grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
          <div class="text-sm font-semibold text-slate-900">Admin Accounts</div>
          <div class="mt-1 text-xs text-slate-500">
            Frontend demo. Later: only Super Admin can create/disable admins.
          </div>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
          <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
            <span class="text-slate-400">⌕</span>
            <input x-model.trim="adminQ"
              class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none sm:w-64"
              placeholder="Search name/email/role…" />
          </div>

          <button type="button" @click="openAdminModal()"
            class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
            + Add Admin
          </button>
        </div>
      </div>

      <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-left">
          <thead>
            <tr class="text-xs text-slate-500">
              <th class="py-2 pr-4">Name</th>
              <th class="py-2 pr-4">Email</th>
              <th class="py-2 pr-4">Role</th>
              <th class="py-2 pr-4">Status</th>
              <th class="py-2 pr-4">Created</th>
              <th class="py-2 pr-4">Last login</th>
              <th class="py-2">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-200">
            <template x-for="a in adminFiltered" :key="a.id">
              <tr class="text-sm">
                <td class="py-3 pr-4 font-semibold text-slate-900" x-text="a.name"></td>
                <td class="py-3 pr-4 text-slate-700" x-text="a.email"></td>
                <td class="py-3 pr-4">
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1"
                    :class="a.role === 'Super Admin' ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-700 ring-slate-200'">
                    <span x-text="a.role"></span>
                  </span>
                </td>
                <td class="py-3 pr-4">
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1"
                    :class="a.status === 'Active' ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-rose-50 text-rose-700 ring-rose-200'">
                    <span x-text="a.status"></span>
                  </span>
                </td>
                <td class="py-3 pr-4 text-xs text-slate-600" x-text="a.created_at"></td>
                <td class="py-3 pr-4 text-xs text-slate-600" x-text="a.last_login"></td>

                <td class="py-3">
                  <div class="flex flex-wrap gap-2">
                    <button type="button" @click="openAdminModal(a)"
                      class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50">
                      Edit
                    </button>

                    <button type="button" @click="toggleAdminStatus(a.id)"
                      class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50"
                      :disabled="a.role === 'Super Admin'"
                      :class="a.role === 'Super Admin' ? 'opacity-50 cursor-not-allowed' : ''">
                      <span x-text="a.status === 'Active' ? 'Disable' : 'Enable'"></span>
                    </button>

                    <button type="button" @click="resetAdminPassword(a.id)"
                      class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800"
                      :disabled="a.role === 'Super Admin'"
                      :class="a.role === 'Super Admin' ? 'opacity-50 cursor-not-allowed' : ''">
                      Reset PW
                    </button>
                  </div>
                </td>
              </tr>
            </template>

            <tr x-show="adminFiltered.length === 0">
              <td colspan="7" class="py-8 text-center text-sm text-slate-500">
                No matching admin accounts.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs text-amber-800">
        Demo limitation: UI-only gating. Backend later must restrict create/edit/disable actions.
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold text-slate-900">Notes</div>
      <div class="mt-1 text-xs text-slate-500">Recommended rules</div>

      <div class="mt-4 space-y-3 text-sm text-slate-700">
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs font-semibold text-slate-700">Rule #1</div>
          <div class="mt-1 text-xs text-slate-600">Do not allow public registration to create Admin roles.</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs font-semibold text-slate-700">Rule #2</div>
          <div class="mt-1 text-xs text-slate-600">Only Super Admin can add/disable Admin accounts.</div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs font-semibold text-slate-700">Rule #3</div>
          <div class="mt-1 text-xs text-slate-600">Log admin actions (who created, disabled, reset password).</div>
        </div>
      </div>
    </div>

    {{-- Admin modal --}}
    <div x-show="adminModalOpen" x-transition.opacity class="fixed inset-0 z-50">
      <div class="absolute inset-0 bg-black/40" @click="closeAdminModal()"></div>

      <div class="relative mx-auto mt-10 w-[92%] max-w-xl rounded-2xl bg-white p-5 shadow-xl">
        <div class="flex items-start justify-between gap-3">
          <div>
            <div class="text-sm font-semibold text-slate-900" x-text="adminForm.id ? 'Edit Admin' : 'Add Admin'"></div>
            <div class="mt-1 text-xs text-slate-500">Frontend demo only — no database yet.</div>
          </div>
          <button class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50"
            @click="closeAdminModal()">Close</button>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <label class="text-xs font-semibold text-slate-700">Full name</label>
            <input x-model.trim="adminForm.name"
              class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
              placeholder="e.g., Admin Two" />
          </div>

          <div class="sm:col-span-2">
            <label class="text-xs font-semibold text-slate-700">Email</label>
            <input x-model.trim="adminForm.email" type="email"
              class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
              placeholder="e.g., admin2@jobfinder.test" />
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-700">Role</label>
            <select x-model="adminForm.role"
              class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
              <option>Admin</option>
              <option>Super Admin</option>
            </select>
            <div class="mt-1 text-[11px] text-slate-500">Backend later: only Super Admin can assign Super Admin.</div>
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-700">Status</label>
            <select x-model="adminForm.status"
              class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm">
              <option>Active</option>
              <option>Disabled</option>
            </select>
          </div>

          <div class="sm:col-span-2">
            <label class="text-xs font-semibold text-slate-700">Temporary password (demo)</label>
            <input x-model.trim="adminForm.temp_password" type="text"
              class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-mono focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
              placeholder="Generate later in backend" />
            <div class="mt-1 text-[11px] text-slate-500">
              Later: generate + email invite link instead of showing a password here.
            </div>
          </div>
        </div>

        <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:justify-end">
          <button type="button" @click="closeAdminModal()"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
            Cancel
          </button>
          <button type="button" @click="saveAdmin()"
            class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
            Save Admin
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Preview modal --}}
  <div x-show="previewOpen" x-transition.opacity class="fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40" @click="previewOpen=false"></div>
    <div class="relative mx-auto mt-10 w-[92%] max-w-2xl rounded-2xl bg-white p-5 shadow-xl">
      <div class="flex items-start justify-between gap-3">
        <div>
          <div class="text-sm font-semibold text-slate-900">Email Preview</div>
          <div class="mt-1 text-xs text-slate-500">Shows sample variable replacements</div>
        </div>
        <button type="button" @click="previewOpen=false; toast('info','Preview closed')"
          class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-slate-50">
          Close
        </button>
      </div>

      <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <div class="text-xs text-slate-500">Subject</div>
        <div class="mt-1 text-sm font-semibold text-slate-900" x-text="preview.subject"></div>
      </div>

      <div class="mt-3 rounded-2xl border border-slate-200 bg-white p-4">
        <div class="text-xs text-slate-500">Body</div>
        <pre class="mt-2 whitespace-pre-wrap text-sm text-slate-800" x-text="preview.body"></pre>
      </div>
    </div>
  </div>

</div>

<script>
  function settingsUI(seed){
    return {
      tab: 'parameters',
      previewOpen: false,

      original: { sys:{}, templates:[] },
      draft: { sys:{}, templates:[] },

      roles: [],
      modules: [],
      access: {},

      adminQ: '',
      admins: [],
      adminModalOpen: false,
      adminForm: { id:null, name:'', email:'', role:'Admin', status:'Active', temp_password:'' },

      selectedTemplateKey: null,

      toast(type, msg, title = ''){
        if (!window.notify) return;
        const allowed = ['success','info','warning','error'];
        const safeType = allowed.includes(type) ? type : 'info';
        window.notify(safeType, String(msg || ''), String(title || ''));
      },

      notifRows: [
        { key:'notify_admin_new_employer', label:'Admin: new employer registrations', hint:'Notify admins when a new employer signs up' },
        { key:'notify_admin_pending_payment', label:'Admin: pending payments', hint:'Notify admins when a payment needs verification' },
        { key:'notify_employer_subscription_expiry', label:'Employer: subscription expiry', hint:'Send reminders before/after subscription ends' },
        { key:'notify_candidate_application_updates', label:'Candidate: application updates', hint:'Notify candidates about job application status changes' },
      ],

      init(){
        this.original.sys = {...(seed.sys || {})};
        this.original.templates = (seed.templates || []).map(t => ({...t}));

        this.resetDraft(false);

        this.roles = seed.roles || [];
        this.modules = seed.modules || [];
        this.admins = (seed.admins || []).map(a => ({...a}));

        // ✅ Cleaned demo defaults:
        // - Super Admin: allow all
        // - Admin: allow everything EXCEPT System Settings + Admin Accounts (demo)
        // - Employer/Candidate: deny all
        this.access = {};
        this.modules.forEach(m => {
          this.access[m.key] = {};
          this.roles.forEach(r => {
            if(r === 'Super Admin') this.access[m.key][r] = true;
            else if(r === 'Admin') this.access[m.key][r] = !['mod_settings','mod_admin_accounts'].includes(m.key);
            else this.access[m.key][r] = false;
          });
        });

        if(this.draft.templates.length){
          this.selectedTemplateKey = this.draft.templates[0].key;
        }
      },

      // ✅ Used for tab gating (demo)
      get canSeeAdminsTab(){
        return this.access?.mod_admin_accounts?.['Super Admin'] === true;
      },

      resetDraft(withToast=true){
        this.draft.sys = {...this.original.sys};
        this.draft.templates = this.original.templates.map(t => ({...t}));
        if(withToast) this.toast('info', 'Draft reset');
      },

      saveAll(){
        const site = String(this.draft.sys.site_name || '').trim();
        if(!site){
          this.toast('error', 'Site name is required.');
          return;
        }

        this.original.sys = {...this.draft.sys};
        this.original.templates = this.draft.templates.map(t => ({...t}));

        this.toast('success', 'Saved changes (frontend demo).');
      },

      fakeAction(msg){
        this.toast('info', msg);
      },

      selectTemplate(key){
        this.selectedTemplateKey = key;
        const t = this.draft.templates.find(x => x.key === key);
        this.toast('info', 'Selected: ' + (t?.name || key));
      },

      get activeTemplate(){
        return this.draft.templates.find(t => t.key === this.selectedTemplateKey) || null;
      },

      revertTemplate(){
        const key = this.selectedTemplateKey;
        if(!key){
          this.toast('warning', 'No template selected.');
          return;
        }

        const orig = this.original.templates.find(t => t.key === key);
        const idx = this.draft.templates.findIndex(t => t.key === key);

        if(orig && idx !== -1){
          this.draft.templates[idx] = {...orig};
          this.toast('success', 'Template reverted (frontend demo).');
        } else {
          this.toast('error', 'Template not found.');
        }
      },

      saveTemplate(){
        if(!this.activeTemplate){
          this.toast('warning', 'No template selected.');
          return;
        }
        this.toast('success', 'Template saved (frontend demo).');
      },

      get preview(){
        const t = this.activeTemplate;
        if(!t) return { subject:'', body:'' };

        const sample = {
          '{site_name}': this.draft.sys.site_name || 'Site',
          '{employer_name}': 'ACME Corp',
          '{plan_name}': 'Pro',
          '{payment_ref}': 'GC-88421',
          '{end_date}': '2026-03-01',
        };

        const replaceVars = (text) => {
          let out = String(text || '');
          Object.keys(sample).forEach(k => { out = out.split(k).join(sample[k]); });
          return out;
        };

        return {
          subject: replaceVars(t.subject),
          body: replaceVars(t.body),
        };
      },

      toggleAccess(moduleKey, role){
        if(!this.access[moduleKey]) this.access[moduleKey] = {};
        this.access[moduleKey][role] = !this.access[moduleKey][role];

        const modName = this.modules.find(m => m.key === moduleKey)?.name || moduleKey;
        const state = this.access[moduleKey][role] ? 'Allowed' : 'Denied';
        this.toast('info', `${state}: ${role} → ${modName}`);
      },

      get adminFiltered(){
        const q = (this.adminQ || '').toLowerCase().trim();
        if(!q) return this.admins;

        return this.admins.filter(a => {
          const hay = `${a.name} ${a.email} ${a.role} ${a.status}`.toLowerCase();
          return hay.includes(q);
        });
      },

      openAdminModal(admin=null){
        if(admin){
          this.adminForm = {
            id: admin.id,
            name: admin.name,
            email: admin.email,
            role: admin.role,
            status: admin.status,
            temp_password: '',
          };
          this.toast('info', 'Editing admin: ' + admin.email);
        } else {
          this.adminForm = { id:null, name:'', email:'', role:'Admin', status:'Active', temp_password:'' };
          this.toast('info', 'Add a new admin');
        }
        this.adminModalOpen = true;
      },

      closeAdminModal(){
        this.adminModalOpen = false;
      },

      saveAdmin(){
        const name = String(this.adminForm.name || '').trim();
        const email = String(this.adminForm.email || '').trim();

        if(!name){
          this.toast('error', 'Name is required');
          return;
        }
        if(!email || !email.includes('@')){
          this.toast('error', 'Valid email is required');
          return;
        }

        const dup = this.admins.some(a => a.email.toLowerCase() === email.toLowerCase() && a.id !== this.adminForm.id);
        if(dup){
          this.toast('error', 'Email already exists');
          return;
        }

        if(this.adminForm.role === 'Super Admin'){
          const hasSuper = this.admins.some(a => a.role === 'Super Admin' && a.id !== this.adminForm.id);
          if(hasSuper){
            this.toast('warning', 'Demo rule: only one Super Admin allowed');
            return;
          }
        }

        if(this.adminForm.id){
          const idx = this.admins.findIndex(a => a.id === this.adminForm.id);
          if(idx !== -1){
            this.admins[idx] = { ...this.admins[idx], name, email, role: this.adminForm.role, status: this.adminForm.status };
          }
          this.toast('success', 'Admin updated (demo)');
        } else {
          const nextId = Math.max(0, ...this.admins.map(a => Number(a.id || 0))) + 1;
          this.admins.unshift({
            id: nextId,
            name,
            email,
            role: this.adminForm.role,
            status: this.adminForm.status,
            created_at: new Date().toISOString().slice(0,16).replace('T',' '),
            last_login: '—',
          });
          this.toast('success', 'Admin added (demo)');
        }

        this.adminModalOpen = false;
      },

      toggleAdminStatus(id){
        const idx = this.admins.findIndex(a => a.id === id);
        if(idx === -1) return;

        if(this.admins[idx].role === 'Super Admin'){
          this.toast('warning', 'Super Admin cannot be disabled (demo)');
          return;
        }

        this.admins[idx].status = (this.admins[idx].status === 'Active') ? 'Disabled' : 'Active';
        this.toast('info', 'Status updated (demo)');
      },

      resetAdminPassword(id){
        const a = this.admins.find(x => x.id === id);
        if(!a) return;

        if(a.role === 'Super Admin'){
          this.toast('warning', 'Super Admin reset disabled in demo');
          return;
        }

        this.toast('info', `Password reset link would be sent to: ${a.email} (demo)`);
      },
    }
  }
</script>

@endsection
