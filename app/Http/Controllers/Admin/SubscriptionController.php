<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployerSubscription;
use Illuminate\Http\Request;
use App\Services\Admin\AdminSubscriptionService;

class SubscriptionController extends Controller
{
    public function __construct(
        private AdminSubscriptionService $subscriptionService
    ) {}

    public function index(Request $request)
    {
        $data = $this->subscriptionService->getSubscriptions($request);

        return view(
            'adminpage.contents.subscriptions.subscriptions.index',
            $data
        );
    }

    public function activate(EmployerSubscription $subscription)
    {
        $this->subscriptionService->activateSubscription($subscription);

        return back()->with(
            'success',
            'Subscription activated and payment marked as completed.'
        );
    }

    public function suspend(Request $request, EmployerSubscription $subscription)
    {
        $this->subscriptionService->suspendSubscription($request, $subscription);

        return back()->with('warning', 'Subscription suspended.');
    }

    public function expired(Request $request)
    {
        $data = $this->subscriptionService->getExpiredSubscriptions($request);

        return view(
            'adminpage.contents.subscriptions.subscriptions.expired',
            $data
        );
    }
}