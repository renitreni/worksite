<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class JobPostStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $jobPost,
        public string $action, // hold, unhold, disable, enable
        public ?string $reason = null
    ) {
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        $title = $this->jobPost->title ?? 'your job post';

        return match ($this->action) {

            'hold' => [
                'title' => '⏳ Job Post Under Review',
                'body' => "Hi there 👋 We've temporarily placed \"{$title}\" on hold while we review it.",
                'status' => 'hold',
                'job_post_id' => $this->jobPost->id,
            ],

            'unhold' => [
                'title' => '🎉 Job Post Live Again!',
                'body' => "Good news! \"{$title}\" is now live and visible to candidates.",
                'status' => 'unhold',
                'job_post_id' => $this->jobPost->id,
            ],

            'disable' => [
                'title' => '🚫 Job Post Disabled',
                'body' => "We're sorry 😔 but \"{$title}\" has been disabled due to policy concerns.",
                'status' => 'disable',
                'job_post_id' => $this->jobPost->id,
            ],

            'enable' => [
                'title' => '✅ Job Post Reactivated',
                'body' => "Thanks for your patience 🙌 \"{$title}\" has been reactivated and is now available again.",
                'status' => 'enable',
                'job_post_id' => $this->jobPost->id,
            ],

            default => [
                'title' => 'Job Post Update',
                'body' => "Your job post \"{$title}\" was updated.",
                'status' => $this->action,
                'job_post_id' => $this->jobPost->id,
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