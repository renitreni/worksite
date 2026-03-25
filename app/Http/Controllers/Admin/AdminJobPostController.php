<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployerProfile;
use App\Models\JobPost;
use App\Services\Employer\EmployerJobService;

class AdminJobPostController extends Controller
{
    public function __construct(
        private EmployerJobService $jobService
    ) {
    }
    public function index(Request $request)
    {
        $jobs = JobPost::with(['employerProfile.user'])
            ->whereHas('employerProfile', function ($q) {
                $q->where('allow_admin_management', true);
            })

            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('employerProfile', function ($q2) use ($request) {
                    $q2->where('company_name', 'like', '%' . $request->search . '%');
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('adminpage.contents.employer.jobs.index', compact('jobs'));
    }
    public function search(Request $request)
    {
        $jobs = JobPost::with(['employerProfile.user'])
            ->whereHas('employerProfile', function ($q) {
                $q->where('allow_admin_management', true);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('employerProfile', function ($q2) use ($request) {
                    $q2->where('company_name', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->take(10)
            ->get();

        return response()->json($jobs);
    }
    public function create()
    {
        $employers = EmployerProfile::where('allow_admin_management', true)->get();

        $data = $this->jobService->getAdminCreatePageData();

        return view('adminpage.contents.employer.post-job', array_merge($data, [
            'employers' => $employers
        ]));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employer_profile_id' => 'required|exists:employer_profiles,id',
        ]);

        $this->jobService->adminStoreJob($request);

        $employer = EmployerProfile::find($request->employer_profile_id);

        return back()->with(
            'success',
            'Job posted successfully for ' . $employer->company_name . '.'
        );
    }
    public function edit(JobPost $job)
    {
        if (!$job->employerProfile || !$job->employerProfile->allow_admin_management) {
            abort(403, 'This employer did not allow admin management.');
        }

        $employers = EmployerProfile::where('allow_admin_management', true)->get();

        $data = $this->jobService->getAdminCreatePageData();

        return view('adminpage.contents.employer.jobs.edit', array_merge($data, [
            'job' => $job,
            'employers' => $employers
        ]));
    }

    public function update(Request $request, JobPost $job)
    {
        if (!$job->employerProfile || !$job->employerProfile->allow_admin_management) {
            abort(403, 'This employer did not allow admin management.');
        }

        $this->jobService->adminUpdateJob($request, $job);

        return redirect()
            ->route('admin.admin-job-posts.index')
            ->with('success', 'Job updated successfully.');
    }
    public function show(JobPost $job)
    {
        $job->load('employerProfile.user');

        return view('adminpage.contents.employer.jobs.show', compact('job'));
    }

    public function close(JobPost $job)
    {
        $job->update([
            'status' => 'closed'
        ]);

        return back()->with('success', 'Job closed successfully.');
    }

    public function reopen(JobPost $job)
    {
        $job->update([
            'status' => 'open'
        ]);

        return back()->with('success', 'Job reopened successfully.');
    }
}