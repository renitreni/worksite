<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $role = $request->query('role'); // employer|candidate|null

        $query = User::query()
            ->whereIn('role', ['employer', 'candidate'])
            ->with('employerProfile');

        if ($role && in_array($role, ['employer', 'candidate'], true)) {
            $query->where('role', $role);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->latest('id')->paginate(10)->withQueryString();

        return view('adminpage.contents.users', compact('users', 'q', 'role'));
    }

   public function edit(User $user)
{
    abort_if(!in_array($user->role, ['employer','candidate'], true), 404);

    $user->load('employerProfile'); // optional

    return view('adminpage.contents.users-edit', compact('user'));
}

public function update(Request $request, User $user)
{
    abort_if(!in_array($user->role, ['employer','candidate'], true), 404);

    $data = $request->validate([
        'first_name' => ['required','string','max:255'],
        'last_name'  => ['required','string','max:255'],
        'email'      => ['required','email','max:255', \Illuminate\Validation\Rule::unique('users','email')->ignore($user->id)],
        'role'       => ['required', \Illuminate\Validation\Rule::in(['employer','candidate'])],
    ]);

    $user->update([
        'first_name' => $data['first_name'],
        'last_name'  => $data['last_name'],
        'name'       => $data['first_name'].' '.$data['last_name'],
        'email'      => $data['email'],
        'role'       => $data['role'],
    ]);

    return redirect()->route('admin.users.index')->with('success', 'User updated.');
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

        $profile = $user->employerProfile;
        abort_if(!$profile, 404);

        if ($profile->status !== 'pending') {
            return back();
        }

        $profile->status = 'approved';
        $profile->save();

        $user->is_active = 1;
        $user->save();

        return back()->with('success', 'Employer approved successfully.');
    }
    
}