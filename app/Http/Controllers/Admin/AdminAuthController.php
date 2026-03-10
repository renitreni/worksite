<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\EmailTemplate;
use App\Support\EmailTemplateRenderer;
use Illuminate\Support\Facades\Mail;
use App\Mail\SystemTemplateMail;
use App\Support\AdminActivity;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin-auth.adminlogin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        $ok = Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ], $request->boolean('remember'));

        if (! $ok) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
        }

        $user = Auth::guard('admin')->user();

        if (! in_array($user->role, ['admin','superadmin'], true)) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'Not allowed.'])->onlyInput('email');
        }

        if (!is_null($user->archived_at)) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'Account is archived.'])->onlyInput('email');
        }

        if (($user->account_status ?? 'active') !== 'active') {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'Account is not active.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        AdminActivity::log('admin_login');
        return redirect()->route('admin.dashboard');
    }

    public function showInviteAcceptForm(Request $request)
{
    $email = trim((string) $request->query('email', ''));
    $token = trim((string) $request->query('token', ''));

    abort_if($email === '' || $token === '', 404);

    $user = User::query()
        ->where('email', $email)
        ->whereIn('role', ['admin', 'superadmin'])
        ->first();

    abort_if(!$user, 404);
    abort_if(!is_null($user->archived_at), 403);

    $row = DB::table('password_reset_tokens')->where('email', $email)->first();
    abort_if(!$row, 404);

    $isExpired = Carbon::parse($row->created_at)->addHours(24)->isPast();
    abort_if($isExpired, 403, 'Invitation link has expired.');

    $matches = Hash::check($token, $row->token);
    abort_if(!$matches, 403, 'Invalid invitation link.');

    return view('admin-auth.accept-invite', [
        'email' => $email,
        'token' => $token,
        'user' => $user,
    ]);
}

public function acceptInvite(Request $request)
{
    $data = $request->validate([
        'email' => ['required', 'email'],
        'token' => ['required', 'string'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = User::query()
        ->where('email', $data['email'])
        ->whereIn('role', ['admin', 'superadmin'])
        ->first();

    if (!$user) {
        return back()->withErrors([
            'email' => 'Invitation is invalid.',
        ]);
    }

    if (!is_null($user->archived_at)) {
        return back()->withErrors([
            'email' => 'This account is archived.',
        ]);
    }

    $row = DB::table('password_reset_tokens')->where('email', $data['email'])->first();

    if (!$row) {
        return back()->withErrors([
            'email' => 'Invitation is invalid or already used.',
        ]);
    }

    $isExpired = Carbon::parse($row->created_at)->addHours(24)->isPast();
    if ($isExpired) {
        return back()->withErrors([
            'email' => 'Invitation link has expired. Please ask the superadmin to resend it.',
        ]);
    }

    $matches = Hash::check($data['token'], $row->token);
    if (!$matches) {
        return back()->withErrors([
            'email' => 'Invitation token is invalid.',
        ]);
    }

    $user->password = Hash::make($data['password']);
    $user->email_verified_at = now();
    $user->account_status = 'active';
    $user->save();

    DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

    $template = EmailTemplate::query()
        ->where('name', 'admin_account_ready')
        ->where('is_active', true)
        ->first();

    if ($template) {
        $rendered = EmailTemplateRenderer::render(
            $template->subject,
            $template->body_text,
            $template->body_html,
            [
                'FULL_NAME'  => $user->name,
                'SITE_NAME'  => 'JobAbroad',
                'LOGIN_LINK' => route('admin.login'),
            ]
        );

     Mail::to($user->email)->queue(
    new SystemTemplateMail(
        $rendered['subject'],
        $rendered['body_html'],
        $rendered['body_text'] ?? null
    )
);
    }

    return redirect()
        ->route('admin.login')
        ->with('success', 'Your admin account has been activated. You can now log in.');
}
    public function logout(Request $request)
    {
        AdminActivity::log('admin_login');
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }
}