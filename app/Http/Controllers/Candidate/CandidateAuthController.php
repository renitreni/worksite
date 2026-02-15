<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Mail\CandidateVerifyEmailCodeMail;
use App\Models\User;
use App\Models\CandidateProfile;
use App\Models\EmailVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class CandidateAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register-candidate');
    }

    private function sendVerificationCode(User $user): void
    {
        // invalidate old codes
        EmailVerificationCode::where('user_id', $user->id)->delete();

        $code = (string) random_int(100000, 999999);

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new CandidateVerifyEmailCodeMail($code, 10));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],

            'contact_number' => ['required', 'string', 'max:30'],
            'contact_e164'   => ['nullable', 'string', 'max:40'],

            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'name'       => $fullName,
            'email'      => $validated['email'],
            'phone'      => $validated['contact_e164'] ?: $validated['contact_number'],
            'role'       => 'candidate',
            'password'   => $validated['password'], // hashed by cast
            'email_verified_at' => null,
        ]);

        CandidateProfile::create([
            'user_id'         => $user->id,
            'contact_number'  => $validated['contact_number'],
            'contact_e164'    => $validated['contact_e164'] ?: null,
        ]);

        // Send code (NO dashboard redirect yet)
        $this->sendVerificationCode($user);

        // store user id in session so the modal can verify
        return back()->with([
            'verify_user_id' => $user->id,
            'verify_email' => $user->email,
            'show_verify_modal' => true,
        ]);
    }

    public function verifyEmailCode(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'code'    => ['required', 'digits:6'],
        ]);

        // Ensure this is really a candidate
        $user = User::where('id', $data['user_id'])
            ->where('role', 'candidate')
            ->firstOrFail();

        // If already verified, just tell frontend to go login
        if ($user->email_verified_at) {
            return response()->json([
                'ok' => true,
                'redirect' => route('candidate.login'),
                'message' => 'Email already verified.',
            ]);
        }

        // Get latest code record
        $record = EmailVerificationCode::where('user_id', $user->id)
            ->latest()
            ->first();

        // If no record or expired
        if (!$record || now()->greaterThan($record->expires_at)) {
            return response()->json([
                'ok' => false,
                'message' => 'Code expired. Please resend a new code.',
            ], 422);
        }

        // Check the code
        if (!Hash::check($data['code'], $record->code_hash)) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid code. Please try again.',
            ], 422);
        }

        // Mark verified + delete code records
        $user->forceFill(['email_verified_at' => now()])->save();
        EmailVerificationCode::where('user_id', $user->id)->delete();

        // ✅ No Auth::login() since you want to redirect to login page
        return response()->json([
            'ok' => true,
            'redirect' => route('candidate.login'),
            'message' => 'Verified successfully.',
        ]);
    }


    public function resendEmailCode(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $user = User::where('id', $data['user_id'])->where('role', 'candidate')->firstOrFail();

        if ($user->email_verified_at) {
            return response()->json(['ok' => true, 'message' => 'Email already verified.']);
        }

        // basic rate limit in app-level (optional)
        // You can also use Laravel throttle middleware
        $this->sendVerificationCode($user);

        return response()->json(['ok' => true, 'message' => 'A new code has been sent to your email.']);
    }

    public function showLogin()
    {
        return view('auth.login-candidate');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials['role'] = 'candidate';

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Invalid candidate email or password.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // block dashboard if not verified
        if (!Auth::user()->email_verified_at) {
            $user = Auth::user();
            Auth::logout();

            // send fresh code
            $this->sendVerificationCode($user);

            return back()->with([
                'unverified_modal' => true,        // ✅ show "not verified" modal
                'verify_user_id' => $user->id,
                'verify_email' => $user->email,
                'show_verify_modal' => false,      // ✅ don't show code modal immediately
            ])->withErrors(['email' => 'Please verify your email to continue.']);
        }


        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
