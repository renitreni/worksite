<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use App\Services\Admin\AdminMessageService;

class MessageController extends Controller
{
    public function __construct(
        private AdminMessageService $messageService
    ) {}

    public function index(Request $request)
    {
        $data = $this->messageService->getMessages($request);

        return view(
            'adminpage.contents.message',
            $data
        );
    }

    public function show(ContactMessage $message)
    {
        $this->messageService->markAsRead($message);

        return view(
            'adminpage.contents.messages.show',
            compact('message')
        );
    }

    public function toggleStar(ContactMessage $message)
    {
        $this->messageService->toggleStar($message);

        return back();
    }

    public function reply(ContactMessage $message, Request $request)
    {
        $this->messageService->replyToMessage($request, $message);

        return back()->with('success', 'Reply sent successfully');
    }

    public function compose()
    {
        return view('adminpage.contents.messages.compose');
    }

    public function send(Request $request)
    {
        $this->messageService->sendEmail($request);

        return redirect()
            ->route('admin.messages.index')
            ->with('success', 'Email sent successfully');
    }

    public function destroy(ContactMessage $message)
    {
        $this->messageService->deleteMessage($message);

        return redirect()
            ->route('admin.messages.index')
            ->with('success', 'Message deleted successfully');
    }
}