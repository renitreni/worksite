<?php



namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ResumeService;

class ResumeController extends Controller
{
    protected $service;

    public function __construct(ResumeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getAll();

        $resumes = $data['resumes'];
        $stats = $data['stats'];

        return view('adminpage.contents.cv-table', compact('resumes', 'stats'));
    }
}