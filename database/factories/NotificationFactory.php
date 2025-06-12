<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'notifiable_id' => $this->faker->randomNumber(),
            'notifiable_type' => $this->faker->randomElement(['App\Models\User', 'App\Models\Admin']),
            'title' => $this->faker->sentence(5),
            'description' => $this->faker->paragraph(),
            'is_read' => $this->faker->boolean(30),
        ];
    }
}
