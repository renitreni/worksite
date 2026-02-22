<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\CandidateProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApplicantController extends Controller
{
    // Unified applicant listing with optional status filter
    public function index(Request $request)
    {
        $status = $request->query('status'); // 'all', 'shortlisted', 'rejected', or null

        $query = CandidateProfile::with('user');

        if ($status && $status != 'all') {
            $query->where('status', $status);
        }

        $candidates = $query->get();

        return view('employer.contents.applicants.index', compact('candidates', 'status'));
    }

    public function show(CandidateProfile $candidate)
    {
        return view('employer.contents.applicants.show', compact('candidate'));
    }

    public function shortlist(CandidateProfile $candidate)
    {
        if ($candidate->status === 'new') {
            $candidate->update(['status' => 'shortlisted']);
        }
        return back()->with('success', 'Applicant shortlisted.');
    }

    public function interview(CandidateProfile $candidate)
    {
        if ($candidate->status === 'shortlisted') {
            $candidate->update(['status' => 'interview']);
        }
        return back()->with('success', 'Applicant moved to Interview stage.');
    }

    public function hire(CandidateProfile $candidate)
    {
        if ($candidate->status === 'interview') {
            $candidate->update(['status' => 'hired']);
        }
        return back()->with('success', 'Applicant Hired!');
    }

    public function reject(CandidateProfile $candidate)
    {
        if (in_array($candidate->status, ['new', 'shortlisted'])) {
            $candidate->update(['status' => 'rejected']);
        }
        return back()->with('error', 'Applicant rejected.');
    }

    public function export(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = CandidateProfile::with('user');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $candidates = $query->get();

        // CSV header
        $csvData = "Name,Email,Phone,Status,Applied Position\n";

        foreach ($candidates as $c) {
            $csvData .= implode(',', [
                $c->user->name ?? '',
                $c->user->email ?? '',
                $c->contact_e164 ?? '',
                ucfirst($c->status ?? 'new'),
                $c->bio ?? ''
            ]) . "\n";
        }

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="applicants.csv"',
        ]);
    }
}
