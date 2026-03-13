<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Services\Candidate\FollowingEmployerService;

class FollowingEmployerController extends Controller
{
    protected $followingEmployerService;

    public function __construct(FollowingEmployerService $followingEmployerService)
    {
        $this->followingEmployerService = $followingEmployerService;
    }

    public function index()
    {
        return $this->followingEmployerService->getFollowedEmployers();
    }
}