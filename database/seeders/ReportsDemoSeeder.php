<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\User;
use App\Models\EmployerProfile;
use App\Models\CandidateProfile;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\Payment;
use App\Models\SubscriptionPlan;

class ReportsDemoSeeder extends Seeder
{
    public function run(): void
    {

        $today = now();

        /*
        |--------------------------------------------------------------------------
        | SUBSCRIPTION PLANS
        |--------------------------------------------------------------------------
        */

        $basic = SubscriptionPlan::firstOrCreate(
            ['code' => 'BASIC_DEMO'],
            [
                'name' => 'Basic Demo',
                'price' => 499,
                'is_active' => true,
                'sort_order' => 1
            ]
        );

        $pro = SubscriptionPlan::firstOrCreate(
            ['code' => 'PRO_DEMO'],
            [
                'name' => 'Pro Demo',
                'price' => 999,
                'is_active' => true,
                'sort_order' => 2
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | EMPLOYERS
        |--------------------------------------------------------------------------
        */

        $employers = [];

        for ($i=1; $i<=3; $i++) {

            $user = User::create([
                'first_name' => 'Employer',
                'last_name' => $i,
                'name' => "Employer $i",
                'email' => "employer$i@test.com",
                'password' => Hash::make('password'),
                'role' => 'employer',
                'account_status' => 'active'
            ]);

            $profile = EmployerProfile::create([
                'user_id' => $user->id,
                'company_name' => "Agency $i",
                'company_address' => "Manila",
                'company_contact' => "09123456789",
                'company_website' => "https://agency$i.com",
                'description' => "Demo agency",
                'representative_name' => "Manager $i",
                'position' => "HR Manager",
                'total_profile_views' => rand(10,100)
            ]);

            $employers[] = $profile;
        }

        /*
        |--------------------------------------------------------------------------
        | CANDIDATES
        |--------------------------------------------------------------------------
        */

        $candidates = [];

        for ($i=1; $i<=10; $i++) {

            $user = User::create([
                'first_name' => 'Candidate',
                'last_name' => $i,
                'name' => "Candidate $i",
                'email' => "candidate$i@test.com",
                'password' => Hash::make('password'),
                'role' => 'candidate',
                'account_status' => 'active'
            ]);

            $profile = CandidateProfile::create([
                'user_id' => $user->id,
                'status' => 'new'
            ]);

            $candidates[] = $user;
        }

        /*
        |--------------------------------------------------------------------------
        | JOB POSTS
        |--------------------------------------------------------------------------
        */

        $jobs = [];

        foreach ($employers as $employer) {

            for ($j=1; $j<=3; $j++) {

                $job = JobPost::create([

                    'employer_profile_id' => $employer->id,

                    'title' => "Factory Worker $j",
                    'industry' => 'Manufacturing',
                    'skills' => 'Packing',

                    'country' => 'Japan',
                    'city' => 'Tokyo',
                    'area' => 'Shinjuku',

                    'min_experience_years' => 1,
                    'education_level' => 'Highschool',

                    'salary_min' => 800,
                    'salary_max' => 1200,
                    'salary_currency' => 'USD',

                    'gender' => 'both',
                    'age_min' => 20,
                    'age_max' => 40,

                    'posted_at' => $today->subDays(rand(1,20)),
                    'apply_until' => $today->addDays(30),

                    'job_description' => 'Demo description',
                    'job_qualifications' => 'Demo qualifications',
                    'additional_information' => 'Demo notes',

                    'principal_employer' => 'Japan Company',
                    'principal_employer_address' => 'Tokyo',
                    'dmw_registration_no' => Str::random(6),

                    'placement_fee' => 0,
                    'placement_fee_currency' => 'PHP',

                    'status' => 'open',

                    'is_held' => false,
                    'is_disabled' => false,
                ]);

                $jobs[] = $job;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | JOB APPLICATIONS
        |--------------------------------------------------------------------------
        */

        foreach ($candidates as $candidate) {

            for ($k=0; $k<2; $k++) {

                $job = $jobs[array_rand($jobs)];

                JobApplication::create([
                    'job_post_id' => $job->id,
                    'candidate_id' => $candidate->id,
                    'full_name' => $candidate->name,
                    'email' => $candidate->email,
                    'phone' => '09123456789',
                    'cover_letter' => 'I want this job',
                    'status' => rand(1,5) == 1 ? 'hired' : 'applied'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENTS
        |--------------------------------------------------------------------------
        */

        foreach ($employers as $employer) {

            Payment::create([
                'employer_id' => $employer->user_id,
                'plan_id' => $basic->id,
                'subscription_id' => null,
                'amount' => 499,
                'method' => 'gcash',
                'status' => 'completed',
                'reference' => Str::random(10)
            ]);

            Payment::create([
                'employer_id' => $employer->user_id,
                'plan_id' => $pro->id,
                'subscription_id' => null,
                'amount' => 999,
                'method' => 'bank',
                'status' => 'pending',
                'reference' => Str::random(10)
            ]);

        }

    }
}