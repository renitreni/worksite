<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ApplicationStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $application)
    {
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        $status = strtolower($this->application->status);
        $jobTitle = $this->application->jobPost->title ?? 'your applied job';

        return match ($status) {

            'hired' => [
                'title' => '🎉 Congratulations! You\'re Hired!',
                'body' => "Great news! You've been hired for \"{$jobTitle}\". Welcome aboard!",
                'status' => 'hired',
            ],

            'shortlisted' => [
                'title' => '📋 You\'ve Been Shortlisted!',
                'body' => "Your application for \"{$jobTitle}\" has been shortlisted.",
                'status' => 'shortlisted',
            ],

            'interview' => [
                'title' => '📅 Interview Stage Reached',
                'body' => "Your application for \"{$jobTitle}\" moved to interview stage.",
                'status' => 'interview',
            ],

            'rejected' => [
                'title' => '❌ Application Update',
                'body' => "Your application for \"{$jobTitle}\" was not selected.",
                'status' => 'rejected',
            ],

            default => [
                'title' => 'Application Update',
                'body' => "Your application for \"{$jobTitle}\" was updated.",
                'status' => $status,
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