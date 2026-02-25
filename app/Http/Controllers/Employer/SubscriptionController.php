<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\EmployerSubscription;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Show the subscription dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        /** @var \App\Models\EmployerProfile|null $employerProfile */
        $employerProfile = $user?->employerProfile;
        if (!$employerProfile) {
            abort(404, 'Employer profile not found.');
        }

        /** @var \App\Models\EmployerProfile $employerProfileNonNull */
        $employerProfileNonNull = $employerProfile;

        // Get all subscriptions for this employer, latest first
        $subscriptions = EmployerSubscription::with('plan')
            ->where('employer_profile_id', $employerProfileNonNull->id)
            ->orderBy('ends_at', 'desc')
            ->get();

        // Assert all subscription plans exist
        foreach ($subscriptions as $sub) {
            assert($sub->plan !== null);
        }

        // Determine current subscription (latest active, or latest inactive)
        $currentSubscription = $subscriptions->firstWhere('subscription_status', 'active')
            ?? $subscriptions->firstWhere('subscription_status', 'inactive');

        // Get all plans and decode features safely
        $plans = SubscriptionPlan::all()->map(function ($plan) {
            /** @var string|null $planFeatures */
            $planFeatures = $plan->features;

            // Decode JSON, fallback to empty array if null
            $plan->features = $planFeatures
                ? json_decode($planFeatures, true)
                : [];

            return $plan;
        });

        return view('employer.contents.subscription.subscription-dashboard', [
            'subscriptions' => $subscriptions,
            'currentSubscription' => $currentSubscription,
            'plans' => $plans,
        ]);
    }

    /**
     * Select a plan and prepare subscription
     */
    public function selectPlan($planId)
    {
        $user = Auth::user();

        /** @var \App\Models\EmployerProfile|null $employerProfile */
        $employerProfile = $user?->employerProfile;
        if (!$employerProfile) {
            abort(404, 'Employer profile not found.');
        }

        /** @var \App\Models\EmployerProfile $employerProfileNonNull */
        $employerProfileNonNull = $employerProfile;

        // Find the selected plan
        $plan = SubscriptionPlan::findOrFail($planId);

        // Find or create subscription for this plan
        $subscription = EmployerSubscription::firstOrCreate(
            [
                'employer_profile_id' => $employerProfileNonNull->id,
                'plan_id' => $plan->id,
            ],
            [
                'subscription_status' => 'inactive',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]
        );

        // Reset dates if previously expired or canceled
        if (!$subscription->wasRecentlyCreated && in_array($subscription->subscription_status, ['expired', 'canceled'])) {
            $subscription->update([
                'subscription_status' => 'inactive',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]);
        }

        // Cancel old inactive plans, expire active plans
        EmployerSubscription::where('employer_profile_id', $employerProfileNonNull->id)
            ->where('id', '!=', $subscription->id)
            ->get()
            ->each(function ($sub) {
                if ($sub->subscription_status === 'inactive') {
                    $sub->update(['subscription_status' => 'canceled']);
                } elseif ($sub->subscription_status === 'active') {
                    $sub->update(['subscription_status' => 'expired']);
                }
            });

        return redirect()->route('employer.subscription.payment', $subscription->id)
                         ->with('success', 'Please complete your payment to activate the plan.');
    }

    /**
     * Show payment page for a subscription
     */
    public function payment($subscriptionId)
    {
        $user = Auth::user();

        /** @var \App\Models\EmployerProfile|null $employerProfile */
        $employerProfile = $user?->employerProfile;
        if (!$employerProfile) {
            abort(404, 'Employer profile not found.');
        }

        /** @var \App\Models\EmployerProfile $employerProfileNonNull */
        $employerProfileNonNull = $employerProfile;

        $subscription = EmployerSubscription::with('plan')->findOrFail($subscriptionId);

        // Assert plan exists to satisfy static analysis
        assert($subscription->plan !== null);

        return view('employer.contents.subscription.payment', compact('subscription'));
    }

    /**
     * Process payment submission
     */
    public function processPayment(Request $request, $subscriptionId)
    {
        $user = Auth::user();

        /** @var \App\Models\EmployerProfile|null $employerProfile */
        $employerProfile = $user?->employerProfile;
        if (!$employerProfile) {
            abort(404, 'Employer profile not found.');
        }

        /** @var \App\Models\EmployerProfile $employerProfileNonNull */
        $employerProfileNonNull = $employerProfile;

        $subscription = EmployerSubscription::with('plan')->findOrFail($subscriptionId);

        // Assert plan exists
        assert($subscription->plan !== null);

        // Create payment linked to subscription
        $subscription->payments()->create([
            'employer_id' => $employerProfileNonNull->user_id,
            'plan_id' => $subscription->plan_id,
            'amount' => $subscription->plan->price,
            'status' => 'pending',
            'reference' => $request->input('reference'),
        ]);

        return redirect()->route('employer.subscription.dashboard')
            ->with('success', "Payment submitted for {$subscription->plan->name}. Once verified, your subscription will be activated.");
    }
}