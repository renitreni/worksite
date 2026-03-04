{{-- resources/views/admin/system/partials/security.blade.php --}}
@section('title', 'System Configuration')
@section('page_title', 'System Configuration')
@php
  $forceVerify = (bool) data_get($settings, 'security.security.force_email_verification.value', true);

  $minLen = (int) data_get($settings, 'security.security.password_min_length.value', 8);
  $throttle = (int) data_get($settings, 'security.security.login_throttle_per_minute.value', 10);
  $timeout = (int) data_get($settings, 'security.security.session_timeout_minutes.value', 120);
@endphp

<h2 class="text-lg font-semibold mb-4">Security Settings</h2>

<form method="POST" action="{{ route('admin.system.security.update', ['tab' => 'security']) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="security[force_email_verification]" value="1"
                   class="rounded border-gray-300"
                   {{ old('security.force_email_verification', $forceVerify) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Force email verification before allowing full access</span>
        </label>
        @error('security.force_email_verification')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Password Min Length</label>
            <input type="number" name="security[password_min_length]" value="{{ old('security.password_min_length', $minLen) }}"
                   class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
            @error('security.password_min_length')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Login throttle / minute</label>
            <input type="number" name="security[login_throttle_per_minute]" value="{{ old('security.login_throttle_per_minute', $throttle) }}"
                   class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
            @error('security.login_throttle_per_minute')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Session timeout (minutes)</label>
            <input type="number" name="security[session_timeout_minutes]" value="{{ old('security.session_timeout_minutes', $timeout) }}"
                   class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
            @error('security.session_timeout_minutes')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="pt-2">
        <button class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-white text-sm hover:bg-gray-800">
            Save Security Settings
        </button>
    </div>
</form>