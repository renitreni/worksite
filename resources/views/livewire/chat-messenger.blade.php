<div class="flex flex-col h-full">

    {{-- MESSAGES --}}
    <div id="chatBox" class="flex-1 overflow-y-auto px-6 py-5 space-y-4 bg-gray-50">

        @foreach ($chats as $chat)
            @if ($chat->sender_id == auth()->id())
                {{-- OUTGOING --}}
                <div class="flex justify-end">

                    <div class="bg-green-500 text-white px-4 py-2 rounded-2xl max-w-xs shadow-sm">

                        <p class="text-sm">
                            {{ $chat->message }}
                        </p>

                        <p class="text-[10px] text-green-100 text-right mt-1">
                            {{ $chat->created_at->format('H:i') }}
                        </p>

                    </div>

                </div>
            @else
                {{-- INCOMING --}}
                <div class="flex items-start gap-2">

                    <img src="{{ $chat->sender->candidateProfile->avatar_url ?? $chat->sender->employerProfile->logo_url }}"
                        class="w-7 h-7 rounded-full" />

                    <div class="bg-white px-4 py-2 rounded-2xl max-w-xs shadow-sm">

                        <p class="text-sm text-gray-700">
                            {{ $chat->message }}
                        </p>

                        <p class="text-[10px] text-gray-400 mt-1">
                            {{ $chat->created_at->format('H:i') }}
                        </p>

                    </div>

                </div>
            @endif
        @endforeach

        <div id="chat-bottom"></div>


    </div>



    {{-- INPUT --}}
    @if ($canReply)
        <form wire:submit.prevent="sendMessage" class="bg-white border-t p-4 flex items-center gap-3">

            <input type="text" wire:model="message" placeholder="Type a message..."
                class="flex-1 bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none" />

            <button class="bg-green-500 text-white px-4 py-2 rounded-full text-sm hover:bg-green-600">

                Send

            </button>

        </form>
    @else
        <div class="p-4 text-center text-gray-500 text-sm border-t bg-white">
            Waiting for employer to start the conversation.
        </div>
    @endif

</div>
