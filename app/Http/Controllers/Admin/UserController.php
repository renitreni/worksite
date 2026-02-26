<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployerProfile;
use App\Models\EmployerVerification;
use App\Models\EmployerSubscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Force default role=candidate on first visit
        if (!$request->has('role')) {
            return redirect()->route('admin.users.index', array_merge(
                $request->query(), // keep other query strings (q, archived, etc.)
                ['role' => 'employer']
            ));
        }

        $q = trim((string) $request->query('q', ''));
        $role = trim((string) $request->query('role', 'candidate')); // candidate|employer
        if (!in_array($role, ['candidate', 'employer'], true))
            $role = 'candidate';

        $verified = trim((string) $request->query('verified', ''));
        $archived = (string) $request->query('archived', '0');

        $sub_plan = trim((string) $request->query('sub_plan', ''));
        $sub_status = trim((string) $request->query('sub_status', ''));

        $query = User::query()
            ->whereIn('role', ['employer', 'candidate'])
            ->where('role', $role) // ✅ always filtered by role
            ->with([
                'employerProfile.verification',
                'employerProfile.subscription.plan',
                'employerProfile.industries',
            ]);

        // archived filter
        if ($archived === '1') {
            $query->whereNotNull('archived_at');
        } else {
            $query->whereNull('archived_at');
        }

        // candidate verified filter only when role=candidate
        if ($role === 'candidate' && in_array($verified, ['verified', 'unverified'], true)) {
            $query->when($verified === 'verified', fn($q2) => $q2->whereNotNull('email_verified_at'))
                ->when($verified === 'unverified', fn($q2) => $q2->whereNull('email_verified_at'));
        }

        // search
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhereRaw("concat(first_name,' ',last_name) like ?", ["%{$q}%"])
                    ->orWhereRaw("concat(last_name,' ',first_name) like ?", ["%{$q}%"]);
            });
        }

        // subscription filters (only when role=employer)
        if ($role === 'employer' && ($sub_plan !== '' || $sub_status !== '')) {
            $query->whereHas('employerProfile.subscription', function ($s) use ($sub_status) {
                if ($sub_status !== '')
                    $s->where('subscription_status', $sub_status);
            });

            if ($sub_plan !== '') {
                $query->whereHas('employerProfile.subscription.plan', function ($p) use ($sub_plan) {
                    $p->where('code', $sub_plan);
                });
            }
        }

        $users = $query->latest('id')->paginate(10)->withQueryString();

        return view('adminpage.contents.users', compact(
            'users',
            'q',
            'role',
            'verified',
            'archived',
            'sub_plan',
            'sub_status'
        ));
    }

    public function show(User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        $user->load([
            'employerProfile.verification',
            'employerProfile.subscription.plan',
            'employerProfile.industries',
        ]);

        return view('adminpage.contents.users-show', compact('user'));
    }

    public function edit(User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        $user->load([
            'employerProfile.verification',
            'employerProfile.subscription.plan',
            'employerProfile.industries',
        ]);

        return view('adminpage.contents.users-edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],

            // employer verification status (NEW location)
            'employer_status' => ['nullable', Rule::in(['pending', 'approved', 'rejected', 'suspended'])],
        ]);

        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);

        // employer verification update (now in employer_verifications table)
        if ($user->role === 'employer' && !empty($data['employer_status'])) {

            $profile = EmployerProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => '—',
                    'company_address' => '—',
                    'company_contact' => '—',
                    'company_website' => null,
                    'description' => null,
                    'representative_name' => '—',
                    'position' => '—',
                ]
            );

            $payload = ['status' => $data['employer_status']];

            if ($data['employer_status'] === 'approved') {
                $payload['approved_at'] = now();
                $payload['rejected_at'] = null;
                $payload['rejection_reason'] = null;
                $payload['suspended_reason'] = null;
            }

            if ($data['employer_status'] === 'rejected') {
                // keep reason unchanged here; use reject() method to set reason
                $payload['rejected_at'] = now();
                $payload['approved_at'] = null;
            }

            $profile->verification()->updateOrCreate(
                ['employer_profile_id' => $profile->id],
                $payload
            );
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated.');
    }

    public function archive(User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        $user->archived_at = now();
        $user->account_status = 'disabled';
        $user->save();

        return back()->with('success', 'User archived.');
    }

    public function restore(User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        $user->archived_at = null;
        $user->account_status = 'active';
        $user->save();

        return back()->with('success', 'User restored.');
    }

    public function toggle(User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        $currentAdminId = Auth::guard('admin')->id();
        abort_if($currentAdminId && $user->id === $currentAdminId, 403);

        $current = $user->account_status ?? 'active';
        $user->account_status = ($current === 'active') ? 'disabled' : 'active';
        $user->save();

        return back()->with('success', 'User status updated.');
    }

    public function setStatus(Request $request, User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        $data = $request->validate([
            'account_status' => ['required', 'in:active,disabled,hold'],
        ]);

        $user->account_status = $data['account_status'];
        $user->save();

        return back()->with('success', 'User status updated.');
    }

    public function approveEmployer(User $user)
    {
        return $this->approve($user);
    }

    public function approve(User $user)
    {
        abort_if($user->role !== 'employer', 404);

        $profile = EmployerProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => '—',
                'company_address' => '—',
                'company_contact' => '—',
                'company_website' => null,
                'description' => null,
                'representative_name' => '—',
                'position' => '—',
            ]
        );

        $profile->verification()->updateOrCreate(
            ['employer_profile_id' => $profile->id],
            [
                'status' => 'approved',
                'approved_at' => now(),
                'rejected_at' => null,
                'rejection_reason' => null,
                'suspended_reason' => null,
            ]
        );

        $user->account_status = 'active';
        $user->save();

        return back()->with('success', 'Employer approved successfully.');
    }

    public function reject(User $user, Request $request)
    {
        abort_if($user->role !== 'employer', 404);

        $request->validate([
            'reason' => ['required', 'string', 'min:3', 'max:2000'],
        ]);

        $profile = EmployerProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => '—',
                'company_address' => '—',
                'company_contact' => '—',
                'company_website' => null,
                'description' => null,
                'representative_name' => '—',
                'position' => '—',
            ]
        );

        $profile->verification()->updateOrCreate(
            ['employer_profile_id' => $profile->id],
            [
                'status' => 'rejected',
                'rejection_reason' => $request->reason,
                'rejected_at' => now(),
                'approved_at' => null,
            ]
        );

        $user->account_status = 'hold';
        $user->save();

        return back()->with('success', 'Employer rejected successfully.');
    }

    public function suspend(User $user, Request $request)
    {
        abort_unless($user->role === 'employer', 404);

        $data = $request->validate([
            'suspended_reason' => ['required', 'string', 'min:3', 'max:2000'],
            'also_hold_account' => ['nullable', 'boolean'],
        ]);

        $profile = EmployerProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => '—',
                'company_address' => '—',
                'company_contact' => '—',
                'company_website' => null,
                'description' => null,
                'representative_name' => '—',
                'position' => '—',
            ]
        );

        $profile->verification()->updateOrCreate(
            ['employer_profile_id' => $profile->id],
            [
                'status' => 'suspended',
                'suspended_reason' => $data['suspended_reason'],
                'approved_at' => null,
                'rejected_at' => null,
                'rejection_reason' => null,
            ]
        );

        // Optional: hold account
        if ($request->boolean('also_hold_account')) {
            $user->account_status = 'hold';
            $user->save();
        }

        return back()->with('success', 'Employer suspended.');
    }

    public function unsuspend(User $user)
    {
        abort_unless($user->role === 'employer', 404);

        $profile = EmployerProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => '—',
                'company_address' => '—',
                'company_contact' => '—',
                'company_website' => null,
                'description' => null,
                'representative_name' => '—',
                'position' => '—',
            ]
        );

        // Unsuspend back to "approved" by default (you can change to "pending" if you prefer)
        $profile->verification()->updateOrCreate(
            ['employer_profile_id' => $profile->id],
            [
                'status' => 'approved',
                'suspended_reason' => null,
                'approved_at' => now(),
                'rejected_at' => null,
                'rejection_reason' => null,
            ]
        );

        // Bring account back active (optional)
        if (($user->account_status ?? 'active') === 'hold') {
            $user->account_status = 'active';
            $user->save();
        }

        return back()->with('success', 'Employer unsuspended.');
    }

    public function updateSubscription(Request $request, User $user)
    {
        abort_unless($user->role === 'employer', 404);

        $data = $request->validate([
            'plan' => ['nullable', Rule::in(['standard', 'gold', 'platinum'])],
            'subscription_status' => ['required', Rule::in(['inactive', 'active', 'expired', 'canceled'])],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'also_hold_account' => ['nullable', 'boolean'],
        ]);

        $ep = $user->employerProfile;

        if (!$ep) {
            return back()->with('error', 'Employer profile not found.');
        }

        // update subscription table (NEW)
        $sub = $ep->subscription()->updateOrCreate(
            ['employer_profile_id' => $ep->id],
            [
                'plan' => $data['plan'] ?? null,
                'subscription_status' => $data['subscription_status'],
                'starts_at' => $data['starts_at'] ?? null,
                'ends_at' => $data['ends_at'] ?? null,
            ]
        );

        // auto expire if ends_at passed
        if ($sub->ends_at && now()->greaterThan($sub->ends_at)) {
            $sub->subscription_status = 'expired';
            $sub->save();
        }

        if ($request->boolean('also_hold_account')) {
            $user->account_status = 'hold';
            $user->save();
        }

        return back()->with('success', 'Subscription updated.');
    }
}
