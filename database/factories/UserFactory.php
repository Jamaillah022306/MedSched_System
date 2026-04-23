<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'fullname'      => fake()->name(),
            'email'         => fake()->unique()->safeEmail(),
            'password_hash' => static::$password ??= Hash::make('password'),
        ];
    }
}