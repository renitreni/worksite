<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $first = fake()->firstName();
        $last  = fake()->lastName();

        return [
            'first_name' => $first,
            'last_name'  => $last,
            'name'       => $first . ' ' . $last,
            'email'      => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone'      => null,               // if column exists
            'role'       => 'candidate',         // set a sensible default if required
            'password'   => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
