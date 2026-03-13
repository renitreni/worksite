<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidate\RegisterCandidateRequest;
use App\Http\Requests\Candidate\LoginCandidateRequest;
use App\Http\Requests\Candidate\VerifyEmailCodeRequest;
use App\Services\Candidate\CandidateAuthService;
use Illuminate\Http\Request;

class CandidateAuthController extends Controller
{
    protected $authService;

    public function __construct(CandidateAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegister()
    {
        return view('auth.register-candidate');
    }

    public function register(RegisterCandidateRequest $request)
    {
        return $this->authService->register($request);
    }

    public function verifyEmailCode(VerifyEmailCodeRequest $request)
    {
        return $this->authService->verifyEmailCode($request);
    }

    public function resendEmailCode(Request $request)
    {
        return $this->authService->resendEmailCode($request);
    }

    public function showLogin()
    {
        return view('auth.login-candidate');
    }

    public function login(LoginCandidateRequest $request)
    {
        return $this->authService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }
}