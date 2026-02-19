@props([
    'type' => 'success',   // success | error | warning | info
    'message' => null,
    'duration' => 3000,
])

@php
    $styles = [
        'success' => [
            'wrap' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
            'icon' => 'check-circle',
            'iconColor' => 'text-emerald-600',
            'close' => 'text-emerald-600 hover:text-emerald-800',
        ],
        'error' => [
            'wrap' => 'border-rose-200 bg-rose-50 text-rose-800',
            'icon' => 'x-circle',
            'iconColor' => 'text-rose-600',
            'close' => 'text-rose-600 hover:text-rose-800',
        ],
        'warning' => [
            'wrap' => 'border-amber-200 bg-amber-50 text-amber-800',
            'icon' => 'alert-triangle',
            'iconColor' => 'text-amber-600',
            'close' => 'text-amber-600 hover:text-amber-800',
        ],
        'info' => [
            'wrap' => 'border-sky-200 bg-sky-50 text-sky-800',
            'icon' => 'info',
            'iconColor' => 'text-sky-600',
            'close' => 'text-sky-600 hover:text-sky-800',
        ],
    ];

    $t = $styles[$type] ?? $styles['success'];
@endphp

@if($message)
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, {{ (int) $duration }})"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-4"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-4"
        class="fixed top-6 right-6 z-[100] w-full max-w-sm rounded-2xl border px-5 py-4 shadow-lg {{ $t['wrap'] }}"
    >
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-2">
                <i data-lucide="{{ $t['icon'] }}" class="h-5 w-5 {{ $t['iconColor'] }}"></i>
                <p class="text-sm font-semibold">
                    {{ $message }}
                </p>
            </div>

            <button type="button" class="{{ $t['close'] }}" @click="show=false">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>
    </div>
@endif

{{-- <x-toast type="success" :message="session('success')" />
<x-toast type="error" :message="session('error')" />
<x-toast type="warning" :message="session('warning')" />
<x-toast type="info" :message="session('info')" /> --}}

