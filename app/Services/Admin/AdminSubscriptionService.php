<?php

namespace App\Services\Admin;

use App\Models\EmployerSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSubscriptionService
{
    public function getSubscriptions(Request $request): array
    {
        $status = trim((string) $request->query('status', ''));
        $q = trim((string) $request->query('q', ''));

        $subs = EmployerSubscription::query()
            ->with([
                'employerProfile.user:id,name,email',
                'plan' => fn($p) => $p->withTrashed()->select('id','name','code','price'),
            ])
            ->when($status !== '', fn($qr) => $qr->where('subscription_status', $status))
            ->when($q !== '', function ($qr) use ($q) {

                $qr->where(function ($w) use ($q) {

                    $w->whereHas('employerProfile', function ($e) use ($q) {
                        $e->where('company_name','like',"%{$q}%")
                          ->orWhere('representative_name','like',"%{$q}%");
                    })
                    ->orWhereHas('employerProfile.user', function ($u) use ($q) {
                        $u->where('name','like',"%{$q}%")
                          ->orWhere('email','like',"%{$q}%");
                    });

                });

            })
            ->orderByDesc('ends_at')
            ->paginate(10)
            ->withQueryString();

        return compact('subs','status','q');
    }

    public function activateSubscription(EmployerSubscription $subscription): void
    {
        DB::transaction(function () use ($subscription) {

            $subscription->load([
                'plan' => fn($p) => $p->withTrashed()
            ]);

            $durationDays = $subscription->plan?->duration_days ?? 30;

            $subscription->update([
                'subscription_status' => EmployerSubscription::STATUS_ACTIVE,
                'starts_at' => now(),
                'ends_at' => now()->addDays($durationDays),

                'activated_by_admin_id' => auth('admin')->id(),
                'activated_at' => now(),
            ]);

            $subscription->payments()->pending()->update([
                'status' => \App\Models\Payment::STATUS_COMPLETED,
                'verified_by_admin_id' => auth('admin')->id(),
                'verified_at' => now(),
            ]);

        });
    }

    public function suspendSubscription(Request $request, EmployerSubscription $subscription): void
    {
        $data = $request->validate([
            'reason' => ['required','string','max:255']
        ]);

        $subscription->update([
            'subscription_status' => 'suspended',
            'suspended_by_admin_id' => auth('admin')->id(),
            'suspended_at' => now(),
            'suspend_reason' => $data['reason'],
        ]);
    }

    public function getExpiredSubscriptions(Request $request): array
    {
        $q = trim((string) $request->query('q',''));

        EmployerSubscription::query()
            ->whereNotNull('ends_at')
            ->where('ends_at','<',now())
            ->where('subscription_status',EmployerSubscription::STATUS_ACTIVE)
            ->update([
                'subscription_status' => EmployerSubscription::STATUS_EXPIRED
            ]);

        $subs = EmployerSubscription::query()
            ->with([
                'employerProfile.user:id,name,email',
                'plan' => fn($p) => $p->withTrashed()->select('id','name','code','price'),
            ])
            ->whereNotNull('ends_at')
            ->where('ends_at','<',now())
            ->where('subscription_status',EmployerSubscription::STATUS_EXPIRED)
            ->when($q !== '', function ($qr) use ($q) {

                $qr->where(function ($w) use ($q) {

                    $w->whereHas('employerProfile', function ($e) use ($q) {
                        $e->where('company_name','like',"%{$q}%")
                          ->orWhere('representative_name','like',"%{$q}%");
                    })
                    ->orWhereHas('employerProfile.user', function ($u) use ($q) {
                        $u->where('name','like',"%{$q}%")
                          ->orWhere('email','like',"%{$q}%");
                    });

                });

            })
            ->orderByDesc('ends_at')
            ->paginate(10)
            ->withQueryString();

        return compact('subs','q');
    }
}