<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\EmailTemplate;
use App\Support\EmailTemplateRenderer;
use App\Mail\SystemTemplateMail;

class AdminAdminUserService
{
    public function getAdmins(Request $request)
    {
        $q = trim((string)$request->query('q', ''));
        $archived = (string)$request->query('archived', '0');

        $admins = User::query()
            ->whereIn('role', ['admin','superadmin'])
            ->when($archived === '1', fn($q)=>$q->whereNotNull('archived_at'))
            ->when($archived !== '1', fn($q)=>$q->whereNull('archived_at'))
            ->when($q !== '', function ($qr) use ($q) {

                $qr->where(function ($w) use ($q) {

                    $w->where('name','like',"%{$q}%")
                      ->orWhere('email','like',"%{$q}%")
                      ->orWhere('first_name','like',"%{$q}%")
                      ->orWhere('last_name','like',"%{$q}%")
                      ->orWhereRaw("concat(first_name,' ',last_name) like ?",["%{$q}%"]);

                });

            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return compact('admins','q','archived');
    }

    public function authorizeAdmin(User $user)
    {
        abort_if(
            !in_array($user->role,['admin','superadmin'],true),
            404
        );
    }

    public function createAdmin(Request $request)
    {
        $data = $request->validate([
            'first_name'=>['required','string','max:80'],
            'last_name'=>['required','string','max:80'],
            'email'=>['required','email','max:190','unique:users,email'],
        ]);

        $user = User::create([
            'first_name'=>$data['first_name'],
            'last_name'=>$data['last_name'],
            'name'=>$data['first_name'].' '.$data['last_name'],
            'email'=>$data['email'],
            'password'=>Hash::make(Str::random(32)),
            'role'=>'admin',
            'account_status'=>'pending_invitation',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email'=>$user->email],
            [
                'email'=>$user->email,
                'token'=>Hash::make($token),
                'created_at'=>now()
            ]
        );

        $inviteLink = route('admin.invite.accept',[
            'email'=>$user->email,
            'token'=>$token
        ]);

        $this->sendInvitationEmail($user,$inviteLink);
    }

    public function updateAdmin(Request $request, User $user)
    {
        $this->authorizeAdmin($user);

        $data = $request->validate([
            'first_name'=>['required','string','max:80'],
            'last_name'=>['required','string','max:80'],
            'email'=>[
                'required','email','max:190',
                Rule::unique('users','email')->ignore($user->id)
            ],
            'password'=>['nullable','string','min:8','confirmed']
        ]);

        $user->update([
            'first_name'=>$data['first_name'],
            'last_name'=>$data['last_name'],
            'name'=>$data['first_name'].' '.$data['last_name'],
            'email'=>$data['email']
        ]);

        if(!empty($data['password'])){
            $user->update([
                'password'=>Hash::make($data['password'])
            ]);
        }
    }

    public function toggleStatus(User $user)
    {
        $this->authorizeAdmin($user);

        $currentAdminId = Auth::guard('admin')->id();

        abort_if($user->id === $currentAdminId,403);
        abort_if(!is_null($user->archived_at),403);

        $current = $user->account_status ?? 'active';

        $user->update([
            'account_status' =>
                $current === 'active'
                ? 'disabled'
                : 'active'
        ]);
    }

    public function archiveAdmin(User $user)
    {
        $this->authorizeAdmin($user);

        $currentAdminId = Auth::guard('admin')->id();

        abort_if($user->id === $currentAdminId,403);
        abort_if(!is_null($user->archived_at),403);

        $user->update([
            'archived_at'=>now(),
            'account_status'=>'disabled'
        ]);
    }

    public function restoreAdmin(User $user)
    {
        $this->authorizeAdmin($user);

        abort_if(is_null($user->archived_at),403);

        $user->update([
            'archived_at'=>null,
            'account_status'=>$user->account_status ?: 'active'
        ]);
    }

    public function resetPassword(Request $request, User $user)
    {
        $this->authorizeAdmin($user);

        $currentAdminId = Auth::guard('admin')->id();
        abort_if($user->id === $currentAdminId,403);

        $data = $request->validate([
            'password'=>['required','string','min:8','confirmed']
        ]);

        $user->update([
            'password'=>Hash::make($data['password'])
        ]);
    }

    private function sendInvitationEmail($user,$inviteLink)
    {
        $template = EmailTemplate::where('name','admin_invitation')
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
                'INVITE_LINK'=>$inviteLink,
                'EXPIRES_IN_HOURS'=>24,
                'SUPERADMIN_NAME'=>auth('admin')->user()->name ?? 'System'
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