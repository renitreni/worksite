<?php

namespace App\Services\Admin;

use App\Models\Payment;
use App\Models\EmployerSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\SubscriptionPaymentUpdated;
use App\Mail\SubscriptionPaymentApprovedMail;
use App\Mail\SubscriptionPaymentFailedMail;

class AdminPaymentService
{
    public function getPayments(Request $request): array
    {
        $status = trim((string) $request->query('status', ''));
        $q = trim((string) $request->query('q', ''));
        $method = trim((string) $request->query('method', ''));

        $payments = Payment::query()
            ->with([
                'employer:id,name,email',
                'employer.employerProfile:id,user_id,company_name',
                'plan:id,name,code,price',
                'subscription:id,employer_profile_id,plan_id,subscription_status,starts_at,ends_at',
            ])
            ->when($status !== '', fn($qr) => $qr->where('status', $status))
            ->when($method !== '', fn($qr) => $qr->where('method', $method))
            ->when($q !== '', function ($qr) use ($q) {
                $qr->whereHas('employer', function ($e) use ($q) {
                    $e->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                })
                ->orWhereHas('employer.employerProfile', function ($ep) use ($q) {
                    $ep->where('company_name', 'like', "%{$q}%");
                });
            })
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return compact('payments','status','q','method');
    }

    public function getPaymentDetails(Payment $payment): Payment
    {
        $payment->load([
            'employer:id,name,email',
            'employer.employerProfile:id,user_id,company_name',
            'plan:id,name,code,price',
            'subscription:id,employer_profile_id,plan_id,subscription_status,starts_at,ends_at',
            'verifiedBy:id,name,email',
        ]);

        return $payment;
    }

    public function approvePayment(Payment $payment): void
    {
        abort_unless(
            $payment->status === Payment::STATUS_PENDING,
            422,
            'Only pending payments can be completed.'
        );

        DB::transaction(function () use ($payment) {

            $payment->load(['subscription','plan']);

            if (!$payment->subscription) {
                abort(422, 'Payment has no linked subscription.');
            }

            $payment->update([
                'status' => Payment::STATUS_COMPLETED,
                'verified_by_admin_id' => Auth::id(),
                'verified_at' => now(),
                'fail_reason' => null,
            ]);

            $sub = $payment->subscription;

            EmployerSubscription::where(
                'employer_profile_id',
                $sub->employer_profile_id
            )
            ->where('id','!=',$sub->id)
            ->where(
                'subscription_status',
                EmployerSubscription::STATUS_ACTIVE
            )
            ->update([
                'subscription_status' =>
                EmployerSubscription::STATUS_EXPIRED
            ]);

            $sub->update([
                'subscription_status' =>
                EmployerSubscription::STATUS_ACTIVE,
                'starts_at' => now(),
                'ends_at' => now()->addDays(30),
            ]);
        });

        $payment->load(['employer','plan']);

        $payment->employer?->notify(
            new SubscriptionPaymentUpdated($payment,'approved')
        );

        Mail::to($payment->employer->email)
            ->send(new SubscriptionPaymentApprovedMail($payment));
    }

    public function failPayment(Request $request, Payment $payment): void
    {
        abort_unless(
            $payment->status === Payment::STATUS_PENDING,
            422,
            'Only pending payments can be failed.'
        );

        $data = $request->validate([
            'fail_reason' => ['required','string','max:255']
        ]);

        $payment->update([
            'status' => Payment::STATUS_FAILED,
            'fail_reason' => $data['fail_reason'],
            'verified_by_admin_id' => Auth::id(),
            'verified_at' => now(),
        ]);

        $payment->load(['employer','plan']);

        $payment->employer?->notify(
            new SubscriptionPaymentUpdated($payment,'failed')
        );

        Mail::to($payment->employer->email)
            ->send(new SubscriptionPaymentFailedMail($payment));
    }
}