<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Complaint;
use App\Models\RiderProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Admin (web dashboard login) ──────────────────────────────
        User::updateOrCreate(
            ['phone' => '237600000001'],
            [
                'name' => 'Wakafine Admin',
                'email' => 'admin@wakafine.cm',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'status' => User::STATUS_ACTIVE,
            ]
        );

        // ── Demo passengers (mobile app login) ───────────────────────
        $passengers = collect([
            ['name' => 'Alice Ngwa', 'phone' => '237620000001'],
            ['name' => 'Bob Tabi', 'phone' => '237620000002'],
            ['name' => 'Carla Fru', 'phone' => '237620000003'],
        ])->map(fn ($p) => User::updateOrCreate(
            ['phone' => $p['phone']],
            [
                'name' => $p['name'],
                'email' => null,
                'password' => Hash::make('password'),
                'role' => User::ROLE_PASSENGER,
                'status' => User::STATUS_ACTIVE,
            ]
        ));

        // ── Demo riders (mobile app login) ───────────────────────────
        $james = User::updateOrCreate(
            ['phone' => '237670000001'],
            [
                'name' => 'James Moto',
                'email' => null,
                'password' => Hash::make('password'),
                'role' => User::ROLE_RIDER,
                'status' => User::STATUS_ACTIVE,
            ]
        );
        RiderProfile::updateOrCreate(
            ['user_id' => $james->id],
            [
                'bike_type' => 'motorcycle',
                'bike_plate' => 'SW-0421-AB',
                'bike_color' => 'Red',
                'verification_status' => RiderProfile::VERIFICATION_APPROVED,
                'is_online' => true,
                'current_lat' => 5.9526,
                'current_lng' => 10.1424,
            ]
        );

        $mary = User::updateOrCreate(
            ['phone' => '237670000002'],
            [
                'name' => 'Mary Bike',
                'email' => null,
                'password' => Hash::make('password'),
                'role' => User::ROLE_RIDER,
                'status' => User::STATUS_ACTIVE,
            ]
        );
        RiderProfile::updateOrCreate(
            ['user_id' => $mary->id],
            [
                'bike_type' => 'bicycle',
                'bike_plate' => 'BK-1187-CD',
                'bike_color' => 'Blue',
                'verification_status' => RiderProfile::VERIFICATION_PENDING,
                'is_online' => false,
            ]
        );

        $peter = User::updateOrCreate(
            ['phone' => '237670000003'],
            [
                'name' => 'Peter Okon',
                'email' => null,
                'password' => Hash::make('password'),
                'role' => User::ROLE_RIDER,
                'status' => User::STATUS_ACTIVE,
            ]
        );
        RiderProfile::updateOrCreate(
            ['user_id' => $peter->id],
            [
                'bike_type' => 'tricycle',
                'bike_plate' => 'TR-0099-EF',
                'bike_color' => 'Green',
                'verification_status' => RiderProfile::VERIFICATION_REJECTED,
                'verification_notes' => 'License document expired. Please resubmit.',
                'is_online' => false,
            ]
        );

        // ── Sample completed bookings for demo data ──────────────────
        if (Booking::count() === 0) {
            $spots = [
                ['Commercial Avenue, Bamenda', 'Mile 4 Nkwen, Bamenda'],
                ['Upkum, Bamenda', 'Mankon, Bamenda'],
                ['Old Town, Bamenda', 'Molyko, Bamenda'],
                ['Belo, Bamenda', 'Santa, Bamenda'],
                ['Bafut Junction, Bamenda', 'Commercial Avenue, Bamenda'],
            ];

            foreach ($spots as $i => [$from, $to]) {
                Booking::create([
                    'passenger_id' => $passengers[$i % $passengers->count()]->id,
                    'rider_id' => $james->id,
                    'pickup_address' => $from,
                    'pickup_lat' => 5.95 + ($i * 0.01),
                    'pickup_lng' => 10.14 + ($i * 0.01),
                    'destination_address' => $to,
                    'destination_lat' => 5.95 + ($i * 0.015),
                    'destination_lng' => 10.14 + ($i * 0.015),
                    'fare' => 600 + ($i * 250),
                    'status' => Booking::STATUS_COMPLETED,
                    'requested_at' => now()->subDays($i + 1)->setTime(8, 0),
                    'accepted_at' => now()->subDays($i + 1)->setTime(8, 2),
                    'arrived_at' => now()->subDays($i + 1)->setTime(8, 10),
                    'started_at' => now()->subDays($i + 1)->setTime(8, 14),
                    'completed_at' => now()->subDays($i + 1)->setTime(8, 45),
                    'passenger_rating' => 4 + ($i % 2),
                    'rider_rating' => 5,
                ]);
            }

            Complaint::create([
                'user_id' => $passengers[0]->id,
                'booking_id' => Booking::latest()->first()?->id,
                'subject' => 'Rider was late',
                'message' => 'The rider took too long to arrive at the pickup point during rush hour.',
                'status' => Complaint::STATUS_OPEN,
            ]);
        }
    }
}
