<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\System\UpdateEmailTemplateRequest;
use App\Services\Admin\AdminEmailTemplateService;

class EmailTemplateController extends Controller
{
    public function __construct(
        private AdminEmailTemplateService $templateService
    ) {
        $this->middleware(function ($request, $next) {

            if (auth('admin')->user()->role !== 'superadmin') {
                abort(403);
            }

            return $next($request);

        });
    }

    public function index()
    {
        $templates = $this->templateService->getTemplates();

        return view('adminpage.email_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('adminpage.email_templates.create');
    }

    public function store(Request $request)
    {
        $template = $this->templateService->createTemplate($request);

        return redirect()
            ->route('admin.email_templates.edit', $template)
            ->with('success', 'Email template created.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('adminpage.email_templates.edit', [
            'template' => $emailTemplate
        ]);
    }

    public function update(UpdateEmailTemplateRequest $request, EmailTemplate $emailTemplate)
    {
        $this->templateService->updateTemplate($emailTemplate, $request->validated());

        return redirect()
            ->route('admin.email_templates.edit', $emailTemplate)
            ->with('success', 'Email template updated.');
    }

    public function preview(Request $request, EmailTemplate $emailTemplate)
    {
        $rendered = $this->templateService->previewTemplate($emailTemplate);

        return response()->json($rendered);
    }
}