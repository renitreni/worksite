<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\System\UpdateEmailTemplateRequest;

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

        // keep your folder structure
        return view('adminpage.email_templates.index', compact('templates'));
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
            'body_html' => $data['body_html'],
            'body_text' => $data['body_text'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return redirect()
            ->route('admin.email_templates.edit', $emailTemplate)
            ->with('success', 'Email template updated.');
    }

    public function preview(Request $request, EmailTemplate $emailTemplate)
    {
        /**
         * Supports BOTH placeholder styles:
         *  - {USER_NAME}
         *  - {{USER_NAME}}
         */
        $samples = [
            'USER_NAME' => 'Juan Dela Cruz',
            'USER_EMAIL' => 'juan@example.com',
            'VERIFY_LINK' => 'https://workabroad.test/verify/sample',
            'RESET_LINK' => 'https://workabroad.test/reset/sample',
            'JOB_TITLE' => 'Warehouse Staff',
            'COMPANY_NAME' => 'ABC Recruitment',
            'APPLICATION_LINK' => 'https://workabroad.test/applications/123',
            'STATUS' => 'Under Review',
        ];

        $replaceMap = [];
        foreach ($samples as $key => $value) {
            $replaceMap['{' . $key . '}'] = $value;
            $replaceMap['{{' . $key . '}}'] = $value;
        }

        $subject = strtr((string) $emailTemplate->subject, $replaceMap);
        $bodyHtml = strtr((string) $emailTemplate->body_html, $replaceMap);
        $bodyText = $emailTemplate->body_text ? strtr((string) $emailTemplate->body_text, $replaceMap) : null;

        return response()->json([
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_text' => $bodyText,
        ]);
    }
}