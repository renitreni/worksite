<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\EmployerProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployerAccountApprovedMail;
use App\Mail\EmployerAccountRejectedMail;
use App\Mail\AccountDisabledMail;

class AdminPlatformUserService
{
    public function authorizeUserRole(User $user): void
    {
        abort_if(!in_array($user->role, ['employer','candidate'], true), 404);
    }

    public function loadUserRelations(User $user): User
    {
        return $user->load([
            'employerProfile.verification',
            'employerProfile.subscription.plan',
            'employerProfile.industries'
        ]);
    }

    public function getUsers(Request $request): array
    {
        $q = trim((string) $request->query('q',''));
        $role = $request->query('role','candidate');

        $query = User::query()->where('role',$role);

        if ($q !== '') {
            $query->where('name','like',"%{$q}%")
                  ->orWhere('email','like',"%{$q}%");
        }

        $users = $query->latest('id')
            ->paginate(10)
            ->withQueryString();

        return compact('users','q','role');
    }

    public function updateUser(Request $request, User $user): void
    {
        $data = $request->validate([
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'email' => ['required','email','max:255',Rule::unique('users','email')->ignore($user->id)],
        ]);

        $user->update([
            'first_name'=>$data['first_name'],
            'last_name'=>$data['last_name'],
            'name'=>$data['first_name'].' '.$data['last_name'],
            'email'=>$data['email'],
        ]);
    }

    public function archiveUser(User $user): void
    {
        $user->update([
            'archived_at'=>now(),
            'account_status'=>'disabled'
        ]);
    }

    public function restoreUser(User $user): void
    {
        $user->update([
            'archived_at'=>null,
            'account_status'=>'active'
        ]);
    }

    public function toggleUserStatus(User $user): void
    {
        $newStatus = $user->account_status === 'active'
            ? 'disabled'
            : 'active';

        $user->update([
            'account_status'=>$newStatus
        ]);

        if ($newStatus === 'disabled') {
            Mail::to($user->email)->send(
                new AccountDisabledMail($user->name,$user->role)
            );
        }
    }

    public function approveEmployer(User $user): void
    {
        abort_if($user->role !== 'employer',404);

        $profile = EmployerProfile::firstOrCreate([
            'user_id'=>$user->id
        ]);

        $profile->verification()->updateOrCreate(
            ['employer_profile_id'=>$profile->id],
            [
                'status'=>'approved',
                'approved_at'=>now()
            ]
        );

        Mail::to($user->email)->send(
            new EmployerAccountApprovedMail(
                $user->name,
                $profile->company_name
            )
        );
    }

    public function rejectEmployer(User $user, Request $request): void
    {
        $data = $request->validate([
            'reason'=>['required','string']
        ]);

        $profile = EmployerProfile::firstOrCreate([
            'user_id'=>$user->id
        ]);

        $profile->verification()->updateOrCreate(
            ['employer_profile_id'=>$profile->id],
            [
                'status'=>'rejected',
                'rejection_reason'=>$data['reason']
            ]
        );

        Mail::to($user->email)->send(
            new EmployerAccountRejectedMail(
                $user->name,
                $profile->company_name,
                $data['reason']
            )
        );
    }

    public function suspendEmployer(User $user, Request $request): void
    {
        $data = $request->validate([
            'suspended_reason'=>['required','string']
        ]);

        $profile = EmployerProfile::firstOrCreate([
            'user_id'=>$user->id
        ]);

        $profile->verification()->updateOrCreate(
            ['employer_profile_id'=>$profile->id],
            [
                'status'=>'suspended',
                'suspended_reason'=>$data['suspended_reason']
            ]
        );
    }

    public function unsuspendEmployer(User $user): void
    {
        $profile = EmployerProfile::firstOrCreate([
            'user_id'=>$user->id
        ]);

        $profile->verification()->updateOrCreate(
            ['employer_profile_id'=>$profile->id],
            [
                'status'=>'approved',
                'suspended_reason'=>null
            ]
        );
    }

    public function updateSubscription(Request $request, User $user): void
    {
        $data = $request->validate([
            'subscription_status'=>['required','in:inactive,active,expired,canceled']
        ]);

        $profile = $user->employerProfile;

        $profile->subscription()->updateOrCreate(
            ['employer_profile_id'=>$profile->id],
            [
                'subscription_status'=>$data['subscription_status']
            ]
        );
    }
}