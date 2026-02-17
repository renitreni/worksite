<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        
        Admin::updateOrCreate(
            ['email' => 'superadmin@worksite.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin12345!'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );
    }
    }
    

