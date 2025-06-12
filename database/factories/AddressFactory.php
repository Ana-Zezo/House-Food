<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'city' => $this->faker->city,
            'center' => $this->faker->citySuffix,
            'neighborhood' => $this->faker->streetName,
            'street' => $this->faker->streetAddress,
            'building_number' => $this->faker->buildingNumber,
        ];
    }
}
