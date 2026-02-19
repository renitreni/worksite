<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CandidateProfile;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        // Create 5 dummy candidates for testing
        for ($i = 1; $i <= 5; $i++) {

            // 1️⃣ Create a user for the candidate
            $user = User::create([
                'first_name' => "Candidate $i",
                'last_name' => "Test",
                'name' => "Candidate $i Test", // optional if your app uses 'name'
                'email' => "candidate{$i}@example.com",
                'password' => Hash::make('password'), // simple password for testing
                'role' => 'candidate', // adjust if your app uses a different field
            ]);

            // 2️⃣ Create candidate profile linked to the user
            CandidateProfile::create([
                'user_id' => $user->id,
                'photo_path' => null,
                'address' => "{$i} Mango Street, Manila",
                'birth_date' => now()->subYears(rand(20, 35)),
                'bio' => "This is candidate $i's bio.",
                'experience_years' => rand(1, 10),
                'whatsapp' => '+63917' . rand(1000000, 9999999),
                'facebook' => "https://facebook.com/candidate$i",
                'linkedin' => "https://linkedin.com/in/candidate$i",
                'telegram' => "@candidate$i",
                'highest_qualification' => 'Bachelor\'s Degree',
                'current_salary' => rand(30000, 80000),
                'contact_number' => '0917' . rand(1000000, 9999999),
                'contact_e164' => '+63917' . rand(1000000, 9999999),
                'status' => ['new', 'shortlisted', 'rejected'][array_rand(['new', 'shortlisted', 'rejected'])],
            ]);
        }
    }
}
