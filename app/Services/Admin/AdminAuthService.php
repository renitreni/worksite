<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\EmailTemplate;
use App\Support\EmailTemplateRenderer;
use App\Mail\SystemTemplateMail;
use App\Support\AdminActivity;

class AdminAuthService
{
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

        if (!$ok) {
            return back()
                ->withErrors(['email' => 'Invalid credentials.'])
                ->onlyInput('email');
        }

        $user = Auth::guard('admin')->user();

        if (!in_array($user->role, ['admin','superadmin'], true)) {
            return $this->logoutWithError($request,'Not allowed.');
        }

        if (!is_null($user->archived_at)) {
            return $this->logoutWithError($request,'Account is archived.');
        }

        if (($user->account_status ?? 'active') !== 'active') {
            return $this->logoutWithError($request,'Account is not active.');
        }

        $request->session()->regenerate();

        AdminActivity::log('admin_login');

        return redirect()->route('admin.dashboard');
    }

    public function getInviteFormData(Request $request)
    {
        $email = trim((string)$request->query('email',''));
        $token = trim((string)$request->query('token',''));

        abort_if($email === '' || $token === '',404);

        $user = User::where('email',$email)
            ->whereIn('role',['admin','superadmin'])
            ->first();

        abort_if(!$user,404);
        abort_if(!is_null($user->archived_at),403);

        $row = DB::table('password_reset_tokens')
            ->where('email',$email)
            ->first();

        abort_if(!$row,404);

        $isExpired = Carbon::parse($row->created_at)
            ->addHours(24)
            ->isPast();

        abort_if($isExpired,403,'Invitation link has expired.');

        abort_if(!Hash::check($token,$row->token),403,'Invalid invitation link.');

        return [
            'email'=>$email,
            'token'=>$token,
            'user'=>$user
        ];
    }

    public function acceptInvite(Request $request)
    {
        $data = $request->validate([
            'email'=>['required','email'],
            'token'=>['required','string'],
            'password'=>['required','string','min:8','confirmed'],
        ]);

        $user = User::where('email',$data['email'])
            ->whereIn('role',['admin','superadmin'])
            ->first();

        if(!$user){
            return back()->withErrors(['email'=>'Invitation is invalid.']);
        }

        $row = DB::table('password_reset_tokens')
            ->where('email',$data['email'])
            ->first();

        if(!$row){
            return back()->withErrors([
                'email'=>'Invitation invalid or already used.'
            ]);
        }

        $isExpired = Carbon::parse($row->created_at)
            ->addHours(24)
            ->isPast();

        if($isExpired){
            return back()->withErrors([
                'email'=>'Invitation expired.'
            ]);
        }

        if(!Hash::check($data['token'],$row->token)){
            return back()->withErrors([
                'email'=>'Invalid invitation token.'
            ]);
        }

        $user->update([
            'password'=>Hash::make($data['password']),
            'email_verified_at'=>now(),
            'account_status'=>'active'
        ]);

        DB::table('password_reset_tokens')
            ->where('email',$data['email'])
            ->delete();

        $this->sendAccountReadyEmail($user);

        return redirect()
            ->route('admin.login')
            ->with('success','Admin account activated.');
    }

    public function logout(Request $request)
    {
        AdminActivity::log('admin_logout');

        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success','Logged out successfully.');
    }

    private function logoutWithError(Request $request,$message)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()
            ->withErrors(['email'=>$message])
            ->onlyInput('email');
    }

    private function sendAccountReadyEmail($user)
    {
        $template = EmailTemplate::where('name','admin_account_ready')
            ->where('is_active',true)
            ->first();

        if(!$template){
            return;
        }

        $rendered = EmailTemplateRenderer::render(
            $template->subject,
            $template->body_text,
            $template->body_html,
            [
                'FULL_NAME'=>$user->name,
                'SITE_NAME'=>'JobAbroad',
                'LOGIN_LINK'=>route('admin.login'),
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
}