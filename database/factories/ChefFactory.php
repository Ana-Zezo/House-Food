<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chef>
 */
class ChefFactory extends Factory
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
            'phone' => $this->faker->unique()->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), 
            'otp' => rand(100000, 999999),
            'is_verify' => $this->faker->boolean(),
            'image' => null,
            'wallet' => $this->faker->randomFloat(2, 0, 1000),
            'countSubscribe' => $this->faker->numberBetween(0, 500),
            'channel_name' => $this->faker->userName(),
            'bio' => $this->faker->paragraph(),
            'totalOrder' => $this->faker->numberBetween(0, 100),
        ];
    }
}
