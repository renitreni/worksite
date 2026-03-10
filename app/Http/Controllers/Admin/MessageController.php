<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        // FILTER STARRED
        if ($request->filter === 'starred') {
            $query->where('is_starred', true);
        }

        // FILTER UNREAD
        if ($request->filter === 'unread') {
            $query->where('is_read', false);
        }

        $messages = $query
            ->orderByDesc('is_starred')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $unreadCount = ContactMessage::where('is_read', false)->count();

        return view('adminpage.contents.message', compact('messages', 'unreadCount'));
    }

    public function show(ContactMessage $message)
    {
        $message->update([
            'is_read' => true
        ]);

        return view('adminpage.contents.messages.show', compact('message'));
    }

    public function toggleStar(ContactMessage $message)
    {
        $message->update([
            'is_starred' => !$message->is_starred
        ]);

        return back();
    }

    public function reply(ContactMessage $message, Request $request)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);

        Mail::raw($request->reply, function ($mail) use ($message) {

            $mail->to($message->email)
                ->subject('Reply from JobAbroad Support');

        });

        return back()->with('success', 'Reply sent successfully');
    }

    public function compose()
    {
        return view('adminpage.contents.messages.compose');
    }


    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        Mail::raw($request->message, function ($mail) use ($request) {

            $mail->to($request->email)
                ->subject($request->subject);

        });

        return redirect()
            ->route('admin.messages.index')
            ->with('success', 'Email sent successfully');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()
            ->route('admin.messages.index')
            ->with('success', 'Message deleted successfully');
    }
}