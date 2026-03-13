<?php

namespace App\Services\Candidate;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\Chat;
use App\Events\ChatMessageSent;

class CandidateChatService
{
    public function showMessenger(?JobApplication $application)
    {
        $user = Auth::user();

        $applications = JobApplication::where('candidate_id', $user->id)
            ->with('jobPost', 'candidateProfile.user')
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

        if ($application && $application->candidate_id != $user->id) {
            abort(403);
        }

        $employerStarted = $chats
            ->where('sender_id', '!=', $user->id)
            ->count() > 0;

        return view('candidate.contents.chat-messenger', [
            'applications' => $applications,
            'application' => $application,
            'chats' => $chats,
            'canReply' => $employerStarted
        ]);
    }

    public function sendMessage(Request $request, JobApplication $application)
    {
        $user = Auth::user();

        $employerStarted = $application->chats()
            ->where('sender_id', '!=', $user->id)
            ->exists();

        if (!$employerStarted) {
            return back()->with(
                'error',
                'Employer must start the conversation first.'
            );
        }

        $request->validate([
            'message' => 'required|string'
        ]);

        $chat = Chat::create([
            'job_application_id' => $application->id,
            'sender_id' => $user->id,
            'message' => $request->message
        ]);

        broadcast(new ChatMessageSent($chat))->toOthers();

        return back();
    }
}