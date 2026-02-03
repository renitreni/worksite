@extends('candidate.layout')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Title --}}
    <h1 class="text-xl sm:text-2xl font-semibold text-red-600">
        Delete Profile
    </h1>

    {{-- Warning Card --}}
    <div class="rounded-2xl bg-white border border-red-200 shadow-sm p-5 sm:p-8 space-y-6">

        {{-- Warning Header --}}
        <div class="flex items-start gap-4">
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-red-100">
                <i data-lucide="alert-triangle" class="h-5 w-5 text-red-600"></i>
            </div>
            <div>
                <p class="text-base font-semibold text-gray-900">
                    Are you sure you want to delete your account?
                </p>
                <p class="mt-1 text-sm text-gray-600">
                    This action is permanent and cannot be undone. All your data including:
                </p>
            </div>
        </div>

        {{-- Data list --}}
        <ul class="ml-16 list-disc space-y-1 text-sm text-gray-600">
            <li>Profile information and resume</li>
            <li>Job applications and history</li>
            <li>Saved jobs and alerts</li>
            <li>Messages and contacts</li>
        </ul>

        {{-- Password confirmation --}}
        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 space-y-2">
            <label class="block text-sm font-medium text-gray-700">
                To confirm, please enter your password
            </label>
            <input
                type="password"
                placeholder="Enter your password"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300"
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
                class="inline-flex items-center gap-2 rounded-xl bg-red-500 px-5 py-3 text-sm font-semibold text-white hover:bg-red-600 transition"
            >
                <i data-lucide="trash-2" class="h-4 w-4"></i>
                Delete Account
            </button>
        </div>
    </div>
</div>
@endsection
