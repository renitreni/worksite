<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\RegisterEmployerRequest;
use App\Http\Requests\Employer\LoginEmployerRequest;
use App\Services\Employer\EmployerAuthService;
use Illuminate\Http\Request;

class EmployerAuthController extends Controller
{
    protected $authService;

    public function __construct(EmployerAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegister()
    {
        return view('auth.register-employer');
    }

    public function register(RegisterEmployerRequest $request)
    {
        return $this->authService->register($request);
    }

    public function showLogin()
    {
        return view('auth.login-employer');
    }

    public function login(LoginEmployerRequest $request)
    {
        return $this->authService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }
}