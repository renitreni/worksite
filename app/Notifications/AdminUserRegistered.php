<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AdminUserRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $user
    ) {}

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return match ($this->user->role) {

            'employer' => [
                'title' => '🏢 New Employer Registered',
                'body' => "{$this->user->name} ({$this->user->email}) has registered as an employer.",
                'status' => 'employer_registered',
                'user_id' => $this->user->id,
            ],

            'candidate' => [
                'title' => '👤 New Candidate Registered',
                'body' => "{$this->user->name} ({$this->user->email}) has registered as a candidate.",
                'status' => 'candidate_registered',
                'user_id' => $this->user->id,
            ],

            default => [
                'title' => 'New User Registered',
                'body' => "{$this->user->name} has created an account.",
                'status' => 'user_registered',
                'user_id' => $this->user->id,
            ],
        };
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage(
            $this->toArray($notifiable)
        );
    }
}