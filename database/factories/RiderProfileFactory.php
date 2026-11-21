<?php

namespace Database\Factories;

use App\Models\RiderProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RiderProfile>
 */
class RiderProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->rider(),
            'bike_type' => fake()->randomElement(['motorcycle', 'bicycle', 'tricycle']),
            'bike_plate' => fake()->bothify('??-####-??'),
            'bike_color' => fake()->colorName(),
            'verification_status' => RiderProfile::VERIFICATION_PENDING,
            'is_online' => false,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => RiderProfile::VERIFICATION_APPROVED,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => RiderProfile::VERIFICATION_REJECTED,
            'verification_notes' => 'Documents could not be verified.',
        ]);
    }

    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_online' => true,
            'current_lat' => 5.95 + fake()->randomFloat(4, -0.05, 0.05),
            'current_lng' => 10.14 + fake()->randomFloat(4, -0.05, 0.05),
        ]);
    }
}
