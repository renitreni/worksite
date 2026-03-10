<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;
use App\Notifications\JobPostStatusUpdated;
use App\Mail\JobPostHeldMail;
use App\Mail\JobPostDisabledMail;
use Illuminate\Support\Facades\Mail;
use App\Models\JobPostLog;



class JobPostAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', '')); // open|closed
        $held = trim((string) $request->query('held', ''));     // 1|0
        $disabled = trim((string) $request->query('disabled', '')); // 1|0

        $jobPosts = JobPost::query()
            ->with(['employerProfile']) // comment out if relation not ready
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('title', 'like', "%{$q}%")
                    ->orWhere('industry', 'like', "%{$q}%")
                    ->orWhere('country', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('area', 'like', "%{$q}%");
            })
            ->when(in_array($status, ['open', 'closed'], true), fn($qr) => $qr->where('status', $status))
            ->when($held === '1', fn($qr) => $qr->where('is_held', true))
            ->when($held === '0', fn($qr) => $qr->where('is_held', false))
            ->when($disabled === '1', fn($qr) => $qr->where('is_disabled', true))
            ->when($disabled === '0', fn($qr) => $qr->where('is_disabled', false))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('adminpage.contents.job_posts.index', compact('jobPosts', 'q', 'status', 'held', 'disabled'));
    }

    public function show(JobPost $jobPost)
    {
        $jobPost->load(['employerProfile']); // comment out if relation not ready
        return view('adminpage.contents.job_posts.show', compact('jobPost'));
    }

    public function hold(Request $request, JobPost $jobPost)
    {
        $data = $request->validate([
            'hold_reason' => ['nullable', 'string', 'max:2000'],
        ]);

        $jobPost->update([
            'is_held' => true,
            'held_at' => now(),
            'hold_reason' => $data['hold_reason'],
            'held_by_user_id' => $request->user()?->id,
        ]);

        // 🔔 Notify Employer (database notification)
        $jobPost->employerProfile?->user?->notify(
            new JobPostStatusUpdated(
                $jobPost,
                'hold',
                $data['hold_reason']
            )
        );

        // 📧 Send Email
        $company = $jobPost->employerProfile->company_name;
        $user = $jobPost->employerProfile->user;

        Mail::to($user->email)->send(
            new JobPostHeldMail(
                $company,
                $jobPost->title,
                $data['hold_reason']
            )
        );


        JobPostLog::create([
            'job_post_id' => $jobPost->id,
            'admin_id' => auth()->id(),
            'action' => 'held',
            'description' => request('hold_reason')
        ]);

        return back()->with('success', 'Job post has been held.');
    }

    public function unhold(Request $request, JobPost $jobPost)
    {
        $jobPost->update([
            'is_held' => false,
            'held_at' => null,
            'hold_reason' => null,
            'held_by_user_id' => $request->user()?->id,
        ]);

        $jobPost->employerProfile?->user?->notify(
            new JobPostStatusUpdated($jobPost, 'unhold')
        );

        JobPostLog::create([
            'job_post_id' => $jobPost->id,
            'admin_id' => auth()->id(),
            'action' => 'unheld',
            'description' => request('hold_reason')
        ]);

        return back()->with('success', 'Job post has been released (unheld).');
    }

    public function disable(Request $request, JobPost $jobPost)
    {
        $data = $request->validate([
            'disabled_reason' => ['required', 'string', 'max:3000'],
        ]);

        $jobPost->update([
            'is_disabled' => true,
            'disabled_at' => now(),
            'disabled_reason' => $data['disabled_reason'],
            'disabled_by_user_id' => $request->user()?->id,
        ]);

        $jobPost->employerProfile?->user?->notify(
            new JobPostStatusUpdated(
                $jobPost,
                'disable',
                $data['disabled_reason']
            )
        );

        $company = $jobPost->employerProfile->company_name;
        $user = $jobPost->employerProfile->user;

        Mail::to($user->email)->send(
            new JobPostDisabledMail(
                $company,
                $jobPost->title,
                $data['disabled_reason']
            )
        );

        JobPostLog::create([
            'job_post_id' => $jobPost->id,
            'admin_id' => auth()->id(),
            'action' => 'disabled',
            'description' => request('disabled_reason')
        ]);

        return back()->with('success', 'Job post has been disabled.');
    }
    public function enable(Request $request, JobPost $jobPost)
    {
        $jobPost->update([
            'is_disabled' => false,
            'disabled_at' => null,
            'disabled_reason' => null,
            'disabled_by_user_id' => $request->user()?->id,
        ]);

        $jobPost->employerProfile?->user?->notify(
            new JobPostStatusUpdated($jobPost, 'enable')
        );

        JobPostLog::create([
            'job_post_id' => $jobPost->id,
            'admin_id' => auth()->id(),
            'action' => 'enabled',
            'description' => request('disabled_reason')
        ]);

        return back()->with('success', 'Job post has been enabled.');
    }

    public function updateNotes(Request $request, JobPost $jobPost)
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $jobPost->update([
            'admin_notes' => $request->input('admin_notes'),
            'notes_updated_at' => now(),
        ]);

        JobPostLog::create([
            'job_post_id' => $jobPost->id,
            'admin_id' => auth()->id(),
            'action' => 'updated_notes',
            'description' => 'Admin notes updated'
        ]);

        return back()->with('success', 'Admin notes updated.');
    }
}