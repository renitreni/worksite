@extends('adminpage.layout')
@section('title','Settings')
@section('page_title','System Configuration')

@section('content')
<div class="space-y-6">

  {{-- Overview --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
      <div>
        <div class="text-sm font-semibold">System Settings</div>
        <div class="mt-1 text-xs text-slate-500">Configure notifications, email templates, backups, and access controls</div>
      </div>
      <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
        Save Changes
      </button>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">

    {{-- Notifications --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold">Notifications</div>
      <div class="mt-1 text-xs text-slate-500">System alerts and reminders</div>

      <div class="mt-5 space-y-3">
        @foreach([
          ['title'=>'Subscription Expiry Reminders','desc'=>'Email employers when plan is near expiry'],
          ['title'=>'Payment Verification Alerts','desc'=>'Notify admins when payments are pending'],
          ['title'=>'Job Approval Queue Alerts','desc'=>'Send alerts for new pending job posts'],
        ] as $x)
          <div class="flex items-start justify-between gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div>
              <div class="text-sm font-semibold">{{ $x['title'] }}</div>
              <div class="mt-1 text-xs text-slate-500">{{ $x['desc'] }}</div>
            </div>
            <label class="inline-flex cursor-pointer items-center">
              <input type="checkbox" class="peer sr-only" checked>
              <div class="peer h-6 w-11 rounded-full bg-slate-200 after:absolute after:mt-0.5 after:ml-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-emerald-600 peer-checked:after:translate-x-5 relative"></div>
            </label>
          </div>
        @endforeach
      </div>
    </div>

    {{-- Security --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold">Security & Access Control</div>
      <div class="mt-1 text-xs text-slate-500">Protect admin access and sensitive data</div>

      <div class="mt-5 space-y-3">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-sm font-semibold">Admin Session Timeout</div>
          <div class="mt-2 flex gap-2">
            <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
              <option>15 minutes</option>
              <option selected>30 minutes</option>
              <option>60 minutes</option>
            </select>
            <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">Apply</button>
          </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-sm font-semibold">Two-Factor Authentication</div>
          <div class="mt-1 text-xs text-slate-500">Require 2FA for admin accounts</div>
          <div class="mt-3 flex gap-2">
            <button class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Enable</button>
            <button class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">Manage</button>
          </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-sm font-semibold">Audit Log (Preview)</div>
          <div class="mt-2 text-xs text-slate-500">Track changes to users, jobs, and billing</div>
          <div class="mt-3 rounded-xl bg-white p-3 ring-1 ring-slate-200 text-sm text-slate-700">
            <div>• Admin updated employer subscription (TechTalent Hub)</div>
            <div>• Admin rejected job post (Customer Support Agent)</div>
            <div>• Admin suspended user (expired subscription)</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Email templates --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold">Email Templates</div>
      <div class="mt-1 text-xs text-slate-500">Customize system emails</div>

      <div class="mt-5 space-y-3">
        <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100">
          <option>Employer Approval</option>
          <option>Payment Verified</option>
          <option>Subscription Expired</option>
          <option>Job Approved</option>
          <option>Job Rejected</option>
        </select>

        <input class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
               placeholder="Subject (e.g., Your employer account is approved)" />

        <textarea class="h-40 w-full rounded-xl border border-slate-200 bg-white p-3 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                  placeholder="Write the email body…"></textarea>

        <div class="flex gap-2">
          <button class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">Preview</button>
          <button class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Save Template</button>
        </div>
      </div>
    </div>

    {{-- Backup & Recovery --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="text-sm font-semibold">Backup & Recovery</div>
      <div class="mt-1 text-xs text-slate-500">Maintenance operations (UI only)</div>

      <div class="mt-5 space-y-3">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-sm font-semibold">Create Backup</div>
          <div class="mt-1 text-xs text-slate-500">Generate a backup file of database and uploads</div>
          <button class="mt-3 w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
            Run Backup
          </button>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-sm font-semibold">Restore</div>
          <div class="mt-1 text-xs text-slate-500">Upload a backup to restore system state</div>
          <input type="file" class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" />
          <button class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
            Start Restore
          </button>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
