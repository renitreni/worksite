@extends('employer.layout')

@section('content')
<div class="container mx-auto p-4 max-w-6xl flex gap-4">

    {{-- Left Panel: Applicant List (optional, for consistent layout) --}}
    <div class="w-1/3 border rounded shadow bg-white overflow-y-auto h-[80vh]">
        {{-- You can reuse the chat-index list here --}}
        <div class="p-4 font-bold border-b">Applicants</div>
    </div>

    {{-- Right Panel: Chat --}}
    <div class="w-2/3 border rounded shadow bg-white flex flex-col h-[80vh]">

        {{-- Chat Header --}}
        <div class="p-4 font-bold border-b">
            Chat with {{ optional($application->candidateProfile)->user?->name ?? 'Unknown Candidate' }}
            <span class="text-sm text-gray-500 block">{{ optional($application->jobPost)->title ?? 'Unknown Job' }}</span>
        </div>

        {{-- Messages --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-2 bg-gray-50">
            @foreach($chats as $chat)
                <div class="flex {{ $chat->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="p-2 rounded-lg max-w-xs
                        {{ $chat->sender_id == auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900' }}">
                        <strong class="block text-sm">{{ $chat->sender?->name ?? 'Unknown' }}</strong>
                        <span>{{ $chat->message }}</span>
                        <div class="text-xs text-gray-500 mt-1 text-right">{{ $chat->created_at->format('H:i') }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Form --}}
        <form action="{{ route('employer.chat.store', $application->id) }}" method="POST" class="p-4 border-t flex">
            @csrf
            <input type="text" name="message" class="flex-1 border rounded px-3 py-2 focus:outline-none" placeholder="Type your message..." required>
            <button type="submit" class="ml-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Send
            </button>
        </form>

    </div>
</div>

<script>
    const chatContainer = document.getElementById('chat-messages');
    chatContainer.scrollTop = chatContainer.scrollHeight;
</script>
@endsection