@extends('candidate.layout')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow p-6">
    <h1 class="text-xl font-semibold mb-6">All Notifications</h1>

    @forelse ($notifications as $notification)
        <div class="border-b py-4">
            <p class="font-semibold">
                {{ $notification->data['title'] ?? '' }}
            </p>

            <p class="text-sm text-gray-600">
                {{ $notification->data['body'] ?? '' }}
            </p>

            <p class="text-xs text-gray-400 mt-1">
                {{ $notification->created_at->diffForHumans() }}
            </p>
        </div>
    @empty
        <p class="text-gray-500">No notifications yet.</p>
    @endforelse

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
@endsection