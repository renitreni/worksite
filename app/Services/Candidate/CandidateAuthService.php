<?php

namespace App\Services\Candidate;

use App\Models\User;
use App\Models\CandidateProfile;
use App\Models\EmailVerificationCode;
use App\Mail\CandidateVerifyEmailCodeMail;
use App\Notifications\AdminUserRegistered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CandidateAuthService
{
    private function sendVerificationCode(User $user)
    {
        EmailVerificationCode::where('user_id', $user->id)->delete();

        $code = random_int(100000, 999999);

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)
            ->send(new CandidateVerifyEmailCodeMail($code, 10));
    }

    public function register($request)
    {
        $validated = $request->validated();

        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'name' => $fullName,
            'email' => $validated['email'],
            'phone' => $validated['contact_e164'] ?: $validated['contact_number'],
            'role' => 'candidate',
            'password' => $validated['password'],
            'email_verified_at' => null,
        ]);

        User::where('role', 'admin')
            ->orWhere('role', 'superadmin')
            ->get()
            ->each(function ($admin) use ($user) {
                $admin->notify(new AdminUserRegistered($user));
            });

        CandidateProfile::create([
            'user_id' => $user->id,
            'contact_number' => $validated['contact_number'],
            'contact_e164' => $validated['contact_e164'] ?: null,
        ]);

        $this->sendVerificationCode($user);

        return back()->with([
            'verify_user_id' => $user->id,
            'verify_email' => $user->email,
            'show_verify_modal' => true
        ]);
    }

    public function verifyEmailCode($request)
    {
        $data = $request->validated();

        $user = User::where('id', $data['user_id'])
            ->where('role', 'candidate')
            ->firstOrFail();

        if ($user->email_verified_at) {
            return response()->json([
                'ok' => true,
                'redirect' => route('candidate.login'),
                'message' => 'Email already verified.'
            ]);
        }

        $record = EmailVerificationCode::where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$record || now()->greaterThan($record->expires_at)) {
            return response()->json([
                'ok' => false,
                'message' => 'Code expired.'
            ], 422);
        }

        if (!Hash::check($data['code'], $record->code_hash)) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid code.'
            ], 422);
        }

        $user->forceFill([
            'email_verified_at' => now()
        ])->save();

        EmailVerificationCode::where('user_id', $user->id)->delete();

        return response()->json([
            'ok' => true,
            'redirect' => route('candidate.login'),
            'message' => 'Verified successfully.'
        ]);
    }

    public function resendEmailCode($request)
    {
        $user = User::findOrFail($request->user_id);

        if ($user->email_verified_at) {
            return response()->json([
                'ok' => true,
                'message' => 'Email already verified.'
            ]);
        }

        $this->sendVerificationCode($user);

        return response()->json([
            'ok' => true,
            'message' => 'New code sent.'
        ]);
    }

    public function login($request)
    {
        $credentials = $request->validated();
        $credentials['role'] = 'candidate';

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Invalid candidate email or password.'
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->account_status === 'disabled') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Account disabled.'
            ]);
        }

        if ($user->account_status === 'hold') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Account under review.'
            ]);
        }

        if (!$user->email_verified_at) {

            Auth::logout();

            $this->sendVerificationCode($user);

            return back()
                ->withErrors(['email' => 'Please verify your email'])
                ->with([
                    'verify_user_id' => $user->id,
                    'verify_email' => $user->email,
                    'unverified_modal' => true
                ]);
        }

        return redirect()->route('home');
    }

    public function logout($request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}