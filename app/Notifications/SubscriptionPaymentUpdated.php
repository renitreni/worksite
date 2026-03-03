<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class SubscriptionPaymentUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $payment,
        public string $type // pending | approved | failed
    ) {}

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        $planName = $this->payment->plan->name ?? 'your selected plan';

        return match ($this->type) {

            'pending' => [
                'title' => '🧾 Subscription Submitted',
                'body' => "Your subscription for \"{$planName}\" has been submitted and is awaiting admin approval.",
                'status' => 'subscription_pending',
            ],

            'approved' => [
                'title' => '✅ Subscription Activated',
                'body' => "Great news! Your subscription for \"{$planName}\" has been approved and is now active.",
                'status' => 'subscription_approved',
            ],

            'failed' => [
                'title' => '❌ Subscription Payment Rejected',
                'body' => "Unfortunately, your payment for \"{$planName}\" was not approved. Please review and try again.",
                'status' => 'subscription_failed',
            ],

            default => [
                'title' => 'Subscription Update',
                'body' => "Your subscription status has been updated.",
                'status' => $this->type,
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