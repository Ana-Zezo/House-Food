<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Address;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'total_price' => $this->faker->randomFloat(2, 10, 200),
            'address_id' => Address::inRandomOrder()->first()->id ?? Address::factory(),
            'paid' => fake()->randomElement(['pending', 'success', 'failed']),
        ];
    }
}
