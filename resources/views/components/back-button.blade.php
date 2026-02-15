@props([
    'label' => null,
])

<button type="button"
    onclick="{ window.location.href='/'; }"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center gap-2 text-gray-600 hover:text-[#16A34A]
                    transition font-medium text-sm group'
    ]) }}
>
    <span class="flex items-center justify-center w-9 h-9 rounded-xl
                 bg-gray-100 group-hover:bg-[#16A34A]/10 transition">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </span>

    @if ($label)
        <span class="hidden sm:inline">{{ $label }}</span>
    @endif
</button>
