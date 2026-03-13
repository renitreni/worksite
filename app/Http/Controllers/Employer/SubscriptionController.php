<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Employer\EmployerSubscriptionService;

class SubscriptionController extends Controller
{
    public function __construct(
        private EmployerSubscriptionService $subscriptionService
    ) {}

    public function dashboard()
    {
        $data = $this->subscriptionService->getDashboardData();

        return view(
            'employer.contents.subscription.subscription-dashboard',
            $data
        );
    }

    public function selectPlan($planId)
    {
        return $this->subscriptionService->selectPlan($planId);
    }

    public function cancelPending($subscriptionId)
    {
        return $this->subscriptionService->cancelPending($subscriptionId);
    }

    public function payment($subscriptionId)
    {
        $subscription = $this->subscriptionService->getPaymentPage($subscriptionId);

        return view(
            'employer.contents.subscription.payment',
            compact('subscription')
        );
    }

    public function processPayment(Request $request, $subscriptionId)
    {
        return $this->subscriptionService
            ->processPayment($request, $subscriptionId);
    }
}