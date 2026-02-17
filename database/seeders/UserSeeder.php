<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
       
         User::updateOrCreate(
            ['email' => 'employer@worksite.com'],
            [
                'first_name' => 'Demo',
                'last_name'  => 'Employer',
                'name'       => 'Demo Employer',
                'password'   => Hash::make('Password123!'),
                'role'       => 'employer',
            ]
        );

        User::updateOrCreate(
            ['email' => 'candidate@worksite.com'],
            [
                'first_name' => 'Demo',
                'last_name'  => 'Candidate',
                'name'       => 'Demo Candidate',
                'password'   => Hash::make('Password123!'),
                'role'       => 'candidate',
            ]
        );
    }
    }

