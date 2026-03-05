<?php

namespace App\Livewire\Employer\Applicants;

use Livewire\Component;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Services\EmployerAccessService;
use Carbon\Carbon;

class ApplicantsTable extends Component
{
    public $status = 'all';

    public $jobPost = '';
    public $experience = '';
    public $education = '';
    public $location = '';
    public $age = '';

    protected EmployerAccessService $accessService;

    public function boot(EmployerAccessService $accessService)
    {
        $this->accessService = $accessService;
    }

    public function mount($status = 'all')
    {
        $this->status = $status;
    }

    public function resetFilters()
    {
        $this->reset([
            'jobPost',
            'experience',
            'education',
            'location',
            'age'
        ]);
    }

    public function statusClasses($status)
    {
        return match ($status) {
            'applied', 'new', 'pending' => 'bg-emerald-100 text-emerald-800',
            'shortlisted' => 'bg-sky-100 text-sky-800',
            'interview' => 'bg-amber-100 text-amber-800',
            'hired' => 'bg-violet-100 text-violet-800',
            'rejected' => 'bg-rose-100 text-rose-800',
            default => 'bg-slate-100 text-slate-700',
        };
    }

    public function render()
    {
        [$profile, $access] = $this->accessService->resolveAccessForCurrentEmployer();

        $jobs = JobPost::where('employer_profile_id', $profile->id)->get();

        $applications = JobApplication::query()
            ->with([
                'jobPost:id,title',
                'candidateProfile.user:id,name,email',
                'candidateProfile'
            ])

            ->when(
                $this->status !== 'all',
                fn($q) =>
                $q->where('status', $this->status)
            )

            ->when(
                $this->jobPost,
                fn($q) =>
                $q->where('job_post_id', $this->jobPost)
            )

            ->when(
                $this->experience,
                fn($q) =>
                $q->whereHas(
                    'candidateProfile',
                    fn($q2) =>
                    $q2->where('experience_years', '>=', $this->experience)
                )
            )

            ->when(
                $this->education,
                fn($q) =>
                $q->whereHas(
                    'candidateProfile',
                    fn($q2) =>
                    $q2->where('highest_qualification', 'like', '%' . $this->education . '%')
                )
            )

            ->when($this->location, function ($q) {

                $location = '%' . strtolower($this->location) . '%';

                $q->whereHas('candidateProfile', function ($q2) use ($location) {

                    $q2->whereRaw('LOWER(address) LIKE ?', [$location]);

                });

            })

            ->when($this->age, function ($q) {

                $date = now()->subYears($this->age)->startOfDay();

                $q->whereHas('candidateProfile', function ($q2) use ($date) {
                    $q2->whereDate('birth_date', '<=', $date);
                });

            })

            ->latest()
            ->get();

        return view('livewire.employer.applicants.applicants-table', [
            'applications' => $applications,
            'jobs' => $jobs,
            'access' => $access
        ]);
    }
}