<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Models\EmployerSubscription;
use Illuminate\Support\Facades\Auth;
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
            'payments' => fn($q) => $q->latest('id'),
        ])
            ->where('employer_profile_id', $employerProfile->id)
            ->orderByDesc('id')
            ->get()
            ->filter(fn($s) => $s->plan !== null)
            ->values();

        // ✅ Current subscription should be ACTIVE only (not pending/inactive)
        $currentSubscription = $subscriptions->firstWhere('subscription_status', EmployerSubscription::STATUS_ACTIVE);
        $pendingSubscription = $subscriptions->firstWhere('subscription_status', EmployerSubscription::STATUS_PENDING);

        // ✅ Available plans
        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->with(['featureValues.definition'])
            ->orderBy('price')
            ->get();

        return view('employer.contents.subscription.subscription-dashboard', [
            'subscriptions' => $subscriptions,
            'currentSubscription' => $currentSubscription,
            'pendingSubscription' => $pendingSubscription, // ✅ add
            'plans' => $plans,
        ]);
    }

    /**
     * Select a plan and prepare subscription (PENDING only; not activated yet)
     */
    public function selectPlan($planId)
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $employerProfile = $user->employerProfile;
        abort_if(!$employerProfile, 404, 'Employer profile not found.');

        $plan = SubscriptionPlan::where('is_active', true)->findOrFail($planId);

        // ✅ Optional rule: block if already has ACTIVE subscription
        $hasActive = EmployerSubscription::where('employer_profile_id', $employerProfile->id)
            ->where('subscription_status', EmployerSubscription::STATUS_ACTIVE)
            ->exists();

        if ($hasActive) {
            return back()->with('error', 'You already have an active subscription.');
        }

        // ✅ Block multiple pending subscriptions
        $pending = EmployerSubscription::where('employer_profile_id', $employerProfile->id)
            ->where('subscription_status', EmployerSubscription::STATUS_PENDING)
            ->latest('id')
            ->first();

        if ($pending) {
            // If same plan, just redirect to its payment page
            if ((int) $pending->plan_id === (int) $plan->id) {
                return redirect()
                    ->route('employer.subscription.payment', $pending->id)
                    ->with('success', 'You already selected a plan. Please complete payment to activate it.');
            }

            return back()->with('error', 'You already have a pending subscription. Please complete payment or wait for admin verification.');
        }

        // ✅ Create PENDING subscription request (NO dates yet)
        $subscription = EmployerSubscription::create([
            'employer_profile_id' => $employerProfile->id,
            'plan_id' => $plan->id,
            'subscription_status' => EmployerSubscription::STATUS_PENDING,
            'starts_at' => null,
            'ends_at' => null,
        ]);

        return redirect()
            ->route('employer.subscription.payment', $subscription->id)
            ->with('success', 'Plan selected. Please complete your payment to activate the plan.');
    }

    public function cancelPending($subscriptionId)
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $employerProfile = $user->employerProfile;
        abort_if(!$employerProfile, 404, 'Employer profile not found.');

        $subscription = EmployerSubscription::where('employer_profile_id', $employerProfile->id)
            ->findOrFail($subscriptionId);

        // Only allow cancel if pending
        if ($subscription->subscription_status !== EmployerSubscription::STATUS_PENDING) {
            return back()->with('error', 'Only pending subscriptions can be canceled.');
        }

        // Optional: if may pending payment record already, you can keep it or mark it canceled too
        // Example:
        $subscription->payments()->where('status', 'pending')->update(['status' => 'canceled']);

        $subscription->update([
            'subscription_status' => EmployerSubscription::STATUS_CANCELED,
            'starts_at' => null,
            'ends_at' => null,
        ]);

        return redirect()
            ->route('employer.subscription.dashboard')
            ->with('success', 'Pending subscription canceled. You can choose a plan again.');
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
            'payments' => fn($q) => $q->latest('id'),
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
     * - cash: reference optional, proof optional
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

        // ✅ Only allow payment submission for PENDING subscriptions
        if ($subscription->subscription_status !== EmployerSubscription::STATUS_PENDING) {
            return back()->with('error', 'This subscription is not pending for payment.');
        }

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

        // Create payment record (pending)
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

        // Keep subscription as pending until admin verifies payment
        // (Admin will set ACTIVE and add starts_at/ends_at)
        return redirect()
            ->route('employer.subscription.dashboard')
            ->with('success', "Payment submitted for {$subscription->plan->name}. Please wait for admin verification.");
    }
}