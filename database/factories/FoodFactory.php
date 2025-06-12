<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $foodTypes = ['full', 'half'];
        $statuses = ['active', 'inactive'];

        return [
            'category_id' => $this->faker->numberBetween(1, 10),
            'chef_id' => $this->faker->numberBetween(1, 10),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'offer_price' => $this->faker->randomFloat(2, 5, 90),
            'preparation_time' => $this->faker->numberBetween(5, 60),
            'rating' => $this->faker->randomFloat(1, 0, 5),
            'food_type' => $this->faker->randomElement($foodTypes),
            'image' => null,
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}
