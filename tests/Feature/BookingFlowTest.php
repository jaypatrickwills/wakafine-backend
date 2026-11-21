<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\RiderProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_passenger_can_register_login_and_book_a_ride(): void
    {
        // Verified, online rider ready to receive bookings.
        $riderUser = User::factory()->rider()->create(['phone' => '237670000099']);
        RiderProfile::factory()->approved()->online()->create(['user_id' => $riderUser->id]);

        // Passenger registers via the API and receives a token.
        $register = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test Passenger',
            'phone' => '237620000099',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'passenger',
        ]);
        $register->assertCreated();
        $this->assertNotEmpty($register->json('token'));

        $passenger = User::where('phone', '237620000099')->firstOrFail();
        Sanctum::actingAs($passenger);

        // Passenger requests a ride — dispatcher assigns the rider.
        $booking = $this->postJson('/api/v1/bookings', [
            'pickup_address' => 'Mile 4, Bamenda',
            'pickup_lat' => 5.96,
            'pickup_lng' => 10.15,
            'destination_address' => 'Commercial Avenue, Bamenda',
            'destination_lat' => 5.95,
            'destination_lng' => 10.14,
        ]);

        $booking->assertCreated();
        $this->assertSame('requested', $booking->json('data.status'));
        $this->assertSame($riderUser->id, $booking->json('data.rider.id'));
        $this->assertGreaterThan(0, $booking->json('data.fare'));

        $bookingId = $booking->json('data.id');

        // Rider logs in (real endpoint) then works the trip.
        $this->postJson('/api/v1/auth/login', [
            'phone' => '237670000099',
            'password' => 'password',
        ])->assertOk()->assertJsonPath('user.id', $riderUser->id);

        Sanctum::actingAs($riderUser);

        $requests = $this->getJson('/api/v1/rider/requests');
        $requests->assertOk();
        $this->assertCount(1, $requests->json('data'));

        $this->postJson("/api/v1/rider/bookings/{$bookingId}/accept")
            ->assertOk()
            ->assertJsonPath('data.status', 'accepted');
        $this->postJson("/api/v1/rider/bookings/{$bookingId}/arrive")
            ->assertJsonPath('data.status', 'arrived');
        $this->postJson("/api/v1/rider/bookings/{$bookingId}/start")
            ->assertJsonPath('data.status', 'ongoing');
        $this->postJson("/api/v1/rider/bookings/{$bookingId}/complete")
            ->assertJsonPath('data.status', 'completed');

        // Passenger rates the completed trip.
        Sanctum::actingAs($passenger);
        $this->postJson("/api/v1/bookings/{$bookingId}/rate", ['rating' => 5])
            ->assertOk()
            ->assertJsonPath('data.passenger_rating', 5);

        $this->assertSame(Booking::STATUS_COMPLETED, Booking::find($bookingId)->status);
    }

    public function test_rider_cannot_go_online_before_verification(): void
    {
        $riderUser = User::factory()->rider()->create(['phone' => '237670000098']);
        RiderProfile::factory()->create(['user_id' => $riderUser->id]); // pending

        $this->postJson('/api/v1/auth/login', [
            'phone' => '237670000098',
            'password' => 'password',
        ])->assertOk();

        Sanctum::actingAs($riderUser);
        $this->postJson('/api/v1/rider/online', ['is_online' => true])->assertStatus(422);
    }

    public function test_admin_routes_require_admin_role(): void
    {
        $user = User::factory()->passenger()->create(['phone' => '237620000097']);

        $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
    }
}
