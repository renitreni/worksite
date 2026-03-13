<?php

namespace App\Services\Admin;

use App\Models\JobPost;
use Illuminate\Http\Request;
use App\Models\JobPostLog;
use Illuminate\Support\Facades\Mail;
use App\Notifications\JobPostStatusUpdated;
use App\Mail\JobPostHeldMail;
use App\Mail\JobPostDisabledMail;

class AdminJobPostService
{
    public function getJobPosts(Request $request): array
    {
        $q = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));
        $held = trim((string) $request->query('held', ''));
        $disabled = trim((string) $request->query('disabled', ''));

        $jobPosts = JobPost::query()
            ->with('employerProfile')
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('title', 'like', "%{$q}%")
                    ->orWhere('industry', 'like', "%{$q}%")
                    ->orWhere('country', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('area', 'like', "%{$q}%");
            })
            ->when(in_array($status, ['open','closed'], true),
                fn($qr) => $qr->where('status', $status))
            ->when($held === '1', fn($qr) => $qr->where('is_held', true))
            ->when($held === '0', fn($qr) => $qr->where('is_held', false))
            ->when($disabled === '1', fn($qr) => $qr->where('is_disabled', true))
            ->when($disabled === '0', fn($qr) => $qr->where('is_disabled', false))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return compact('jobPosts','q','status','held','disabled');
    }

    public function holdJobPost(Request $request, JobPost $jobPost): void
    {
        $data = $request->validate([
            'hold_reason' => ['nullable','string','max:2000']
        ]);

        $jobPost->update([
            'is_held' => true,
            'held_at' => now(),
            'hold_reason' => $data['hold_reason'],
            'held_by_user_id' => $request->user()?->id
        ]);

        $this->notifyEmployer($jobPost,'hold',$data['hold_reason']);

        Mail::to($jobPost->employerProfile->user->email)
            ->send(new JobPostHeldMail(
                $jobPost->employerProfile->company_name,
                $jobPost->title,
                $data['hold_reason']
            ));

        $this->log($jobPost,'held',$data['hold_reason']);
    }

    public function unholdJobPost(Request $request, JobPost $jobPost): void
    {
        $jobPost->update([
            'is_held' => false,
            'held_at' => null,
            'hold_reason' => null,
            'held_by_user_id' => $request->user()?->id
        ]);

        $this->notifyEmployer($jobPost,'unhold');

        $this->log($jobPost,'unheld');
    }

    public function disableJobPost(Request $request, JobPost $jobPost): void
    {
        $data = $request->validate([
            'disabled_reason' => ['required','string','max:3000']
        ]);

        $jobPost->update([
            'is_disabled' => true,
            'disabled_at' => now(),
            'disabled_reason' => $data['disabled_reason'],
            'disabled_by_user_id' => $request->user()?->id
        ]);

        $this->notifyEmployer($jobPost,'disable',$data['disabled_reason']);

        Mail::to($jobPost->employerProfile->user->email)
            ->send(new JobPostDisabledMail(
                $jobPost->employerProfile->company_name,
                $jobPost->title,
                $data['disabled_reason']
            ));

        $this->log($jobPost,'disabled',$data['disabled_reason']);
    }

    public function enableJobPost(Request $request, JobPost $jobPost): void
    {
        $jobPost->update([
            'is_disabled' => false,
            'disabled_at' => null,
            'disabled_reason' => null,
            'disabled_by_user_id' => $request->user()?->id
        ]);

        $this->notifyEmployer($jobPost,'enable');

        $this->log($jobPost,'enabled');
    }

    public function updateNotes(Request $request, JobPost $jobPost): void
    {
        $data = $request->validate([
            'admin_notes' => ['nullable','string','max:5000']
        ]);

        $jobPost->update([
            'admin_notes' => $data['admin_notes'],
            'notes_updated_at' => now()
        ]);

        $this->log($jobPost,'updated_notes','Admin notes updated');
    }

    private function notifyEmployer(JobPost $jobPost, string $action, ?string $reason=null): void
    {
        $jobPost->employerProfile?->user?->notify(
            new JobPostStatusUpdated($jobPost,$action,$reason)
        );
    }

    private function log(JobPost $jobPost,string $action,?string $description=null): void
    {
        JobPostLog::create([
            'job_post_id' => $jobPost->id,
            'admin_id' => auth()->id(),
            'action' => $action,
            'description' => $description
        ]);
    }
}