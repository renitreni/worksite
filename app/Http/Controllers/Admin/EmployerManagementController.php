<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminManagementRequest;
class EmployerManagementController extends Controller
{
    public function index()
    {
        $requests = AdminManagementRequest::with('employer.user')
            ->latest()
            ->get();

        return view('adminpage.contents.employer.requests', compact('requests'));
    }

    public function approve($id)
    {
        $req = AdminManagementRequest::findOrFail($id);

        $req->update(['status' => 'approved']);

        $req->employer->update([
            'allow_admin_management' => true
        ]);

        return back()->with('success', 'Request approved.');
    }

    public function decline($id)
    {
        $req = AdminManagementRequest::findOrFail($id);

        $req->update(['status' => 'declined']);

        return back()->with('success', 'Request declined.');
    }
}
