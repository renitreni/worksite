<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use App\Events\ChatMessageSent;

class ChatMessenger extends Component
{
    public $application;
    public $message = '';
    public $chats = [];
    public $canReply;

    public function mount($application, $canReply)
    {
        $this->application = $application;
        $this->canReply = $canReply;

        $this->loadMessages();
    }

    public function getListeners()
    {
        return [
            "echo:chat.{$this->application->id},ChatMessageSent" => 'messageReceived'
        ];
    }

    public function messageReceived()
    {
        $this->loadMessages();

        // 🔥 scroll for receiver
        $this->dispatch('scrollToBottom');
    }

    public function loadMessages()
    {
        $this->chats = Chat::where('job_application_id', $this->application->id)
            ->with('sender')
            ->orderBy('created_at')
            ->get();
    }

    public function sendMessage()
    {
        if (!$this->canReply) return;

        $chat = Chat::create([
            'job_application_id' => $this->application->id,
            'sender_id' => Auth::id(),
            'message' => $this->message
        ]);

        broadcast(new ChatMessageSent($chat))->toOthers();

        $this->message = '';

        $this->loadMessages();

        // 🔥 scroll for sender
        $this->dispatch('scrollToBottom');
    }

    public function render()
    {
        return view('livewire.chat-messenger');
    }
}