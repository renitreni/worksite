<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\System\UpdateEmailTemplateRequest;
use App\Support\EmailTemplateRenderer;

class EmailTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth('admin')->user()->role !== 'superadmin') {
                abort(403);
            }

            return $next($request);
        });
    }

    public function index()
    {
        $templates = EmailTemplate::query()
            ->orderBy('name')
            ->paginate(15);

        return view('adminpage.email_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('adminpage.email_templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:email_templates,name'],
            'subject' => ['required', 'string', 'max:255'],
            'body_text' => ['required', 'string'],
            'body_html' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $template = EmailTemplate::create([
            'name' => strtolower(trim($data['name'])),
            'subject' => $data['subject'],
            'body_text' => $data['body_text'],
            'body_html' => $data['body_html'] ?? '',
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return redirect()
            ->route('admin.email_templates.edit', $template)
            ->with('success', 'Email template created.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('adminpage.email_templates.edit', [
            'template' => $emailTemplate,
        ]);
    }

    public function update(UpdateEmailTemplateRequest $request, EmailTemplate $emailTemplate)
    {
        $data = $request->validated();

        $emailTemplate->update([
            'subject' => $data['subject'],
            'body_text' => $data['body_text'],
            'body_html' => $data['body_html'] ?? '',
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return redirect()
            ->route('admin.email_templates.edit', $emailTemplate)
            ->with('success', 'Email template updated.');
    }

    public function preview(Request $request, EmailTemplate $emailTemplate)
    {
        $samples = [
            'USER_NAME' => 'Juan Dela Cruz',
            'USER_EMAIL' => 'juan@example.com',
            'VERIFY_LINK' => 'https://workabroad.test/verify/sample',
            'RESET_LINK' => 'https://workabroad.test/reset/sample',
            'JOB_TITLE' => 'Warehouse Staff',
            'COMPANY_NAME' => 'ABC Recruitment',
            'APPLICATION_LINK' => 'https://workabroad.test/applications/123',
            'STATUS' => 'Under Review',
            'FULL_NAME' => 'Juan Dela Cruz',
            'INVITE_LINK' => 'https://workabroad.test/admin/invite/sample-token',
            'EXPIRES_IN_HOURS' => '24',
            'SITE_NAME' => 'JobAbroad',
            'SUPERADMIN_NAME' => 'System Super Admin',
        ];

        $rendered = EmailTemplateRenderer::render(
            $emailTemplate->subject,
            $emailTemplate->body_text,
            $emailTemplate->body_html,
            $samples
        );

        return response()->json($rendered);
    }
}