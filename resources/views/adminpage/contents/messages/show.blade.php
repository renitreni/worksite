@extends('adminpage.layout')

@section('title','Message')
@section('page_title','Message')

@section('content')

<div class="space-y-6" x-data="{ replyOpen:false }">

    {{-- Back --}}
    <a wire:navigate
       href="{{ route('admin.messages.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700">

        <x-lucide-icon name="arrow-left" class="w-4 h-4" />
        Back to Inbox

    </a>



    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Header --}}
        <div class="border-b border-slate-200 p-6">

            <div class="flex items-start justify-between">

                <div>
                    <div class="text-base font-semibold text-slate-900">
                        {{ $message->name }}
                    </div>

                    <div class="text-xs text-slate-500 mt-1">
                        {{ $message->email }}
                    </div>
                </div>

                <div class="text-xs text-slate-500">
                    {{ $message->created_at->format('F d, Y h:i A') }}
                </div>

            </div>

            <div class="mt-3">

                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1
                bg-emerald-50 text-emerald-700 ring-emerald-200">

                    {{ ucfirst($message->role) }}

                </span>

            </div>

        </div>


        <h3 class="text-sm font-medium text-slate-900 px-6 mt-4">
            Message 
        </h3>
        {{-- Message Body --}}
        <div class="p-6">

            <div class="max-w-4xl bg-slate-50 border border-slate-200 rounded-xl p-5 text-sm text-slate-700 leading-relaxed whitespace-pre-line">

                {{ $message->message }}

            </div>

        </div>



        {{-- Actions --}}
        <div class="border-t border-slate-200 p-5 flex items-center justify-between">

            <div class="flex items-center gap-3">

                {{-- Reply button --}}
                <button
                    @click="replyOpen = !replyOpen"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">

                    <x-lucide-icon name="reply" class="w-4 h-4" />
                    Reply

                </button>

            </div>



            {{-- Delete --}}
            <form method="POST" action="{{ route('admin.messages.destroy',$message->id) }}">
                @csrf
                @method('DELETE')

                <button
                    class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100">

                    <x-lucide-icon name="trash-2" class="w-4 h-4" />
                    Delete

                </button>

            </form>

        </div>



        {{-- Reply Form --}}
        <div x-show="replyOpen" x-transition class="border-t border-slate-200 p-6">

            <form method="POST" action="{{ route('admin.messages.reply',$message->id) }}" class="space-y-4">

                @csrf

                <textarea
                    name="reply"
                    rows="6"
                    required
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700
                    focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                    placeholder="Type your reply..."></textarea>


                <div class="flex justify-end">

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">

                        <x-lucide-icon name="send" class="w-4 h-4" />
                        Send Reply

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection