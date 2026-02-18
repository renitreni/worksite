<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $archived = $request->query('archived', '0');

        $query = User::query()->whereIn('role', ['admin', 'superadmin']);

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($archived === '1') {
            $query->whereNotNull('archived_at');
        } else {
            $query->whereNull('archived_at');
        }

        $admins = $query->latest('id')->paginate(10)->withQueryString();

        return view('adminpage.contents.admins.index', compact('admins', 'q', 'archived'));
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
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'name'       => $data['first_name'].' '.$data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => 'admin',
            'account_status' => 'active',
            'archived_at' => null,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin created.');
    }

    public function edit(User $user)
    {
        abort_if(!in_array($user->role, ['admin','superadmin'], true), 404);
        return view('adminpage.contents.admins.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_if(!in_array($user->role, ['admin','superadmin'], true), 404);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['required', 'string', 'max:80'],
            'email'      => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($user->id)],
            'password'   => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->first_name = $data['first_name'];
        $user->last_name  = $data['last_name'];
        $user->name       = $data['first_name'].' '.$data['last_name'];
        $user->email      = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated.');
    }

    public function toggle(User $user)
    {
        abort_if(!in_array($user->role, ['admin','superadmin'], true), 404);

        $currentAdminId = Auth::guard('admin')->id();
        abort_if($currentAdminId && $user->id === $currentAdminId, 403);

        abort_if(!is_null($user->archived_at), 403);

        $current = $user->account_status ?? 'active';
        $user->account_status = ($current === 'active') ? 'disabled' : 'active';
        $user->save();

        return back()->with('success', 'Admin status updated.');
    }

    public function archive(User $user)
    {
        abort_if(!in_array($user->role, ['admin','superadmin'], true), 404);

        $currentAdminId = Auth::guard('admin')->id();
        abort_if($currentAdminId && $user->id === $currentAdminId, 403);

        abort_if(!is_null($user->archived_at), 403);

        $user->archived_at = now();
        $user->account_status = 'disabled';
        $user->save();

        return back()->with('success', 'Admin archived.');
    }

    public function restore(User $user)
    {
        abort_if(!in_array($user->role, ['admin','superadmin'], true), 404);

        abort_if(is_null($user->archived_at), 403);

        $user->archived_at = null;

        // safer: if empty, default to active
        if (empty($user->account_status)) {
            $user->account_status = 'active';
        }

        $user->save();

        return back()->with('success', 'Admin restored.');
    }

    public function resetPassword(Request $request, User $user)
    {
        abort_if(!in_array($user->role, ['admin','superadmin'], true), 404);

        $currentAdminId = Auth::guard('admin')->id();
        abort_if($currentAdminId && $user->id === $currentAdminId, 403);

        $data = $request->validate([
            'password' => ['required','string','min:8','confirmed'],
        ]);

        $user->password = Hash::make($data['password']);
        $user->save();

        return back()->with('success', 'Password updated.');
    }
}
