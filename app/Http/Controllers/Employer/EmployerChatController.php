<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use App\Services\Employer\EmployerChatService;

class EmployerChatController extends Controller
{
    public function __construct(
        private EmployerChatService $chatService
    ) {}

    public function index(?JobApplication $application = null)
    {
        $data = $this->chatService->getChatPageData($application);

        return view('employer.contents.chat-messenger', $data);
    }

    public function store(Request $request, JobApplication $application)
    {
        return $this->chatService->sendMessage($request, $application);
    }
}