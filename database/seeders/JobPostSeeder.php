<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPost;
use App\Models\EmployerProfile;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class JobPostSeeder extends Seeder
{
    public function run(): void
    {
        $employers = EmployerProfile::all();

        if ($employers->isEmpty()) {
            $this->command->warn('No EmployerProfiles found. Seed employers first.');
            return;
        }

        foreach (range(1, 20) as $i) {

            $employer = $employers->random();

            $isHeld = fake()->boolean(10);        // 10% chance
            $isDisabled = fake()->boolean(5);     // 5% chance
            $status = fake()->randomElement(['open', 'closed']);

            JobPost::create([
                'employer_profile_id' => $employer->id,

                'title' => fake()->jobTitle(),
                'industry' => fake()->randomElement(['IT', 'Healthcare', 'Construction', 'Engineering']),
                'skills' => implode(', ', fake()->words(5)),

                'country' => 'Philippines',
                'city' => fake()->city(),
                'area' => fake()->streetName(),

                'min_experience_years' => fake()->numberBetween(0, 10),
                'education_level' => fake()->randomElement(['High School', 'Bachelor', 'Master']),

                'salary_min' => fake()->numberBetween(20000, 50000),
                'salary_max' => fake()->numberBetween(60000, 120000),
                'salary_currency' => 'PHP',

                'gender' => fake()->randomElement(['male', 'female', 'both']),
                'age_min' => fake()->numberBetween(18, 25),
                'age_max' => fake()->numberBetween(30, 50),

                'posted_at' => Carbon::now()->subDays(fake()->numberBetween(0, 30)),
                'apply_until' => Carbon::now()->addDays(fake()->numberBetween(7, 30)),

                'job_description' => fake()->paragraphs(3, true),
                'job_qualifications' => fake()->paragraphs(2, true),
                'additional_information' => fake()->sentence(),

                'principal_employer' => fake()->company(),
                'dmw_registration_no' => strtoupper(Str::random(10)),
                'principal_employer_address' => fake()->address(),

                'placement_fee' => fake()->numberBetween(0, 50000),
                'placement_fee_currency' => 'PHP',

                'status' => $status,

                'is_held' => $isHeld,
                'held_at' => $isHeld ? now() : null,
                'hold_reason' => $isHeld ? fake()->sentence() : null,
                'held_by_user_id' => $isHeld ? User::inRandomOrder()->value('id') : null,

                'is_disabled' => $isDisabled,
                'disabled_at' => $isDisabled ? now() : null,
                'disabled_reason' => $isDisabled ? fake()->sentence() : null,
                'disabled_by_user_id' => $isDisabled ? User::inRandomOrder()->value('id') : null,

                'admin_notes' => fake()->sentence(),
                'notes_updated_at' => now(),
            ]);
        }
    }
}