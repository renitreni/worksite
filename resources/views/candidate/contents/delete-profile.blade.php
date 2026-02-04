@extends('candidate.layout')

@section('content')
<div class="max-w-3xl mx-auto space-y-6"
     x-data="{
        successOpen: false
     }"
>
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
                id="deletePassword"
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
                @click="successOpen = true"
                class="inline-flex items-center gap-2 rounded-xl bg-red-500 px-5 py-3 text-sm font-semibold text-white hover:bg-red-600 transition"
            >
                <i data-lucide="trash-2" class="h-4 w-4"></i>
                Delete Account
            </button>
        </div>
    </div>

    {{-- SUCCESS MODAL --}}
    <div
        x-show="successOpen"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        style="display: none;"
    >
        {{-- overlay --}}
        <div
            class="absolute inset-0 bg-black/40"
            @click="successOpen = false"
        ></div>

        {{-- modal card --}}
        <div
            x-transition.scale
            class="relative w-full max-w-md rounded-2xl bg-white shadow-xl border border-gray-200 p-6"
            @click.away="successOpen = false"
        >
            <div class="flex items-start gap-4">
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-green-100">
                    <i data-lucide="check-circle" class="h-6 w-6 text-green-600"></i>
                </div>

                <div class="flex-1">
                    <p class="text-base font-semibold text-gray-900">
                        Success
                    </p>
                    <p class="mt-1 text-sm text-gray-600">
                        Your account is successfully deleted.
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    class="rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700 transition"
                    @click="successOpen = false"
                >
                    OK
                </button>
            </div>
        </div>
    </div>
</div>
@endsection