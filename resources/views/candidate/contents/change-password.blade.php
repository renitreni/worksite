@extends('candidate.layout')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Title --}}
    <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">
        Change Password
    </h1>

    {{-- Card --}}
    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-5 sm:p-8 space-y-6">

        {{-- Info box --}}
        <div class="flex items-start gap-4 rounded-2xl bg-blue-50 border border-blue-100 p-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">
                <i data-lucide="shield-check" class="h-5 w-5 text-blue-600"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Secure your account</p>
                <p class="text-sm text-gray-600">
                    Ensure your account is using a long, random password to stay secure.
                </p>
            </div>
        </div>

        {{-- Form --}}
        <form class="space-y-6">
            {{-- Current password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Current Password
                </label>
                <input
                    type="password"
                    placeholder="Enter current password"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                />
            </div>

            {{-- New password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    New Password
                </label>
                <input
                    type="password"
                    placeholder="Enter new password"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                />

                {{-- Strength bar --}}
                <div class="mt-3 space-y-2">
                    <div class="flex gap-2">
                        <span class="h-1.5 flex-1 rounded-full bg-emerald-500"></span>
                        <span class="h-1.5 flex-1 rounded-full bg-emerald-500"></span>
                        <span class="h-1.5 flex-1 rounded-full bg-gray-200"></span>
                        <span class="h-1.5 flex-1 rounded-full bg-gray-200"></span>
                    </div>
                    <p class="text-xs text-gray-500">
                        Password strength: <span class="font-semibold">Medium</span>
                    </p>
                </div>
            </div>

            {{-- Confirm password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm New Password
                </label>
                <input
                    type="password"
                    placeholder="Confirm new password"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                />
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-4">
                <button
                    type="button"
                    class="rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition"
                >
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection