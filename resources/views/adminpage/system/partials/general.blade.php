{{-- resources/views/admin/system/partials/general.blade.php --}}
@section('title', 'System Configuration')
@section('page_title', 'System Configuration')
@php
  $siteName = data_get($settings, 'general.site.name.value', '');
  $supportEmail = data_get($settings, 'general.site.support_email.value', '');
  $timezone = data_get($settings, 'general.site.timezone.value', 'Asia/Manila');
@endphp

<h2 class="text-lg font-semibold mb-4">General Settings</h2>

<form method="POST" action="{{ route('admin.system.general.update', ['tab' => 'general']) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-sm font-medium text-gray-700">Site Name</label>
        <input name="site[name]" value="{{ old('site.name', $siteName) }}"
               class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
        @error('site.name')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Support Email</label>
        <input name="site[support_email]" type="email" value="{{ old('site.support_email', $supportEmail) }}"
               class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
        @error('site.support_email')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Timezone</label>
        <input name="site[timezone]" value="{{ old('site.timezone', $timezone) }}"
               class="mt-1 w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900" />
        <p class="text-xs text-gray-500 mt-1">Example: Asia/Manila</p>
        @error('site.timezone')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="pt-2">
        <button class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-white text-sm hover:bg-gray-800">
            Save General Settings
        </button>
    </div>
</form>