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
            ->with(['employer:id,name,email', 'plan:id,name,code,price'])
            ->when($status !== '', fn($qr) => $qr->where('status', $status))
            ->when($q !== '', function($qr) use ($q) {
                $qr->whereHas('employer', function($e) use ($q) {
                    $e->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('ends_at')
            ->paginate(10)
            ->withQueryString();

        return view('adminpage.contents.subscriptions.subscriptions.index', compact('subs', 'status', 'q'));
    }

    public function activate(EmployerSubscription $subscription)
    {
        // Manual activate (optional). Uses your 30-day rule.
        DB::transaction(function () use ($subscription) {
            $subscription->update([
                'status' => EmployerSubscription::STATUS_ACTIVE,
                'starts_at' => now(),
                'ends_at' => now()->addDays(30),
                'activated_by_admin_id' => auth('admin')->id(),
                'activated_at' => now(),
            ]);
        });

        return back()->with('success', 'Subscription activated for 30 days.');
    }

    public function suspend(Request $request, EmployerSubscription $subscription)
    {
        $data = $request->validate([
            'reason' => ['required','string','max:255'],
        ]);

        $subscription->update([
            'status' => EmployerSubscription::STATUS_SUSPENDED,
            'suspended_by_admin_id' => auth('admin')->id(),
            'suspended_at' => now(),
            'suspend_reason' => $data['reason'],
        ]);

        return back()->with('warning', 'Subscription suspended.');
    }

    public function expired(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $subs = EmployerSubscription::query()
            ->with(['employer:id,name,email', 'plan:id,name,code,price'])
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->whereIn('status', [
                EmployerSubscription::STATUS_ACTIVE,
                EmployerSubscription::STATUS_EXPIRED,
            ])
            ->when($q !== '', function($qr) use ($q) {
                $qr->whereHas('employer', function($e) use ($q) {
                    $e->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderBy('ends_at')
            ->paginate(10)
            ->withQueryString();

        // Optional: auto-mark as expired when viewing list
        foreach ($subs as $s) {
            if ($s->status !== EmployerSubscription::STATUS_EXPIRED) {
                $s->update(['status' => EmployerSubscription::STATUS_EXPIRED]);
            }
        }

        return view('adminpage.contents.subscriptions.subscriptions.expired', compact('subs', 'q'));
    }

    public function sendExpiredReminder(EmployerSubscription $subscription)
    {
        // Stub: implement notification/email later
        // Requirement: reminders for expired plans :contentReference[oaicite:3]{index=3}

        return back()->with('info', 'Reminder queued (stub).');
    }
}