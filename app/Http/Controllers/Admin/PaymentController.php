<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployerSubscription;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $status = trim((string) $request->query('status', ''));
        $q = trim((string) $request->query('q', ''));
        $method = trim((string) $request->query('method', '')); // ✅ new filter

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

        return view('adminpage.contents.subscriptions.payments.index', compact('payments', 'status', 'q', 'method'));
    }

    public function show(Payment $payment)
    {
        $payment->load([
            'employer:id,name,email',
            'employer.employerProfile:id,user_id,company_name',
            'plan:id,name,code,price',
            'subscription:id,employer_profile_id,plan_id,subscription_status,starts_at,ends_at',
            'verifiedBy:id,name,email',
        ]);

        return view('adminpage.contents.subscriptions.payments.show', compact('payment'));
    }

    public function markCompleted(Payment $payment)
    {
        abort_unless($payment->status === Payment::STATUS_PENDING, 422, 'Only pending payments can be completed.');

        DB::transaction(function () use ($payment) {

            // ✅ must have subscription linked (you set subscription_id in employer controller)
            $payment->load(['subscription', 'plan']);

            if (!$payment->subscription) {
                abort(422, 'Payment has no linked subscription.');
            }

            // 1) mark payment as completed
            $payment->update([
                'status' => Payment::STATUS_COMPLETED,
                'verified_by_admin_id' => Auth::id(),
                'verified_at' => now(),
                'fail_reason' => null,
            ]);

            $sub = $payment->subscription;

            // 2) expire other active subscriptions for that employer profile (optional but recommended)
            EmployerSubscription::where('employer_profile_id', $sub->employer_profile_id)
                ->where('id', '!=', $sub->id)
                ->where('subscription_status', EmployerSubscription::STATUS_ACTIVE)
                ->update([
                    'subscription_status' => EmployerSubscription::STATUS_EXPIRED,
                ]);

            // 3) activate selected subscription for 30 days (or 1 month)
            $sub->update([
                'subscription_status' => EmployerSubscription::STATUS_ACTIVE,
                'starts_at' => now(),
                'ends_at' => now()->addDays(30),
            ]);
        });

        return back()->with('success', 'Payment verified. Subscription activated.');
    }

    public function markFailed(Request $request, Payment $payment)
    {
        abort_unless($payment->status === Payment::STATUS_PENDING, 422, 'Only pending payments can be failed.');

        $data = $request->validate([
            'fail_reason' => ['required', 'string', 'max:255'],
        ]);

        $payment->update([
            'status' => Payment::STATUS_FAILED,
            'fail_reason' => $data['fail_reason'],
            'verified_by_admin_id' => Auth::id(),
            'verified_at' => now(),
        ]);

        // optional: keep subscription inactive if payment failed
        // if ($payment->subscription) {
        //     $payment->subscription->update(['subscription_status' => EmployerSubscription::STATUS_INACTIVE]);
        // }

        return back()->with('info', 'Payment marked as failed.');
    }
}
