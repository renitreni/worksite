<?php

namespace App\Services\Employer;

use App\Models\EmployerSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Notifications\SubscriptionPaymentUpdated;

class EmployerSubscriptionService
{
    public function getActiveSubscriptionForProfile($profile): ?EmployerSubscription
    {
        return EmployerSubscription::query()
            ->with([
                'plan' => fn($q) => $q->withTrashed()
                    ->with('featureValues.definition')
            ])
            ->where('employer_profile_id', $profile->id)
            ->where('subscription_status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->orderByDesc('ends_at')
            ->first();
    }

    public function getDashboardData(): array
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $profile = $user->employerProfile;
        abort_if(!$profile, 404, 'Employer profile not found.');

        $subscriptions = EmployerSubscription::with([
            'plan' => fn($q) => $q->withTrashed()
                ->with(['featureValues.definition']),
            'payments' => fn($q) => $q->latest('id'),
        ])
            ->where('employer_profile_id', $profile->id)
            ->orderByDesc('id')
            ->get()
            ->filter(fn($s) => $s->plan !== null)
            ->values();

        $currentSubscription =
            $subscriptions->firstWhere(
                'subscription_status',
                EmployerSubscription::STATUS_ACTIVE
            );

        $pendingSubscription =
            $subscriptions->firstWhere(
                'subscription_status',
                EmployerSubscription::STATUS_PENDING
            );

        $plans = SubscriptionPlan::where('is_active', true)
            ->with(['featureValues.definition'])
            ->orderBy('price')
            ->get();

        $paymentPendingApproval = null;

        if ($pendingSubscription) {
            $paymentPendingApproval = $pendingSubscription
                ->payments()
                ->where('status', 'pending')
                ->latest()
                ->first();
        }

        return [
            'subscriptions' => $subscriptions,
            'currentSubscription' => $currentSubscription,
            'pendingSubscription' => $pendingSubscription,
            'paymentPendingApproval' => $paymentPendingApproval,
            'plans' => $plans
        ];
    }

    public function selectPlan($planId)
    {
        $user = Auth::user();
        $profile = $user->employerProfile;

        $plan = SubscriptionPlan::where('is_active', true)
            ->findOrFail($planId);

        $hasActive = EmployerSubscription::where(
            'employer_profile_id',
            $profile->id
        )
            ->where(
                'subscription_status',
                EmployerSubscription::STATUS_ACTIVE
            )
            ->exists();

        if ($hasActive) {
            return back()->with(
                'error',
                'You already have an active subscription.'
            );
        }

        $pending = EmployerSubscription::where(
            'employer_profile_id',
            $profile->id
        )
            ->where(
                'subscription_status',
                EmployerSubscription::STATUS_PENDING
            )
            ->latest('id')
            ->first();

        if ($pending) {

            if ((int) $pending->plan_id === (int) $plan->id) {

                return redirect()
                    ->route(
                        'employer.subscription.payment',
                        $pending->id
                    )
                    ->with(
                        'success',
                        'You already selected a plan.'
                    );
            }

            return back()->with(
                'error',
                'You already have a pending subscription.'
            );
        }

        $subscription = EmployerSubscription::create([
            'employer_profile_id' => $profile->id,
            'plan_id' => $plan->id,
            'subscription_status' => EmployerSubscription::STATUS_PENDING,
            'starts_at' => null,
            'ends_at' => null,
        ]);

        return redirect()
            ->route(
                'employer.subscription.payment',
                $subscription->id
            );
    }

    public function cancelPending($subscriptionId)
    {
        $user = Auth::user();
        $profile = $user->employerProfile;

        $subscription = EmployerSubscription::where(
            'employer_profile_id',
            $profile->id
        )->findOrFail($subscriptionId);

        if (
            $subscription->subscription_status
            !== EmployerSubscription::STATUS_PENDING
        ) {

            return back()->with(
                'error',
                'Only pending subscriptions can be canceled.'
            );
        }

        $subscription->payments()
            ->where('status', 'pending')
            ->update(['status' => 'canceled']);

        $subscription->update([
            'subscription_status' => EmployerSubscription::STATUS_CANCELED,
            'starts_at' => null,
            'ends_at' => null
        ]);

        return redirect()
            ->route('employer.subscription.dashboard')
            ->with('success', 'Pending subscription canceled.');
    }

    public function getPaymentPage($subscriptionId)
    {
        $user = Auth::user();
        $profile = $user->employerProfile;

        $subscription = EmployerSubscription::with([
            'plan' => fn($q) => $q->withTrashed(),
            'payments' => fn($q) => $q->latest('id'),
        ])
            ->where('employer_profile_id', $profile->id)
            ->findOrFail($subscriptionId);

        abort_if(!$subscription->plan, 404);

        return $subscription;
    }

    public function processPayment($request, $subscriptionId)
    {
        $user = Auth::user();
        $profile = $user->employerProfile;

        $subscription = EmployerSubscription::with([
            'plan' => fn($q) => $q->withTrashed()
        ])
            ->where('employer_profile_id', $profile->id)
            ->findOrFail($subscriptionId);

        $data = $request->validate([
            'method' => ['required', Rule::in(['gcash', 'cash'])],
            'gcash_reference' => ['nullable', 'string', 'max:191'],
            'cash_note' => ['nullable', 'string', 'max:191'],
            'proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        if ($data['method'] === 'gcash') {

            $request->validate([
                'gcash_reference' => 'required|string|max:191',
                'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096'
            ]);
        }

        $proofPath = $request->hasFile('proof')
            ? $request->file('proof')
                ->store('payments/proofs', 'public')
            : null;

        $reference = $data['method'] === 'gcash'
            ? $data['gcash_reference']
            : ($data['cash_note'] ?? null);

        $payment = $subscription->payments()->create([
            'employer_id' => $profile->user_id,
            'plan_id' => $subscription->plan_id,
            'subscription_id' => $subscription->id,
            'amount' => $subscription->plan->price,
            'status' => 'pending',
            'method' => $data['method'],
            'reference' => $reference,
            'proof_path' => $proofPath
        ]);

        $user->notify(
            new SubscriptionPaymentUpdated(
                $payment->load('plan'),
                'pending'
            )
        );

        return redirect()
            ->route('employer.subscription.dashboard')
            ->with(
                'success',
                "Payment submitted. Please wait for admin verification."
            );
    }
}