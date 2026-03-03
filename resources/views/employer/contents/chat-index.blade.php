{{-- resources/views/employer/contents/chat-index.blade.php --}}
@extends('employer.layout')

@section('content')
<div class="container mx-auto p-4 max-w-6xl flex gap-4">

    {{-- Left Panel: Applicants List --}}
    <div class="w-1/3 border rounded shadow bg-white overflow-y-auto h-[80vh]">
        <div class="p-4 font-bold border-b">Applicants</div>
        <ul>
            @foreach($applications as $application)
                <li>
                    <a href="{{ route('employer.chat.show', $application->id) }}" class="block px-4 py-3 hover:bg-gray-100 flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-900">{{ optional($application->candidateProfile)->user?->name ?? 'Unknown Candidate' }}</p>
                            <p class="text-sm text-gray-500">{{ optional($application->jobPost)->title ?? 'Unknown Job' }}</p>
                        </div>
                        <div class="text-sm text-gray-400">&rarr;</div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Right Panel: Chat Messages (empty by default, or you can load latest) --}}
    <div class="w-2/3 border rounded shadow bg-white flex flex-col h-[80vh]">
        <div class="p-4 font-bold border-b" id="chat-header">
            Select an applicant to start chat
        </div>

        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-2">
            {{-- Messages will appear here if loaded --}}
        </div>

        <form id="chat-form" action="#" method="POST" class="p-4 border-t flex" style="display: none;">
            @csrf
            <input type="text" name="message" class="flex-1 border rounded px-3 py-2 focus:outline-none" placeholder="Type your message..." required>
            <button type="submit" class="ml-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Send
            </button>
        </form>
    </div>

</div>
@endsection