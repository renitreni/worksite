<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerChatController extends Controller
{
    /**
     * Show the Messenger-style chat page
     * 
     * @param JobApplication|null $application
     */
    public function index(?JobApplication $application = null)
    {
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
        $currentUser = Auth::user();

        // Authorization check
        if ($application->jobPost->employer_profile_id != $currentUser->employerProfile->id) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        Chat::create([
            'job_application_id' => $application->id,
            'sender_id' => $currentUser->id,
            'message' => $request->message,
        ]);

        return redirect()->route('employer.chat.index', $application->id);
    }
}