{{-- resources/views/admin/system/partials/notifications.blade.php --}}
@section('title', 'System Configuration')
@section('page_title', 'System Configuration')
@php
  $emailEnabled = (bool) data_get($settings, 'notifications.notify.email_enabled.value', false);
  $smsEnabled = (bool) data_get($settings, 'notifications.notify.sms_enabled.value', false);

  $fromEmail = data_get($settings, 'notifications.notify.from_email.value', '');
  $fromName = data_get($settings, 'notifications.notify.from_name.value', '');
  $adminAlertEmail = data_get($settings, 'notifications.notify.admin_alert_email.value', '');

  $newJobAlert = (bool) data_get($settings, 'notifications.notify.new_job_post_alert_admin.value', false);
  $newUserAlert = (bool) data_get($settings, 'notifications.notify.new_user_signup_alert_admin.value', false);
@endphp

<h2 class="text-lg font-semibold mb-4">Notification Settings</h2>

<form method="POST" action="{{ route('admin.system.notifications.update', ['tab' => 'notifications']) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid md:grid-cols-2 gap-4">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="notify[email_enabled]" value="1"
                   class="rounded border-gray-300"
                   {{ old('notify.email_enabled', $emailEnabled) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Enable Email Notifications</span>
        </label>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="notify[sms_enabled]" value="1"
                   class="rounded border-gray-300"
                   {{ old('notify.sms_enabled', $smsEnabled) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Enable SMS Notifications</span>
        </label>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">From Email</label>
            <input name="notify[from_email]" type="email" value="{{ old('notify.from_email', $fromEmail) }}"
                   class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
            @error('notify.from_email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">From Name</label>
            <input name="notify[from_name]" value="{{ old('notify.from_name', $fromName) }}"
                   class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
            @error('notify.from_name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Admin Alert Email</label>
        <input name="notify[admin_alert_email]" type="email" value="{{ old('notify.admin_alert_email', $adminAlertEmail) }}"
               class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
        <p class="text-xs text-gray-500 mt-1">Receives alerts like new job posts / new signups (if enabled).</p>
        @error('notify.admin_alert_email')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-2">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="notify[new_job_post_alert_admin]" value="1"
                   class="rounded border-gray-300"
                   {{ old('notify.new_job_post_alert_admin', $newJobAlert) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Alert admin on new job post</span>
        </label>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="notify[new_user_signup_alert_admin]" value="1"
                   class="rounded border-gray-300"
                   {{ old('notify.new_user_signup_alert_admin', $newUserAlert) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Alert admin on new user signup</span>
        </label>
    </div>

    <div class="pt-2">
        <button class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-white text-sm hover:bg-gray-800">
            Save Notification Settings
        </button>
    </div>
</form>