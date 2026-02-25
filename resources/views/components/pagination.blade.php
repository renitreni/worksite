@php
    $scrollTopJs = "this.closest('[wire\\\\:id]')?.querySelector('[data-pagination-top]')?.scrollIntoView({behavior:'smooth', block:'start'})";
@endphp

@if ($paginator->hasPages())
    <nav class="flex items-center justify-between gap-3" role="navigation" aria-label="Pagination">
        {{-- Mobile --}}
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-400">
                    Prev
                </span>
            @else
                <button
                    wire:click="previousPage"
                    wire:loading.attr="disabled"
                    onclick="{{ $scrollTopJs }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Prev
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button
                    wire:click="nextPage"
                    wire:loading.attr="disabled"
                    onclick="{{ $scrollTopJs }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Next
                </button>
            @else
                <span class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-400">
                    Next
                </span>
            @endif
        </div>

        {{-- Desktop --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div class="text-sm text-slate-600">
                Showing
                <span class="font-semibold text-slate-900">{{ $paginator->firstItem() ?? 0 }}</span>
                to
                <span class="font-semibold text-slate-900">{{ $paginator->lastItem() ?? 0 }}</span>
                of
                <span class="font-semibold text-slate-900">{{ $paginator->total() }}</span>
                results
            </div>

            <div class="flex items-center gap-2">
                {{-- Prev --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex h-11 items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-400">
                        <span class="text-lg leading-none">‹</span> Prev
                    </span>
                @else
                    <button
                        wire:click="previousPage"
                        wire:loading.attr="disabled"
                        onclick="{{ $scrollTopJs }}"
                        class="inline-flex h-11 items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:shadow-sm transition">
                        <span class="text-lg leading-none">‹</span> Prev
                    </button>
                @endif

                {{-- Pages --}}
                <div class="flex items-center gap-2">
                    @foreach ($elements as $element)
                        {{-- Dots --}}
                        @if (is_string($element))
                            <span class="px-2 text-slate-400">…</span>
                        @endif

                        {{-- Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page"
                                        class="inline-flex h-11 min-w-[44px] items-center justify-center rounded-2xl bg-[#16A34A] px-4 text-sm font-extrabold text-white shadow-sm">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button
                                        wire:click="gotoPage({{ $page }})"
                                        wire:loading.attr="disabled"
                                        onclick="{{ $scrollTopJs }}"
                                        class="inline-flex h-11 min-w-[44px] items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-700 hover:bg-slate-50 hover:shadow-sm transition">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <button
                        wire:click="nextPage"
                        wire:loading.attr="disabled"
                        onclick="{{ $scrollTopJs }}"
                        class="inline-flex h-11 items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:shadow-sm transition">
                        Next <span class="text-lg leading-none">›</span>
                    </button>
                @else
                    <span class="inline-flex h-11 items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-400">
                        Next <span class="text-lg leading-none">›</span>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif