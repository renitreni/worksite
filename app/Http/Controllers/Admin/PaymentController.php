<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\Admin\AdminPaymentService;

class PaymentController extends Controller
{
    public function __construct(
        private AdminPaymentService $paymentService
    ) {}

    public function index(Request $request)
    {
        $data = $this->paymentService->getPayments($request);

        return view(
            'adminpage.contents.subscriptions.payments.index',
            $data
        );
    }

    public function show(Payment $payment)
    {
        $payment = $this->paymentService->getPaymentDetails($payment);

        return view(
            'adminpage.contents.subscriptions.payments.show',
            compact('payment')
        );
    }

    public function markCompleted(Payment $payment)
    {
        $this->paymentService->approvePayment($payment);

        return back()->with(
            'success',
            'Payment verified. Subscription activated.'
        );
    }

    public function markFailed(Request $request, Payment $payment)
    {
        $this->paymentService->failPayment($request, $payment);

        return back()->with('info', 'Payment marked as failed.');
    }
}