<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\EmployerSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    /**
     * Show the subscription dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $employerProfile = $user->employerProfile;
        abort_if(!$employerProfile, 404, 'Employer profile not found.');

        // ✅ Get all subscriptions for this employer (include soft-deleted plans)
        $subscriptions = EmployerSubscription::with([
            'plan' => fn($q) => $q->withTrashed()->with(['featureValues.definition']),
            'payments' => fn($q) => $q->latest('id'), // optional: show last payment status in UI
        ])
            ->where('employer_profile_id', $employerProfile->id)
            ->orderBy('ends_at', 'desc')
            ->get()
            ->filter(fn($s) => $s->plan !== null)
            ->values();

        // ✅ Determine current subscription (prefer active, else inactive, else latest)
        $currentSubscription =
            $subscriptions->firstWhere('subscription_status', 'active')
            ?? $subscriptions->firstWhere('subscription_status', 'inactive')
            ?? $subscriptions->first();

        // ✅ Available plans
        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->with(['featureValues.definition'])
            ->orderBy('price')
            ->get();

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
        abort_if(!$user, 403);

        $employerProfile = $user->employerProfile;
        abort_if(!$employerProfile, 404, 'Employer profile not found.');

        $plan = SubscriptionPlan::where('is_active', true)->findOrFail($planId);

        // Create/reuse subscription
        $subscription = EmployerSubscription::firstOrCreate(
            [
                'employer_profile_id' => $employerProfile->id,
                'plan_id' => $plan->id,
            ],
            [
                'subscription_status' => EmployerSubscription::STATUS_INACTIVE,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]
        );

        // If expired/canceled, reset to inactive with fresh dates
        if (
            !$subscription->wasRecentlyCreated &&
            in_array($subscription->subscription_status, [EmployerSubscription::STATUS_EXPIRED, EmployerSubscription::STATUS_CANCELED], true)
        ) {
            $subscription->update([
                'subscription_status' => EmployerSubscription::STATUS_INACTIVE,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]);
        }

        // Cancel other inactive subs, expire old active subs
        EmployerSubscription::where('employer_profile_id', $employerProfile->id)
            ->where('id', '!=', $subscription->id)
            ->get()
            ->each(function ($sub) {
                if ($sub->subscription_status === EmployerSubscription::STATUS_INACTIVE) {
                    $sub->update(['subscription_status' => EmployerSubscription::STATUS_CANCELED]);
                } elseif ($sub->subscription_status === EmployerSubscription::STATUS_ACTIVE) {
                    $sub->update(['subscription_status' => EmployerSubscription::STATUS_EXPIRED]);
                }
            });

        return redirect()
            ->route('employer.subscription.payment', $subscription->id)
            ->with('success', 'Please complete your payment to activate the plan.');
    }

    /**
     * Show payment page for a subscription
     */
    public function payment($subscriptionId)
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $employerProfile = $user->employerProfile;
        abort_if(!$employerProfile, 404, 'Employer profile not found.');

        $subscription = EmployerSubscription::with([
            'plan' => fn($q) => $q->withTrashed(),
        ])
            ->where('employer_profile_id', $employerProfile->id)
            ->findOrFail($subscriptionId);

        abort_if(!$subscription->plan, 404, 'Subscription plan not found for this subscription.');

        return view('employer.contents.subscription.payment', compact('subscription'));
    }

    /**
     * Process manual payment submission (GCash QR or Cash)
     *
     * - gcash: reference REQUIRED + proof REQUIRED
     * - cash: reference optional, proof optional (you can require if you want)
     */
    public function processPayment(Request $request, $subscriptionId)
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $employerProfile = $user->employerProfile;
        abort_if(!$employerProfile, 404, 'Employer profile not found.');

        $subscription = EmployerSubscription::with([
            'plan' => fn($q) => $q->withTrashed(),
        ])
            ->where('employer_profile_id', $employerProfile->id)
            ->findOrFail($subscriptionId);

        abort_if(!$subscription->plan, 404, 'Subscription plan not found for this subscription.');

        // Base validation
        $data = $request->validate([
            'method' => ['required', Rule::in(['gcash', 'cash'])],
            'gcash_reference' => ['nullable', 'string', 'max:191'],
            'cash_note' => ['nullable', 'string', 'max:191'],
            'proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        if ($data['method'] === 'gcash') {
            $request->validate([
                'gcash_reference' => ['required', 'string', 'max:191'],
                'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            ]);
        }

        $proofPath = $request->hasFile('proof')
            ? $request->file('proof')->store('payments/proofs', 'public')
            : null;

        $reference = $data['method'] === 'gcash'
            ? $data['gcash_reference']
            : ($data['cash_note'] ?? null);

        $subscription->payments()->create([
            'employer_id' => $employerProfile->user_id,
            'plan_id' => $subscription->plan_id,
            'subscription_id' => $subscription->id,
            'amount' => $subscription->plan->price,
            'status' => 'pending',
            'method' => $data['method'],
            'reference' => $reference,
            'proof_path' => $proofPath,
        ]);

        return redirect()
            ->route('employer.subscription.dashboard')
            ->with('success', "Payment submitted for {$subscription->plan->name}. Please wait for admin verification.");
    }
}
