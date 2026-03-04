<?php

namespace App\Events;

use App\Models\JobApplication;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $application;

    public function __construct(JobApplication $application)
    {
        $this->application = $application->load('candidateProfile.user', 'jobPost');
    }

    public function broadcastOn()
    {
        return new PrivateChannel('employer.' . $this->application->jobPost->employer_id);
    }

    public function broadcastAs()
    {
        return 'application.status.changed';
    }
}