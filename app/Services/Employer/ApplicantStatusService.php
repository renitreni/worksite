<?php

namespace App\Services\Employer;

use App\Models\JobApplication;
use App\Notifications\ApplicationStatusUpdated;
use App\Events\ApplicationStatusChanged;

class ApplicantStatusService
{
    private function updateStatus(JobApplication $application,string $status)
    {
        $application->update(['status'=>$status]);

        $candidateUser = $application->candidateProfile?->user;

        if ($candidateUser) {
            $candidateUser->notify(
                new ApplicationStatusUpdated($application)
            );
        }

        event(new ApplicationStatusChanged($application));
    }

    public function shortlist(JobApplication $application)
    {
        $current = strtolower(trim($application->status ?? ''));

        if (in_array($current,['applied','new','pending'])) {

            $this->updateStatus($application,'shortlisted');

            return back()->with('success','Applicant shortlisted.');
        }

        return back()->with('error','Cannot shortlist.');
    }

    public function interview(JobApplication $application)
    {
        if ($application->status === 'shortlisted') {

            $this->updateStatus($application,'interview');

            return back()->with('success','Moved to Interview.');
        }

        return back()->with('error','Cannot move to interview.');
    }

    public function hire(JobApplication $application)
    {
        if ($application->status === 'interview') {

            $this->updateStatus($application,'hired');

            return back()->with('success','Applicant Hired!');
        }

        return back()->with('error','Cannot hire.');
    }

    public function reject(JobApplication $application)
    {
        if (!in_array($application->status,['rejected','hired'])) {

            $this->updateStatus($application,'rejected');

            return back()->with('error','Applicant rejected.');
        }

        return back()->with('error','Cannot reject.');
    }
}