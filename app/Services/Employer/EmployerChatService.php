<?php

namespace App\Services\Employer;

use App\Models\Chat;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\ChatMessageSent;

class EmployerChatService
{
    public function __construct(
        private EmployerAccessService $accessService
    ) {}

    public function getChatPageData(?JobApplication $application = null): array
    {
        $profile = $this->accessService->requireApprovedEmployerProfile();

        // Check subscription permission
        abort_unless(
            $this->accessService->canUseDirectMessaging($profile),
            403,
            'Upgrade your plan to use Direct Messaging.'
        );

        $currentUser = Auth::user();

        $applications = JobApplication::whereHas(
            'jobPost',
            fn($q) => $q->where(
                'employer_profile_id',
                $currentUser->employerProfile->id
            )
        )
        ->with('candidateProfile.user','jobPost')
        ->get();

        if (!$application && $applications->isNotEmpty()) {
            $application = $applications->first();
        }

        $chats = $application
            ? $application->chats()
                ->with('sender')
                ->orderBy('created_at')
                ->get()
            : collect();

        return [
            'applications' => $applications,
            'application' => $application,
            'chats' => $chats
        ];
    }

    public function sendMessage(Request $request, JobApplication $application)
    {
        $profile = $this->accessService->requireApprovedEmployerProfile();

        abort_unless(
            $this->accessService->canUseDirectMessaging($profile),
            403,
            'Upgrade your plan to send messages.'
        );

        $currentUser = Auth::user();

        // Authorization
        abort_if(
            $application->jobPost->employer_profile_id !== $currentUser->employerProfile->id,
            403
        );

        $request->validate([
            'message' => 'required|string',
        ]);

        $chat = Chat::create([
            'job_application_id' => $application->id,
            'sender_id' => $currentUser->id,
            'message' => $request->message
        ]);

        broadcast(new ChatMessageSent($chat))->toOthers();

        return redirect()->route('employer.chat.index', $application->id);
    }
}