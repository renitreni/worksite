<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployerProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public function index(Request $request)
{
    $q = trim((string) $request->query('q', ''));
    $role = trim((string) $request->query('role', ''));               // employer|candidate|''
    $verified = trim((string) $request->query('verified', ''));       // verified|unverified|''
    $archived = (string) $request->query('archived', '0');            // 0|1

    $sub_plan = trim((string) $request->query('sub_plan', ''));
    $sub_status = trim((string) $request->query('sub_status', ''));

    $query = User::query()
        ->whereIn('role', ['employer', 'candidate'])
        ->with('employerProfile');

    // archived filter
    if ($archived === '1') {
        $query->whereNotNull('archived_at');
    } else {
        $query->whereNull('archived_at');
    }

    // role filter
    if (in_array($role, ['employer', 'candidate'], true)) {
        $query->where('role', $role);
    }

    // verified filter (ONLY for candidates, and DO NOT override employer selection)
    if (in_array($verified, ['verified', 'unverified'], true)) {

        // if role explicitly employer, ignore verified filter
        if ($role !== 'employer') {
            $query->where(function ($w) use ($verified) {
                $w->where('role', 'candidate')
                  ->when($verified === 'verified', fn ($q2) => $q2->whereNotNull('email_verified_at'))
                  ->when($verified === 'unverified', fn ($q2) => $q2->whereNull('email_verified_at'));
            });
        }
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

    // subscription filters (employer only)
    if ($sub_plan !== '' || $sub_status !== '') {
        $query->where('role', 'employer')
              ->whereHas('employerProfile', function ($ep) use ($sub_plan, $sub_status) {
                  if ($sub_plan !== '') $ep->where('plan', $sub_plan);
                  if ($sub_status !== '') $ep->where('subscription_status', $sub_status);
              });
    }

    $users = $query->latest('id')->paginate(10)->withQueryString();

    // ✅ IMPORTANT: match your actual blade file
    return view('adminpage.contents.users', compact(
    'users', 'q', 'role', 'verified', 'archived', 'sub_plan', 'sub_status'
));
}

    public function show(User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        $user->load('employerProfile');

        return view('adminpage.contents.users-show', compact('user'));
    }

    public function edit(User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        $user->load('employerProfile');

        return view('adminpage.contents.users-edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        $data = $request->validate([
            'first_name'       => ['required', 'string', 'max:255'],
            'last_name'        => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'            => ['nullable', 'string', 'max:50'],
            'employer_status'  => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
        ]);

        $user->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'name'       => $data['first_name'] . ' ' . $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
        ]);

        if ($user->role === 'employer' && !empty($data['employer_status'])) {
            $profile = EmployerProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name'         => '—',
                    'company_email'        => $user->email,
                    'company_address'      => '—',
                    'company_contact'      => '—',
                    'representative_name'  => '—',
                    'position'             => '—',
                    'status'               => 'pending',
                ]
            );

            $profile->status = $data['employer_status'];
            $profile->save();
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated.');
    }

    /**
     * Archive user: sets archived_at and disables via account_status.
     */
    public function archive(User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        $user->archived_at = now();
        $user->account_status = 'disabled';
        $user->save();

        return back()->with('success', 'User archived.');
    }

    /**
     * Restore user: clears archived_at and optionally re-activates.
     */
    public function restore(User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        $user->archived_at = null;

        // If you want restore to bring them back online:
        $user->account_status = 'active';

        $user->save();

        return back()->with('success', 'User restored.');
    }

    /**
     * Toggle active/disabled for candidate/employer only.
     */
    public function toggle(User $user)
    {
        abort_if(!in_array($user->role, ['employer', 'candidate'], true), 404);

        // Safety: prevent disabling yourself if you ever reuse this for admins
        $currentAdminId = Auth::guard('admin')->id();
        abort_if($currentAdminId && $user->id === $currentAdminId, 403);

        $current = $user->account_status ?? 'active';
        $user->account_status = ($current === 'active') ? 'disabled' : 'active';
        $user->save();

        return back()->with('success', 'User status updated.');
    }

    /**
     * Set explicit status: active|disabled|hold
     */
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

    /**
     * Approve employer (kept for compatibility). Calls approve().
     */
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
                'company_name'         => '—',
                'company_email'        => $user->email,
                'company_address'      => '—',
                'company_contact'      => '—',
                'representative_name'  => '—',
                'position'             => '—',
                'status'               => 'pending',
            ]
        );

        $profile->update([
            'status'           => 'approved',
            'rejection_reason' => null,
            'rejected_at'      => null,
            'approved_at'      => now(),
        ]);

        // Approved employers should be active
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
                'company_name'         => '—',
                'company_email'        => $user->email,
                'company_address'      => '—',
                'company_contact'      => '—',
                'representative_name'  => '—',
                'position'             => '—',
                'status'               => 'pending',
            ]
        );

        $profile->update([
            'status'            => 'rejected',
            'rejection_reason'  => $request->reason,
            'rejected_at'       => now(),
            'approved_at'       => null,
        ]);

        // Rejected employers should not be active
        $user->account_status = 'hold';
        $user->save();

        return back()->with('success', 'Employer rejected successfully.');
    }
    public function updateSubscription(Request $request, User $user)
{
    abort_unless($user->role === 'employer', 404);

    $data = $request->validate([
        'plan' => ['nullable', Rule::in(['standard', 'gold', 'platinum'])],
        'subscription_status' => ['nullable', Rule::in(['active', 'expired', 'suspended', 'none'])],
        'starts_at' => ['nullable', 'date'],
        'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        'suspended_reason' => ['nullable', 'string', 'max:5000'],
        'also_hold_account' => ['nullable', 'boolean'],
    ]);

    $ep = $user->employerProfile;
    if (!$ep) {
        return back()->with('error', 'Employer profile not found.');
    }

    // Update fields (keep previous if not provided)
    $ep->plan = $data['plan'] ?? $ep->plan;
    $ep->subscription_status = $data['subscription_status'] ?? $ep->subscription_status;
    $ep->starts_at = $data['starts_at'] ?? $ep->starts_at;
    $ep->ends_at = $data['ends_at'] ?? $ep->ends_at;
    $ep->suspended_reason = $data['suspended_reason'] ?? $ep->suspended_reason;

   
    if ($ep->ends_at && now()->greaterThan($ep->ends_at)) {
        $ep->subscription_status = 'expired';

        if (empty($ep->suspended_reason)) {
            $ep->suspended_reason = 'expired';
        }
    }

    
    if (($ep->subscription_status === 'active') && empty($ep->ends_at)) {
        $ep->subscription_status = 'none';
    }

    $ep->save();

    
    if ($request->boolean('also_hold_account')) {
        $user->account_status = 'hold';
        $user->save();
    }

    return back()->with('success', 'Subscription updated.');
}
}
