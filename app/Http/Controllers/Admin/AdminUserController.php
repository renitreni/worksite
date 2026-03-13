<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\AdminAdminUserService;
use App\Models\User;

class AdminUserController extends Controller
{
    public function __construct(
        private AdminAdminUserService $adminService
    ) {
        $this->middleware(function ($request, $next) {

            if (auth('admin')->user()->role !== 'superadmin') {
                abort(403);
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $data = $this->adminService->getAdmins($request);

        return view(
            'adminpage.contents.admins.index',
            $data
        );
    }

    public function create()
    {
        return view('adminpage.contents.admins.create');
    }

    public function store(Request $request)
    {
        $this->adminService->createAdmin($request);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin created and invitation email sent.');
    }

    public function edit(User $user)
    {
        $this->adminService->authorizeAdmin($user);

        return view(
            'adminpage.contents.admins.edit',
            compact('user')
        );
    }

    public function update(Request $request, User $user)
    {
        $this->adminService->updateAdmin($request, $user);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin updated.');
    }

    public function toggle(User $user)
    {
        $this->adminService->toggleStatus($user);

        return back()->with('success', 'Admin status updated.');
    }

    public function archive(User $user)
    {
        $this->adminService->archiveAdmin($user);

        return back()->with('success', 'Admin archived.');
    }

    public function restore(User $user)
    {
        $this->adminService->restoreAdmin($user);

        return back()->with('success', 'Admin restored.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $this->adminService->resetPassword($request, $user);

        return back()->with('success', 'Password updated.');
    }
}