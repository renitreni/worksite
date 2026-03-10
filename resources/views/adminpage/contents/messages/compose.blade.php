@extends('adminpage.layout')

@section('title','Compose Email')
@section('page_title','Compose Email')

@section('content')

<div class="space-y-6">

    {{-- Back --}}
    <a wire:navigate
       href="{{ route('admin.messages.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700">

        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to Inbox

    </a>



    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Header --}}
        <div class="border-b border-slate-200 p-5">

            <div class="text-sm font-semibold">
                Compose Email
            </div>

            <div class="text-xs text-slate-500 mt-1">
                Send an email directly from the admin panel.
            </div>

        </div>



        <form method="POST"
              action="{{ route('admin.messages.send') }}"
              class="p-6 space-y-5">

            @csrf


            {{-- To --}}
            <div class="space-y-1">

                <label class="text-sm font-semibold text-slate-700">
                    Recipient Email
                </label>

                <input
                    type="email"
                    name="email"
                    required
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm
                    text-slate-700 placeholder:text-slate-400
                    focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                    placeholder="example@email.com">

            </div>



            {{-- Subject --}}
            <div class="space-y-1">

                <label class="text-sm font-semibold text-slate-700">
                    Subject
                </label>

                <input
                    type="text"
                    name="subject"
                    required
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm
                    text-slate-700 placeholder:text-slate-400
                    focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                    placeholder="Email subject">

            </div>



            {{-- Message --}}
            <div class="space-y-1">

                <label class="text-sm font-semibold text-slate-700">
                    Message
                </label>

                <textarea
                    name="message"
                    rows="8"
                    required
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm
                    text-slate-700 placeholder:text-slate-400
                    focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                    placeholder="Write your email message..."></textarea>

            </div>



            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4">

                <a
                    wire:navigate
                    href="{{ route('admin.messages.index') }}"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2
                    text-sm font-semibold text-slate-700 hover:bg-slate-50">

                    Cancel

                </a>


                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl
                    bg-emerald-600 px-4 py-2 text-sm font-semibold
                    text-white hover:bg-emerald-700">

                    <i data-lucide="send" class="w-4 h-4"></i>
                    Send Email

                </button>

            </div>

        </form>

    </div>

</div>

@endsection