<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $first = fake()->firstName();
        $last  = fake()->lastName();

        return [
            'first_name' => $first,
            'last_name'  => $last,
            'name'       => "$first $last",
            'email'      => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone'      => null,
            'password'   => Hash::make('password'),
            'role'       => 'candidate', // or fake()->randomElement([...])
            'remember_token' => Str::random(10),
        ];
    }
}
