<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployerSubscription;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $status = trim((string) $request->query('status', ''));
        $q = trim((string) $request->query('q', '')); // employer name/email quick search

        $payments = Payment::query()
            ->with(['employer:id,name,email', 'plan:id,name,code,price'])
            ->when($status !== '', fn($qr) => $qr->where('status', $status))
            ->when($q !== '', function($qr) use ($q) {
                $qr->whereHas('employer', function($e) use ($q) {
                    $e->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('adminpage.contents.subscriptions.payments.index', compact('payments', 'status', 'q'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['employer:id,name,email', 'plan:id,name,code,price', 'verifiedBy:id,name,email']);

        return view('adminpage.contents.subscriptions.payments.show', compact('payment'));
    }

    public function markCompleted(Payment $payment)
    {
        abort_unless($payment->status === Payment::STATUS_PENDING, 422);

        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => Payment::STATUS_COMPLETED,
                'verified_by_admin_id' => auth('admin')->id(), // adjust if your admin guard differs
                'verified_at' => now(),
                'fail_reason' => null,
            ]);

            EmployerSubscription::create([
                'employer_id' => $payment->employer_id,
                'plan_id' => $payment->plan_id,
                'status' => EmployerSubscription::STATUS_ACTIVE,
                'starts_at' => now(),
                'ends_at' => now()->addDays(30),
                'activated_by_admin_id' => auth('admin')->id(),
                'activated_at' => now(),
            ]);
        });

        return back()->with('success', 'Payment verified. Subscription activated for 30 days.');
    }

    public function markFailed(Request $request, Payment $payment)
    {
        abort_unless($payment->status === Payment::STATUS_PENDING, 422);

        $data = $request->validate([
            'fail_reason' => ['required','string','max:255'],
        ]);

        $payment->update([
            'status' => Payment::STATUS_FAILED,
            'fail_reason' => $data['fail_reason'],
            'verified_by_admin_id' => auth('admin')->id(),
            'verified_at' => now(),
        ]);

        return back()->with('info', 'Payment marked as failed.');
    }
}