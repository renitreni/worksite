<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'user_welcome',
                'subject' => 'Welcome to Workabroad!',
                'body_html' => '<p>Hi {USER_NAME},</p><p>Welcome to Workabroad. Start applying today!</p>',
                'placeholders' => ['{USER_NAME}'],
            ],
            [
                'name' => 'verify_email',
                'subject' => 'Verify your email address',
                'body_html' => '<p>Hi {USER_NAME},</p><p>Verify your email here: <a href="{VERIFY_LINK}">{VERIFY_LINK}</a></p>',
                'placeholders' => ['{USER_NAME}', '{VERIFY_LINK}'],
            ],
            [
                'name' => 'reset_password',
                'subject' => 'Reset your password',
                'body_html' => '<p>Hi {USER_NAME},</p><p>Reset your password: <a href="{RESET_LINK}">{RESET_LINK}</a></p>',
                'placeholders' => ['{USER_NAME}', '{RESET_LINK}'],
            ],
            [
                'name' => 'application_submitted',
                'subject' => 'Application submitted: {JOB_TITLE}',
                'body_html' => '<p>Hi {USER_NAME},</p><p>Your application for {JOB_TITLE} at {COMPANY_NAME} was submitted.</p><p>Track it here: <a href="{APPLICATION_LINK}">{APPLICATION_LINK}</a></p>',
                'placeholders' => ['{USER_NAME}', '{JOB_TITLE}', '{COMPANY_NAME}', '{APPLICATION_LINK}'],
            ],
            [
                'name' => 'application_status_changed',
                'subject' => 'Application update: {JOB_TITLE}',
                'body_html' => '<p>Hi {USER_NAME},</p><p>Your application for {JOB_TITLE} is now: <strong>{STATUS}</strong>.</p>',
                'placeholders' => ['{USER_NAME}', '{JOB_TITLE}', '{STATUS}'],
            ],
            [
                'name' => 'employer_welcome',
                'subject' => 'Welcome to Workabroad Employers',
                'body_html' => '<p>Hi {COMPANY_NAME},</p><p>Welcome! You can start posting jobs after approval.</p>',
                'placeholders' => ['{COMPANY_NAME}'],
            ],
            [
                'name' => 'job_post_approved',
                'subject' => 'Your job post is approved: {JOB_TITLE}',
                'body_html' => '<p>Hi {COMPANY_NAME},</p><p>Your job post "{JOB_TITLE}" is approved and now live.</p>',
                'placeholders' => ['{COMPANY_NAME}', '{JOB_TITLE}'],
            ],
        ];

        foreach ($templates as $t) {
            EmailTemplate::updateOrCreate(
                ['name' => $t['name']],
                [
                    'subject' => $t['subject'],
                    'body_html' => $t['body_html'],
                    'body_text' => null,
                    'placeholders' => $t['placeholders'],
                    'is_active' => true,
                ]
            );
        }
    }
}