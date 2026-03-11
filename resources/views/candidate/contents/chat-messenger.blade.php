@extends('candidate.layout')

@section('content')
    <div class="space-y-6">

        {{-- CHAT CONTAINER --}}
        <div class="rounded-2xl bg-white border border-gray-200 overflow-hidden">

            <div class="flex h-[600px]" x-data="{ openChat: {{ $application ? 'true' : 'false' }} }">

                {{-- LEFT : CONVERSATIONS --}}
                <div class="w-full md:w-[340px] border-r border-gray-200 overflow-y-auto"
                    :class="openChat ? 'hidden md:block' : 'block'">

                    <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">

                        <p class="font-semibold text-gray-800">
                            Conversations
                        </p>

                        @if ($applications->sum('unread_count') > 0)
                            <span class="bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full">
                                {{ $applications->sum('unread_count') }}
                            </span>
                        @endif

                    </div>



                    <div class="divide-y">

                        @foreach ($applications as $app)
                            @php
                                $lastChat = $app->chats->last();
                            @endphp

                            <a href="{{ route('candidate.chat.index', $app->id) }}"
                                class="flex items-center gap-3 px-5 py-4 hover:bg-gray-50 transition
{{ $application && $application->id == $app->id ? 'bg-gray-50' : '' }}">

                                <img src="{{ optional($app->jobPost->employerProfile)->logo_url }}"
                                    class="w-10 h-10 rounded-full object-cover border" />

                                <div class="flex-1 min-w-0">

                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ optional($app->jobPost)->title }}
                                    </p>

                                    <p class="text-xs text-gray-500 truncate">
                                        {{ optional($app->jobPost->employerProfile)->company_name }}
                                    </p>

                                    @if ($lastChat)
                                        <p class="text-xs text-gray-400 truncate mt-1">
                                            {{ Str::limit($lastChat->message, 40) }}
                                        </p>
                                    @endif

                                </div>

                                @if ($app->unread_count)
                                    <span class="bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full">
                                        {{ $app->unread_count }}
                                    </span>
                                @endif

                            </a>
                        @endforeach

                    </div>

                </div>



                {{-- RIGHT : CHAT WINDOW --}}
                <div class="flex-1 flex flex-col" :class="openChat ? 'block' : 'hidden md:flex'">

                    @if ($application)
                        {{-- CHAT HEADER --}}
                        <div class="border-b border-gray-200 px-6 py-4 flex items-center gap-3">

                            <button @click="openChat=false" class="md:hidden text-gray-400">
                                ←
                            </button>

                            <img src="{{ optional($application->jobPost->employerProfile)->logo_url }}"
                                class="w-9 h-9 rounded-full object-cover border" />

                            <div>

                                <p class="text-sm font-semibold text-gray-900">
                                    {{ optional($application->jobPost)->title }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ optional($application->jobPost->employerProfile)->company_name }}
                                </p>

                            </div>

                        </div>



                        {{-- CHAT COMPONENT --}}
                        <div class="flex-1 overflow-hidden">

                            <livewire:chat-messenger :application="$application" :canReply="$canReply" />

                        </div>
                    @else
                        <div class="flex items-center justify-center h-full text-gray-500 text-sm">
                            Select a conversation
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

    <script>
        function scrollChatToBottom() {
            const bottom = document.getElementById("chat-bottom");

            if (bottom) {
                bottom.scrollIntoView({
                    behavior: "auto",
                    block: "end"
                });
            }
        }

        // page load
        window.addEventListener("load", () => {
            setTimeout(scrollChatToBottom, 200);
        });

        // Livewire event
        document.addEventListener("livewire:init", () => {
            Livewire.on('scrollToBottom', () => {
                setTimeout(scrollChatToBottom, 50);
            });
        });
    </script>
@endsection
