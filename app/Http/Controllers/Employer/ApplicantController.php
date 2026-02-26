<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Services\EmployerAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\CandidateProfileView;

class ApplicantController extends Controller
{
    public function __construct(
        private EmployerAccessService $accessService
    ) {
    }

    // ----------------------------
    // Pages
    // ----------------------------

    public function index(Request $request)
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        $today = now()->toDateString();

        $viewedTodayIds = CandidateProfileView::query()
            ->where('employer_profile_id', $profile->id)
            ->where('view_date', $today)
            ->pluck('job_application_id')
            ->toArray();

        $status = $request->query('status', 'all');

        $applications = JobApplication::query()
            ->with([
                'jobPost:id,title',
                'candidateProfile.user:id,name,email',
            ])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->get();

        return view('employer.contents.applicants.index', compact('applications', 'status', 'access', 'viewedTodayIds'));
    }

    public function show(JobApplication $application)
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        $application->load(['jobPost', 'candidateProfile']);

        $candidateProfileId = (int) ($application->candidateProfile?->id);
        abort_if(!$candidateProfileId, 404, 'Candidate profile not found.');

        $dailyLimit = $this->accessService->dailyCandidateProfileViewLimit($profile);
        $usedToday = $this->accessService->usedApplicationViewsToday($profile);

        $canViewToday = $this->accessService->canViewApplicationToday($profile, (int) $application->id);
        if (!$canViewToday) {
            return redirect()
                ->route('employer.applicants.index')
                ->with('candidate_view_limit_modal', true)
                ->with('candidate_view_limit_data', [
                    'limit' => $dailyLimit,
                    'usedToday' => $usedToday,
                ]);
        }

        // allowed => record view (counts once per job application per day)
        $this->accessService->recordApplicationView($profile, (int) $application->id, $candidateProfileId);

        // refresh usedToday for UI
        $usedToday = $this->accessService->usedApplicationViewsToday($profile);

        // now load user + resume gated by access
        $application->loadMissing(['candidateProfile.user']);

        if (
            $access['can_view_work_history']
            || $access['can_view_education_history']
            || $access['can_preview_cv']
            || $access['can_download_documents']
        ) {
            $application->loadMissing([
                'candidateProfile.resume.experiences',
                'candidateProfile.resume.educations',
                'candidateProfile.resume.attachments',
            ]);
        }

        return view('employer.contents.applicants.show', compact(
            'application',
            'access',
            'canViewToday',
            'dailyLimit',
            'usedToday'
        ));
    }

    // ----------------------------
    // Status actions
    // ----------------------------

    public function shortlist(JobApplication $application)
    {
        $current = strtolower(trim($application->status ?? ''));

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

    // ----------------------------
    // Export (CSV)
    // ----------------------------

    public function export(Request $request)
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        $status = $request->query('status', 'all');

        $applications = JobApplication::query()
            ->with(['jobPost:id,title', 'candidateProfile.user:id,name,email'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->get();

        $csvData = "Name," . ($access['can_view_full_contact_info'] ? "Email," : "") . "Status,Applied Position\n";

        foreach ($applications as $app) {
            $name = $app->candidateProfile?->user?->name ?? $app->full_name ?? '';
            $email = $app->candidateProfile?->user?->email ?? $app->email ?? '';
            $st = ucfirst(strtolower(trim($app->status ?? 'applied')));
            $title = $app->jobPost?->title ?? '';

            $row = [$this->csvEscape($name)];

            if ($access['can_view_full_contact_info']) {
                $row[] = $this->csvEscape($email);
            }

            $row[] = $this->csvEscape($st);
            $row[] = $this->csvEscape($title);

            $csvData .= implode(',', $row) . "\n";
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