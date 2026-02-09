<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@worksite.com'],
            [
                'first_name' => 'System',
                'last_name'  => 'Admin',
                'name'       => 'System Admin',
                'phone'      => null,
                'password'   => bcrypt('Admin12345'),
                'role'       => 'admin',
            ]
        );
    }
}
