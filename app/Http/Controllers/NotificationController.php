<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'notifications' => $user->notifications()
                ->latest()
                ->take(10)
                ->get()
                ->map(fn($n) => [
                    'id' => $n->id,
                    'title' => $n->data['title'] ?? '',
                    'body' => $n->data['body'] ?? $n->data['message'] ?? '',
                    'status' => $n->data['status'] ?? $n->data['action'] ?? '',
                    'time' => $n->created_at?->diffForHumans() ?? '',
                    'read' => $n->read_at !== null,
                ]),
            'unread' => $user->unreadNotifications()->count(),
        ]);
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markSingleRead(Request $request, $id)
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function all(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }
}