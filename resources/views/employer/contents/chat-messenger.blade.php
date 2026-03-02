@extends('employer.layout')

@section('content')
<div class="container mx-auto p-4 max-w-7xl flex flex-col md:flex-row gap-4">

    {{-- Left Panel: Applicants List --}}
    <div class="w-full md:w-1/3 border rounded shadow bg-white overflow-y-auto h-[80vh]">
        <div class="p-4 font-bold border-b">Applicants</div>
        <ul>
            @foreach($applications as $app)
                <li>
                    <a href="{{ route('employer.chat.index', $app->id) }}"
                       class="block px-4 py-3 hover:bg-gray-100 flex justify-between items-center {{ $app->id == $application->id ? 'bg-gray-100' : '' }}">
                        <div>
                            <p class="font-semibold text-gray-900">{{ optional($app->candidateProfile)->user?->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-500">{{ optional($app->jobPost)->title ?? 'Unknown Job' }}</p>
                        </div>
                        @if($app->unread_count ?? 0)
                            <span class="bg-emerald-500 text-white text-xs px-2 py-1 rounded-full">{{ $app->unread_count }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Right Panel: Chat --}}
    <div class="w-full md:w-2/3 border rounded shadow bg-white flex flex-col h-[80vh]">

        {{-- Chat Header --}}
        <div class="p-4 font-bold border-b flex justify-between items-center">
            <div>
                Chat with {{ optional($application->candidateProfile)->user?->name ?? 'Unknown' }}
                <span class="text-sm text-gray-500 block">{{ optional($application->jobPost)->title ?? 'Unknown Job' }}</span>
            </div>
        </div>

        {{-- Messages --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
            @foreach($chats as $chat)
                <div class="flex {{ $chat->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="p-2 rounded-lg max-w-xs
                        {{ $chat->sender_id == auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900' }}">
                        <strong class="block text-sm">{{ $chat->sender?->name ?? 'Unknown' }}</strong>
                        <span>{{ $chat->message }}</span>
                        <div class="text-xs text-gray-400 mt-1 text-right">{{ $chat->created_at->format('H:i') }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Input Form --}}
        <form action="{{ route('employer.chat.store', $application->id) }}" method="POST"
              class="p-4 border-t flex items-center gap-2 bg-white">
            @csrf
            <input type="text" name="message"
                   class="flex-1 border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                   placeholder="Type your message..." required>
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Send
            </button>
        </form>

    </div>
</div>

<script>
    // Auto-scroll to bottom
    const chatContainer = document.getElementById('chat-messages');
    chatContainer.scrollTop = chatContainer.scrollHeight;
</script>
@endsection