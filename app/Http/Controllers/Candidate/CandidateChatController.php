<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Services\Candidate\CandidateChatService;

class CandidateChatController extends Controller
{
    protected $chatService;

    public function __construct(CandidateChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function index(?JobApplication $application = null)
    {
        return $this->chatService->showMessenger($application);
    }

    public function store(Request $request, JobApplication $application)
    {
        return $this->chatService->sendMessage($request, $application);
    }
}