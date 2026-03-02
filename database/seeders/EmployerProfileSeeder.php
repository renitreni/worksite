<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\EmployerProfile;
use App\Models\EmployerVerification;

class EmployerProfileSeeder extends Seeder
{
    public function run(): void
    {
        $employers = User::where('role', 'employer')->get();

        foreach ($employers as $user) {

            $profile = EmployerProfile::create([
                'user_id' => $user->id,
                'company_name' => fake()->company(),
                'company_address' => fake()->address(),
                'company_contact' => fake()->phoneNumber(),
                'company_website' => fake()->url(),
                'description' => fake()->paragraph(),
                'logo_path' => null,
                'cover_path' => null,
                'total_profile_views' => fake()->numberBetween(0, 1000),
                'representative_name' => $user->name,
                'position' => fake()->jobTitle(),
            ]);

            // 🔥 Create Verification Record
            $status = fake()->randomElement(['pending', 'approved', 'rejected', 'suspended']);

            EmployerVerification::create([
                'employer_profile_id' => $profile->id,
                'status' => $status,
                'approved_at' => $status === 'approved' ? now() : null,
                'rejected_at' => $status === 'rejected' ? now() : null,
                'rejection_reason' => $status === 'rejected' ? fake()->sentence() : null,
                'suspended_reason' => $status === 'suspended' ? fake()->sentence() : null,
            ]);
        }
    }
}