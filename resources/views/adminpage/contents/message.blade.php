@extends('adminpage.layout')

@section('title', 'Inbox')
@section('page_title', 'Inbox')

@section('content')

    <div class="space-y-6">

        {{-- Layout --}}
        <div class="flex gap-6">

            {{-- LEFT MENU --}}
            <div class="w-64 shrink-0">

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4 space-y-3">

                    {{-- Compose --}}
                    <a href="{{ route('admin.messages.compose') }}"
                        class="flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">

                        <x-lucide-icon name="edit-3" class="w-4 h-4" />
                        Compose

                    </a>

                    {{-- Menu --}}
                    <div class="pt-2 space-y-1 text-sm">

                        <a wire:navigate href="{{ route('admin.messages.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl
                    {{ request('filter') == null ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">

                            <x-lucide-icon name="inbox" class="w-4 h-4" />
                            Inbox

                        </a>

                        <a wire:navigate href="{{ route('admin.messages.index', ['filter' => 'starred']) }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl
                    {{ request('filter') == 'starred' ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">

                            <x-lucide-icon name="star" class="w-4 h-4" />
                            Starred

                        </a>

                        <a wire:navigate href="{{ route('admin.messages.index', ['filter' => 'unread']) }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl
                    {{ request('filter') == 'unread' ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">

                            <x-lucide-icon name="mail" class="w-4 h-4" />
                            Unread

                        </a>

                    </div>

                </div>

            </div>



            {{-- RIGHT CONTENT --}}
            <div class="flex-1">

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                    {{-- Header --}}
                    <div class="flex items-center justify-between border-b border-slate-200 p-5">

                        <div class="text-sm font-semibold">
                            Messages
                        </div>

                        <div class="text-xs text-slate-500">
                            {{ $messages->total() }} messages
                        </div>

                    </div>



                    {{-- Message Table --}}
                    <div class="overflow-x-auto">

                        <table class="w-full text-left text-sm">

                            <thead class="bg-slate-50 text-xs font-semibold text-slate-600">

                                <tr>

                                    <th class="px-5 py-3 w-10"></th>
                                    <th class="px-5 py-3 w-56">Sender</th>
                                    <th class="px-5 py-3">Message</th>
                                    <th class="px-5 py-3 w-32 text-right">Date</th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-slate-200" wire:poll.10s>

                                @forelse($messages as $msg)
                                    <tr
                                        class="hover:bg-slate-50 cursor-pointer
                            {{ !$msg->is_read ? 'font-semibold bg-slate-50' : '' }}">

                                        {{-- STAR --}}
                                        <td class="px-5 py-4">

                                            <button wire:navigate
                                                onclick="event.stopPropagation();fetch('{{ route('admin.messages.star', $msg->id) }}',{
                                        method:'POST',
                                        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
                                    }).then(()=>location.reload())"
                                                class="text-slate-400 hover:text-amber-400">

                                                <x-lucide-icon :name="$msg->is_starred ? 'star' : 'star'" class="w-4 h-4 {{ $msg->is_starred ? 'fill-amber-400 text-amber-400' : '' }}" />

                                            </button>

                                        </td>



                                        {{-- SENDER --}}
                                        <td class="px-5 py-4">

                                            <a wire:navigate href="{{ route('admin.messages.show', $msg->id) }}"
                                                class="block">

                                                <div class="font-semibold text-slate-900">
                                                    {{ $msg->name }}
                                                </div>

                                                <div class="text-xs text-slate-500">
                                                    {{ $msg->email }}
                                                </div>

                                            </a>

                                        </td>



                                        {{-- MESSAGE --}}
                                        <td class="px-5 py-4">

                                            <a wire:navigate href="{{ route('admin.messages.show', $msg->id) }}"
                                                class="text-slate-600 truncate block">

                                                {{ \Illuminate\Support\Str::limit($msg->message, 90) }}

                                            </a>

                                        </td>



                                        {{-- DATE --}}
                                        <td class="px-5 py-4 text-right text-xs text-slate-500">

                                            {{ $msg->created_at->diffForHumans() }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-500">
                                            No messages found.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>



                    {{-- Pagination --}}
                    <div class="border-t border-slate-200 p-4">

                        {{ $messages->links() }}

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
