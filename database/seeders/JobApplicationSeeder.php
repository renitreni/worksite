<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Notifications\EmployerNotification;
use Faker\Factory as Faker;

class JobApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Target employer user ID
        $employerUserId = 3;
        $employerUser = User::find($employerUserId);

        if (!$employerUser) {
            echo "Employer user with ID $employerUserId not found.\n";
            return;
        }

        // Get all candidates
        $candidates = User::where('role', 'candidate')->get();

        // Get all job posts for this employer
        $jobs = JobPost::whereHas('employerProfile', function($q) use ($employerUserId) {
            $q->where('user_id', $employerUserId);
        })->get();

        if ($jobs->isEmpty()) {
            echo "No job posts found for employer ID $employerUserId.\n";
            return;
        }

        $maxApplicationsPerCandidate = 3;

        foreach ($candidates as $candidate) {
            // Randomly pick jobs for this candidate
            $jobsToApply = $jobs->shuffle()->take($maxApplicationsPerCandidate);

            foreach ($jobsToApply as $job) {
                // Skip if already applied
                if (JobApplication::where('job_post_id', $job->id)
                    ->where('candidate_id', $candidate->id)
                    ->exists()) {
                    continue;
                }

                // Create application
                $application = JobApplication::create([
                    'job_post_id'  => $job->id,
                    'candidate_id' => $candidate->id,
                    'status'       => 'applied',
                    'full_name'    => $candidate->name,
                    'email'        => $candidate->email,
                    'phone'        => $candidate->phone ?? null,
                    'cover_letter' => $faker->paragraph,
                ]);

                // Notify the single employer
                $employerUser->notify(new EmployerNotification([
                    'title'     => 'New Application',
                    'body'      => $candidate->name . ' applied to ' . $job->title,
                    'icon'      => 'user-plus',
                    'iconWrap'  => 'bg-blue-50 border-blue-100',
                    'iconColor' => 'text-blue-600',
                    'time'      => now()->diffForHumans(),
                ]));
            }
        }

        echo "Seeding completed. Notifications sent to employer ID $employerUserId.\n";
    }
}