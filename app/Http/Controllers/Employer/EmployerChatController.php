<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\ChatMessageSent;
use App\Services\EmployerAccessService;

class EmployerChatController extends Controller
{
    protected EmployerAccessService $access;

    public function __construct(EmployerAccessService $access)
    {
        $this->access = $access;
    }
    /**
     * Show the Messenger-style chat page
     * 
     * @param JobApplication|null $application
     */
    public function index(?JobApplication $application = null)
    {
        $profile = $this->access->requireApprovedEmployerProfile();

        // 🔒 Block if plan does not allow messaging
        abort_unless(
            $this->access->canUseDirectMessaging($profile),
            403,
            'Upgrade your plan to use Direct Messaging.'
        );

        $currentUser = Auth::user();

        // Get all applications for jobs of this employer
        $applications = JobApplication::whereHas('jobPost', function ($q) use ($currentUser) {
            $q->where('employer_profile_id', $currentUser->employerProfile->id);
        })->with('candidateProfile.user', 'jobPost')->get();

        // If no application selected, pick the first one
        if (!$application && $applications->isNotEmpty()) {
            $application = $applications->first();
        }

        $chats = $application
            ? $application->chats()->with('sender')->orderBy('created_at')->get()
            : collect();

        return view('employer.contents.chat-messenger', compact('applications', 'application', 'chats'));
    }

    /**
     * Store a new chat message
     */
    public function store(Request $request, JobApplication $application)
    {
        $profile = $this->access->requireApprovedEmployerProfile();

        abort_unless(
            $this->access->canUseDirectMessaging($profile),
            403,
            'Upgrade your plan to send messages.'
        );

        $currentUser = Auth::user();

        // Authorization check
        if ($application->jobPost->employer_profile_id != $currentUser->employerProfile->id) {
            abort(403);
        }

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