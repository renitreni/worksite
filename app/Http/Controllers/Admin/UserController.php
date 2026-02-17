<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployerProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
{
    $q = trim((string) $request->query('q', ''));
    $role = $request->query('role');         // employer|candidate|null
    $verified = $request->query('verified'); // verified|unverified|null
    $archived = $request->query('archived', '0'); // '0' default

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
    if ($role && in_array($role, ['employer', 'candidate'], true)) {
        $query->where('role', $role);
    }

    // verified filter (candidates only)
    if ($verified === 'verified') {
        $query->where('role', 'candidate')->whereNotNull('email_verified_at');
    } elseif ($verified === 'unverified') {
        $query->where('role', 'candidate')->whereNull('email_verified_at');
    }

    // search
    if ($q !== '') {
        $query->where(function ($sub) use ($q) {
            $sub->where('name', 'like', "%{$q}%")
                ->orWhere('first_name', 'like', "%{$q}%")
                ->orWhere('last_name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%");
        });
    }

    $users = $query->latest('id')->paginate(10)->withQueryString();

    return view('adminpage.contents.users', compact('users', 'q', 'role', 'verified', 'archived'));
}
public function show(User $user)
{
    abort_if(!in_array($user->role, ['employer','candidate'], true), 404);

    $user->load('employerProfile');

    return view('adminpage.contents.users-show', compact('user'));
}
public function archive(User $user)
{
    abort_if(!in_array($user->role, ['employer','candidate'], true), 404);

    $user->archived_at = now();
    $user->is_active = false; // archived users should be disabled
    $user->save();

    return back()->with('success', 'User archived.');
}

public function restore(User $user)
{
    abort_if(!in_array($user->role, ['employer','candidate'], true), 404);

    $user->archived_at = null;
    $user->save();

    return back()->with('success', 'User restored.');
}
public function edit(User $user)
{
    abort_if(!in_array($user->role, ['employer','candidate'], true), 404);

    $user->load('employerProfile');

    return view('adminpage.contents.users-edit', compact('user'));
}



    public function update(Request $request, User $user)
    {
        abort_if(!in_array($user->role, ['employer','candidate'], true), 404);

        $data = $request->validate([
            'first_name' => ['required','string','max:255'],
            'last_name'  => ['required','string','max:255'],
            'email'      => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'phone'      => ['nullable','string','max:50'],
            'employer_status' => ['nullable', Rule::in(['pending','approved','rejected'])],
        ]);

        $user->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'name'       => $data['first_name'].' '.$data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
        ]);

        if ($user->role === 'employer' && !empty($data['employer_status'])) {
            $profile = EmployerProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => '—',
                    'company_email' => $user->email,
                    'company_address' => '—',
                    'company_contact' => '—',
                    'representative_name' => '—',
                    'position' => '—',
                    'status' => 'pending',
                ]
            );

            $profile->status = $data['employer_status'];
            $profile->save();
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated.');
    }

    public function toggle(User $user)
    {
        abort_if($user->role === 'admin', 403);

        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'User status updated.');
    }

    public function approveEmployer(User $user)
    {
        abort_if($user->role !== 'employer', 404);

        $profile = EmployerProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => '—',
                'company_email' => $user->email,
                'company_address' => '—',
                'company_contact' => '—',
                'representative_name' => '—',
                'position' => '—',
                'status' => 'pending',
            ]
        );

        if ($profile->status !== 'pending') {
            return back()->with('success', 'Employer already processed.');
        }

        $profile->status = 'approved';
        $profile->save();

        $user->is_active = true;
        $user->save();

        return back()->with('success', 'Employer approved successfully.');
    }
}
