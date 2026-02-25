<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = JobApplication::query()
            ->with([
                'jobPost:id,title',
                'candidateProfile.user:id,name,email',
            ])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $applications = $query->get();

        return view('employer.contents.applicants.index', compact('applications', 'status'));
    }

    public function show(JobApplication $application)
    {
        $application->load(['jobPost', 'candidateProfile.user']);
        return view('employer.contents.applicants.show', compact('application'));
    }

    public function shortlist(JobApplication $application)
    {
        $current = strtolower(trim($application->status ?? ''));

        // ✅ allow applied/new/pending -> shortlisted
        if (in_array($current, ['applied', 'new', 'pending'], true)) {
            $application->update(['status' => 'shortlisted']);
            return back()->with('success', 'Applicant shortlisted.');
        }

        return back()->with('error', 'Cannot shortlist from current status: ' . ($application->status ?? 'N/A'));
    }

    public function interview(JobApplication $application)
    {
        $current = strtolower(trim($application->status ?? ''));

        if ($current === 'shortlisted') {
            $application->update(['status' => 'interview']);
            return back()->with('success', 'Applicant moved to Interview stage.');
        }

        return back()->with('error', 'Cannot move to interview from current status: ' . ($application->status ?? 'N/A'));
    }

    public function hire(JobApplication $application)
    {
        $current = strtolower(trim($application->status ?? ''));

        if ($current === 'interview') {
            $application->update(['status' => 'hired']);
            return back()->with('success', 'Applicant Hired!');
        }

        return back()->with('error', 'Cannot hire from current status: ' . ($application->status ?? 'N/A'));
    }

    public function reject(JobApplication $application)
    {
        $current = strtolower(trim($application->status ?? ''));

        if (!in_array($current, ['rejected', 'hired'], true)) {
            $application->update(['status' => 'rejected']);
            return back()->with('error', 'Applicant rejected.');
        }

        return back()->with('error', 'Cannot reject from current status: ' . ($application->status ?? 'N/A'));
    }

    public function export(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = JobApplication::query()
            ->with(['jobPost:id,title', 'candidateProfile.user:id,name,email'])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $applications = $query->get();

        $csvData = "Name,Email,Phone,Status,Applied Position\n";

        foreach ($applications as $app) {
            $name  = $app->candidateProfile?->user?->name ?? $app->full_name ?? '';
            $email = $app->candidateProfile?->user?->email ?? $app->email ?? '';
            $phone = $app->phone ?? '';
            $st    = ucfirst(strtolower(trim($app->status ?? 'applied')));
            $title = $app->jobPost?->title ?? '';

            $csvData .= implode(',', [
                $this->csvEscape($name),
                $this->csvEscape($email),
                $this->csvEscape($phone),
                $this->csvEscape($st),
                $this->csvEscape($title),
            ]) . "\n";
        }

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="applicants.csv"',
        ]);
    }

    private function csvEscape(string $value): string
    {
        $v = str_replace('"', '""', $value);
        return '"' . $v . '"';
    }
}