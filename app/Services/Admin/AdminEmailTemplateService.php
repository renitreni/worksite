<?php

namespace App\Services\Admin;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Support\EmailTemplateRenderer;

class AdminEmailTemplateService
{
    public function getTemplates()
    {
        return EmailTemplate::query()
            ->orderBy('name')
            ->paginate(15);
    }

    public function createTemplate(Request $request): EmailTemplate
    {
        $data = $request->validate([
            'name' => ['required','string','max:100','unique:email_templates,name'],
            'subject' => ['required','string','max:255'],
            'body_text' => ['required','string'],
            'body_html' => ['nullable','string'],
            'is_active' => ['nullable','boolean'],
        ]);

        return EmailTemplate::create([
            'name' => strtolower(trim($data['name'])),
            'subject' => $data['subject'],
            'body_text' => $data['body_text'],
            'body_html' => $data['body_html'] ?? '',
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);
    }

    public function updateTemplate(EmailTemplate $template, array $data): void
    {
        $template->update([
            'subject' => $data['subject'],
            'body_text' => $data['body_text'],
            'body_html' => $data['body_html'] ?? '',
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);
    }

    public function previewTemplate(EmailTemplate $template): array
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

        return EmailTemplateRenderer::render(
            $template->subject,
            $template->body_text,
            $template->body_html,
            $samples
        );
    }
}