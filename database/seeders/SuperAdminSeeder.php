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
                'is_active'  => true,
            ]
        );
    }
}
