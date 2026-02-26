<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployerSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $status = trim((string) $request->query('status', ''));
        $q = trim((string) $request->query('q', ''));

        $subs = EmployerSubscription::query()
            ->with([
                // employer = EmployerProfile, user has name/email
                'employerProfile.user:id,name,email',
                // plan is soft deletable, safe include trashed
                'plan' => fn($p) => $p->withTrashed()->select('id', 'name', 'code', 'price'),
            ])
            ->when($status !== '', fn($qr) => $qr->where('subscription_status', $status))
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->whereHas('employer', function ($e) use ($q) {
                        $e->where('company_name', 'like', "%{$q}%")
                            ->orWhere('representative_name', 'like', "%{$q}%");
                    })->orWhereHas('employer.user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
                });
            })
            ->orderByDesc('ends_at')
            ->paginate(10)
            ->withQueryString();

        return view('adminpage.contents.subscriptions.subscriptions.index', compact('subs', 'status', 'q'));
    }

    public function activate(EmployerSubscription $subscription)
    {
        DB::transaction(function () use ($subscription) {

            // ensure plan loaded (including trashed)
            $subscription->load(['plan' => fn($p) => $p->withTrashed()]);

            $durationDays = $subscription->plan?->duration_days ?? 30;

            $subscription->update([
                // ✅ your column is subscription_status
                'subscription_status' => EmployerSubscription::STATUS_ACTIVE,
                'starts_at' => now(),
                'ends_at' => now()->addDays($durationDays),

                // keep these only if columns exist in employer_subscriptions table
                'activated_by_admin_id' => auth('admin')->id(),
                'activated_at' => now(),
            ]);

            // Update pending payment to completed
            $subscription->payments()->pending()->update([
                'status' => \App\Models\Payment::STATUS_COMPLETED,
                'verified_by_admin_id' => auth('admin')->id(),
                'verified_at' => now(),
            ]);
        });

        return back()->with('success', 'Subscription activated and payment marked as completed.');
    }

    public function suspend(Request $request, EmployerSubscription $subscription)
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        // ✅ make sure your DB + model supports "suspended"
        // If you don't have this status, change to STATUS_CANCELED or STATUS_EXPIRED.
        $subscription->update([
            'subscription_status' => 'suspended',
            'suspended_by_admin_id' => auth('admin')->id(),
            'suspended_at' => now(),
            'suspend_reason' => $data['reason'],
        ]);

        return back()->with('warning', 'Subscription suspended.');
    }

    public function expired(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        // ✅ 1) Bulk mark as expired FIRST (so display is consistent)
        EmployerSubscription::query()
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->where('subscription_status', EmployerSubscription::STATUS_ACTIVE)
            ->update([
                'subscription_status' => EmployerSubscription::STATUS_EXPIRED,
            ]);

        // ✅ 2) Fetch only truly expired subs
        $subs = EmployerSubscription::query()
            ->with([
                'employerProfile.user:id,name,email',
                'plan' => fn($p) => $p->withTrashed()->select('id', 'name', 'code', 'price'),
            ])
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->where('subscription_status', EmployerSubscription::STATUS_EXPIRED)
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->whereHas('employerProfile', function ($e) use ($q) {
                        $e->where('company_name', 'like', "%{$q}%")
                            ->orWhere('representative_name', 'like', "%{$q}%");
                    })
                        ->orWhereHas('employerProfile.user', function ($u) use ($q) {
                            $u->where('name', 'like', "%{$q}%")
                                ->orWhere('email', 'like', "%{$q}%");
                        });
                });
            })
            ->orderByDesc('ends_at')
            ->paginate(10)
            ->withQueryString();

        return view('adminpage.contents.subscriptions.subscriptions.expired', compact('subs', 'q'));
    }
}
