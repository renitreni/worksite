<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Admin\AdminPlatformUserService;

class UserController extends Controller
{
    public function __construct(
        private AdminPlatformUserService $userService
    ) {}

    public function index(Request $request)
    {
        $data = $this->userService->getUsers($request);

        return view('adminpage.contents.users', $data);
    }

    public function show(User $user)
    {
        $this->userService->authorizeUserRole($user);

        $user = $this->userService->loadUserRelations($user);

        return view('adminpage.contents.users-show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->userService->authorizeUserRole($user);

        $user = $this->userService->loadUserRelations($user);

        return view('adminpage.contents.users-edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->userService->updateUser($request, $user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated.');
    }

    public function archive(User $user)
    {
        $this->userService->archiveUser($user);

        return back()->with('success', 'User archived.');
    }

    public function restore(User $user)
    {
        $this->userService->restoreUser($user);

        return back()->with('success', 'User restored.');
    }

    public function toggle(User $user)
    {
        $this->userService->toggleUserStatus($user);

        return back()->with('success', 'User status updated.');
    }

    public function setStatus(Request $request, User $user)
    {
        $this->userService->setStatus($request, $user);

        return back()->with('success', 'User status updated.');
    }

    public function approve(User $user)
    {
        $this->userService->approveEmployer($user);

        return back()->with('success', 'Employer approved successfully.');
    }

    public function reject(User $user, Request $request)
    {
        $this->userService->rejectEmployer($user, $request);

        return back()->with('success', 'Employer rejected successfully.');
    }

    public function suspend(User $user, Request $request)
    {
        $this->userService->suspendEmployer($user, $request);

        return back()->with('success', 'Employer suspended.');
    }

    public function unsuspend(User $user)
    {
        $this->userService->unsuspendEmployer($user);

        return back()->with('success', 'Employer unsuspended.');
    }

    public function updateSubscription(Request $request, User $user)
    {
        $this->userService->updateSubscription($request, $user);

        return back()->with('success', 'Subscription updated.');
    }
}