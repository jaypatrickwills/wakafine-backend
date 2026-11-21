<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    private const BAMENDA_SPOTS = [
        'Commercial Avenue, Bamenda',
        'Mile 4 Nkwen, Bamenda',
        'Upkum, Bamenda',
        'Mankon, Bamenda',
        'Old Town, Bamenda',
        'Molyko, Bamenda',
        'Belo, Bamenda',
        'Santa, Bamenda',
        'Bafut Junction, Bamenda',
        'Bamenda Airport Road',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pickup = fake()->randomElement(self::BAMENDA_SPOTS);
        $destination = fake()->randomElement(array_diff(self::BAMENDA_SPOTS, [$pickup]));

        return [
            'passenger_id' => User::factory()->passenger(),
            'rider_id' => null,
            'pickup_address' => $pickup,
            'pickup_lat' => 5.95 + fake()->randomFloat(4, -0.05, 0.05),
            'pickup_lng' => 10.14 + fake()->randomFloat(4, -0.05, 0.05),
            'destination_address' => $destination,
            'destination_lat' => 5.95 + fake()->randomFloat(4, -0.05, 0.05),
            'destination_lng' => 10.14 + fake()->randomFloat(4, -0.05, 0.05),
            'fare' => fake()->numberBetween(400, 2500),
            'status' => Booking::STATUS_COMPLETED,
            'requested_at' => now()->subHours(fake()->numberBetween(1, 72)),
            'accepted_at' => now()->subHours(fake()->numberBetween(1, 72))->addMinutes(2),
            'arrived_at' => now()->subHours(fake()->numberBetween(1, 72))->addMinutes(8),
            'started_at' => now()->subHours(fake()->numberBetween(1, 72))->addMinutes(12),
            'completed_at' => now()->subHours(fake()->numberBetween(0, 70)),
        ];
    }

    public function searching(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Booking::STATUS_SEARCHING,
            'accepted_at' => null,
            'arrived_at' => null,
            'started_at' => null,
            'completed_at' => null,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Booking::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'completed_at' => null,
        ]);
    }
}
