<?php

namespace App\Services\Admin;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminMessageService
{
    public function getMessages(Request $request): array
    {
        $query = ContactMessage::query();

        if ($request->filter === 'starred') {
            $query->where('is_starred', true);
        }

        if ($request->filter === 'unread') {
            $query->where('is_read', false);
        }

        $messages = $query
            ->orderByDesc('is_starred')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $unreadCount = ContactMessage::where('is_read', false)->count();

        return compact('messages','unreadCount');
    }

    public function markAsRead(ContactMessage $message): void
    {
        $message->update([
            'is_read' => true
        ]);
    }

    public function toggleStar(ContactMessage $message): void
    {
        $message->update([
            'is_starred' => !$message->is_starred
        ]);
    }

    public function replyToMessage(Request $request, ContactMessage $message): void
    {
        $data = $request->validate([
            'reply' => ['required','string']
        ]);

        Mail::raw($data['reply'], function ($mail) use ($message) {

            $mail->to($message->email)
                 ->subject('Reply from JobAbroad Support');

        });
    }

    public function sendEmail(Request $request): void
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'subject' => ['required','string','max:255'],
            'message' => ['required','string']
        ]);

        Mail::raw($data['message'], function ($mail) use ($data) {

            $mail->to($data['email'])
                 ->subject($data['subject']);

        });
    }

    public function deleteMessage(ContactMessage $message): void
    {
        $message->delete();
    }
}