<?php

namespace Database\Factories;

use App\Models\Chef;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Withdraw>
 */
class WithdrawFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chef_id' => Chef::inRandomOrder()->first()->id ?? Chef::factory(),
            'amount' => $this->faker->numberBetween(100, 1000),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),

        ];
    }
}
