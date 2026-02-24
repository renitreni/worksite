<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@worksite.com'],
            [
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'name'       => 'Super Admin',
                'password'   => Hash::make('ChangeMe123!'),
                'role'       => 'superadmin',
                'account_status'  => 'active',
            ],

            ['email' => 'superadmin@example.com'], // change this
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'name' => 'Super Admin', // if you use name
                'phone' => '0000000000', // optional
                'password' => Hash::make('ChangeMe123!'), // change this
                'role' => 'superadmin',
                'account_status' => 'active',
                'archived_at' => null,
                'is_active' => true,

                // If you want the seeded account to be "verified":
                'email_verified_at' => now(),
            ]
        );
    }
}
