<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $query = User::query()->where('role', 'admin');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $admins = $query->latest('id')->paginate(10)->withQueryString();

        return view('adminpage.contents.admins.index', compact('admins', 'q'));
    }

    public function create()
    {
        return view('adminpage.contents.admins.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['required', 'string', 'max:80'],
            'email'      => ['required', 'email', 'max:190', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'name'       => $data['first_name'] . ' ' . $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => 'admin',
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin created.');
    }

    public function edit(User $user)
    {
        abort_if($user->role !== 'admin', 404);

        return view('adminpage.contents.admins.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->role !== 'admin', 404);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['required', 'string', 'max:80'],
            'email'      => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'name'       => $data['first_name'] . ' ' . $data['last_name'],
            'email'      => $data['email'],
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated.');
    }

    public function toggle(User $user)
    {
        abort_if($user->role !== 'admin', 404);

        // Don’t allow disabling yourself (basic safety)
        abort_if(Auth::id() === $user->id, 403);

        if (Schema::hasColumn('users', 'is_active')) {
            $user->is_active = !$user->is_active;
            $user->save();
            return back()->with('success', 'Admin status updated.');
        }

        if (Schema::hasColumn('users', 'status')) {
            $user->status = ($user->status === 'Active') ? 'Disabled' : 'Active';
            $user->save();
            return back()->with('success', 'Admin status updated.');
        }

        return back()->with('error', 'No status column found (add is_active or status).');
    }

    public function resetPassword(Request $request, User $user)
    {
        abort_if($user->role !== 'admin', 404);
        abort_if(Auth::id() === $user->id, 403);

        $data = $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user->password = Hash::make($data['password']);
        $user->save();

        return back()->with('success', 'Password reset.');
    }
}
