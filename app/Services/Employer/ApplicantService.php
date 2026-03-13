<?php

namespace App\Services\Employer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CandidateProfileView;
use App\Models\JobApplication;
use App\Services\Employer\EmployerAccessService;

class ApplicantService
{
    public function __construct(
        private EmployerAccessService $accessService
    ) {}

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

        return view(
            'employer.contents.applicants.index',
            compact('status','access','viewedTodayIds')
        );
    }

    public function show(JobApplication $application)
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        $application->load(['jobPost','candidateProfile']);

        $candidateProfileId = (int) ($application->candidateProfile?->id);
        abort_if(!$candidateProfileId,404,'Candidate profile not found.');

        $dailyLimit = $this->accessService->dailyCandidateProfileViewLimit($profile);
        $usedToday = $this->accessService->usedApplicationViewsToday($profile);

        $canViewToday = $this->accessService
            ->canViewApplicationToday($profile,(int)$application->id);

        if (!$canViewToday) {

            return redirect()
                ->route('employer.applicants.index')
                ->with('candidate_view_limit_modal',true)
                ->with('candidate_view_limit_data',[
                    'limit'=>$dailyLimit,
                    'usedToday'=>$usedToday
                ]);
        }

        $this->accessService->recordApplicationView(
            $profile,
            (int)$application->id,
            $candidateProfileId
        );

        $usedToday = $this->accessService->usedApplicationViewsToday($profile);

        $application->loadMissing(['candidateProfile.user']);

        if (
            $access['can_view_work_history'] ||
            $access['can_view_education_history'] ||
            $access['can_preview_cv'] ||
            $access['can_download_documents']
        ) {

            $application->loadMissing([
                'candidateProfile.resume.experiences',
                'candidateProfile.resume.educations',
                'candidateProfile.resume.attachments',
            ]);
        }

        return view(
            'employer.contents.applicants.show',
            compact(
                'application',
                'access',
                'canViewToday',
                'dailyLimit',
                'usedToday'
            )
        );
    }
}