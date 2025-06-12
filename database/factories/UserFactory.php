<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), 
            'otp' => rand(1000, 9999),
            'phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement(['active', 'block']),
            'is_verify' => $this->faker->boolean(),
            'image' => null,
            'wallet' => rand(100, 1000),
            'fcm_token' => Str::random(10),
            'remember_token' => Str::random(10),
        ];
    }
}
