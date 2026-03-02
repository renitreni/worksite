<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 2 Employers
        foreach (range(1, 2) as $i) {
            User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'name' => fake()->name(),
                'email' => "employer{$i}@example.com",
                'phone' => fake()->phoneNumber(),
                'password' => 'password',
                'role' => 'employer',
                'account_status' => 'active',
                // 'is_active' => true,
            ]);
        }

        // 3 Candidates
        foreach (range(1, 3) as $i) {
            User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'name' => fake()->name(),
                'email' => "candidate{$i}@example.com",
                'phone' => fake()->phoneNumber(),
                'password' => 'password',
                'role' => 'candidate',
                'account_status' => 'active',
                // 'is_active' => true,
            ]);
        }
    }
}