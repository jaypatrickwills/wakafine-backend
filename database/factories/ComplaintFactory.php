<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->passenger(),
            'booking_id' => null,
            'subject' => fake()->randomElement([
                'Rider was late',
                'Fare dispute',
                'Unsafe riding',
                'Rude behaviour',
                'Lost item',
            ]),
            'message' => fake()->paragraph(),
            'status' => Complaint::STATUS_OPEN,
        ];
    }
}
