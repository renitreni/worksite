<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\AdminAuthService;

class AdminAuthController extends Controller
{
    public function __construct(
        private AdminAuthService $authService
    ) {}

    public function showLogin()
    {
        return view('admin-auth.adminlogin');
    }

    public function login(Request $request)
    {
        return $this->authService->login($request);
    }

    public function showInviteAcceptForm(Request $request)
    {
        $data = $this->authService->getInviteFormData($request);

        return view('admin-auth.accept-invite', $data);
    }

    public function acceptInvite(Request $request)
    {
        return $this->authService->acceptInvite($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }
}