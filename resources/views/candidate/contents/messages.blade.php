@extends('candidate.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Messages</h1>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        {{-- Left: conversation list --}}
        <section class="xl:col-span-4">
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i data-lucide="search" class="h-4 w-4"></i>
                        </span>
                        <input
                            type="text"
                            placeholder="Search messages..."
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 pl-9 pr-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                        />
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    {{-- Active thread --}}
                    <button type="button" class="w-full text-left p-4 bg-blue-50/70 hover:bg-blue-50 transition">
                        <div class="flex items-start gap-3">
                            <div class="relative">
                                <img
                                    src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=crop&w=96&h=96&q=80"
                                    alt="Alex Morgan"
                                    class="h-10 w-10 rounded-full object-cover ring-2 ring-white"
                                />
                                <span class="absolute -top-1 -right-1 h-3.5 w-3.5 rounded-full bg-rose-500 ring-2 ring-white"></span>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">Alex Morgan</p>
                                        <p class="text-xs text-gray-500 truncate">TechFlow</p>
                                    </div>
                                    <p class="text-xs text-gray-500 whitespace-nowrap">10:30 AM</p>
                                </div>
                                <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                    Hi Keith, thanks for confirming the time. We look forward to speaking with you...
                                </p>
                            </div>
                        </div>
                    </button>

                    {{-- Other thread --}}
                    <button type="button" class="w-full text-left p-4 hover:bg-gray-50 transition">
                        <div class="flex items-start gap-3">
                            <img
                                src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=96&h=96&q=80"
                                alt="Emily Chen"
                                class="h-10 w-10 rounded-full object-cover ring-2 ring-white"
                            />

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">Emily Chen</p>
                                        <p class="text-xs text-gray-500 truncate">Creative Studio</p>
                                    </div>
                                    <p class="text-xs text-gray-500 whitespace-nowrap">Yesterday</p>
                                </div>
                                <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                    Hello Keith, we have reviewed your portfolio and would like to discuss next steps.
                                </p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </section>

        {{-- Right: chat --}}
        <section class="xl:col-span-8">
            <div class="rounded-2xl bg-white border border-gray-200 shadow-sm overflow-hidden flex flex-col min-h-[560px]">
                {{-- Chat header --}}
                <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <img
                            src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=crop&w=96&h=96&q=80"
                            alt="Alex Morgan"
                            class="h-11 w-11 rounded-full object-cover ring-2 ring-gray-100"
                        />
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">Alex Morgan</p>
                            <p class="text-xs text-gray-500 truncate">TechFlow</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition" title="Call">
                            <i data-lucide="phone" class="h-5 w-5 text-gray-600"></i>
                        </button>
                        <button class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition" title="Video">
                            <i data-lucide="video" class="h-5 w-5 text-gray-600"></i>
                        </button>
                        <button class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 transition" title="More">
                            <i data-lucide="more-vertical" class="h-5 w-5 text-gray-600"></i>
                        </button>
                    </div>
                </div>

                {{-- Messages --}}
                <div class="flex-1 p-4 sm:p-6 bg-white space-y-4 overflow-y-auto">
                    {{-- Incoming --}}
                    <div class="max-w-[640px]">
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:p-5 shadow-sm">
                            <p class="text-sm text-gray-700 leading-relaxed">
                                Hi Keith, thanks for confirming the time. We look forward to speaking with you... Let's schedule a time to chat.
                            </p>
                            <p class="mt-3 text-xs text-gray-500">10:30 AM</p>
                        </div>
                    </div>

                    {{-- Outgoing --}}
                    <div class="flex justify-end">
                        <div class="max-w-[640px]">
                            <div class="rounded-2xl bg-blue-600 p-4 sm:p-5 shadow-sm">
                                <p class="text-sm text-white leading-relaxed">
                                    Hi Alex, thanks for reaching out! I'm available tomorrow afternoon.
                                </p>
                                <p class="mt-3 text-xs text-blue-100">Just now</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Composer --}}
                <div class="p-4 sm:p-5 border-t border-gray-100 bg-white">
                    <div class="flex items-center gap-3">
                        <input
                            type="text"
                            placeholder="Type your message..."
                            class="flex-1 rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300"
                        />
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition"
                        >
                            <span>Send</span>
                            <i data-lucide="send" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection