<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingDispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request, BookingDispatcher $dispatcher): JsonResponse
    {
        $user = $request->user();

        abort_unless($user->isPassenger(), 403, 'Only passengers can request rides.');

        $hasActiveBooking = $user->passengerBookings()
            ->whereIn('status', Booking::ACTIVE_STATUSES)
            ->exists();

        if ($hasActiveBooking) {
            throw ValidationException::withMessages([
                'booking' => ['You already have an active booking. Complete or cancel it first.'],
            ]);
        }

        $data = $request->validated();

        $booking = Booking::create([
            'passenger_id' => $user->id,
            'pickup_address' => $data['pickup_address'],
            'pickup_lat' => $data['pickup_lat'] ?? null,
            'pickup_lng' => $data['pickup_lng'] ?? null,
            'destination_address' => $data['destination_address'],
            'destination_lat' => $data['destination_lat'] ?? null,
            'destination_lng' => $data['destination_lng'] ?? null,
            'status' => Booking::STATUS_SEARCHING,
            'requested_at' => now(),
        ]);

        $booking->fare = $booking->calculateFare();
        $booking->save();

        $dispatcher->dispatch($booking);

        return (new BookingResource($booking->fresh(['passenger', 'rider.riderProfile'])))
            ->response()
            ->setStatusCode(201);
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $bookings = $request->user()
            ->passengerBookings()
            ->with(['passenger', 'rider.riderProfile'])
            ->latest()
            ->paginate(15);

        return BookingResource::collection($bookings);
    }

    public function show(Request $request, Booking $booking): BookingResource
    {
        $this->authorizeParticipant($request, $booking);

        // If the booking is still searching, retry dispatching — a rider
        // may have come online since the request was created.
        if ($booking->status === Booking::STATUS_SEARCHING) {
            app(BookingDispatcher::class)->dispatch($booking);
            $booking->refresh();
        }

        return new BookingResource(
            $booking->load(['passenger', 'rider.riderProfile'])
        );
    }

    public function cancel(Request $request, Booking $booking): BookingResource
    {
        $this->authorizeParticipant($request, $booking);

        if (! in_array($booking->status, [
            Booking::STATUS_SEARCHING,
            Booking::STATUS_REQUESTED,
            Booking::STATUS_ACCEPTED,
        ], true)) {
            throw ValidationException::withMessages([
                'booking' => ['This booking can no longer be cancelled.'],
            ]);
        }

        $booking->update([
            'status' => Booking::STATUS_CANCELLED,
            'cancelled_at' => now(),
        ]);

        return new BookingResource($booking->fresh(['passenger', 'rider.riderProfile']));
    }

    public function sos(Request $request, Booking $booking): BookingResource
    {
        $this->authorizeParticipant($request, $booking);

        abort_unless($booking->isActive(), 422, 'SOS can only be triggered during an active trip.');

        if (! $booking->sos_triggered) {
            $booking->update([
                'sos_triggered' => true,
                'sos_at' => now(),
            ]);
        }

        return new BookingResource($booking->fresh(['passenger', 'rider.riderProfile']));
    }

    public function rate(Request $request, Booking $booking): BookingResource
    {
        $user = $request->user();
        $booking->loadMissing(['passenger', 'rider']);

        $data = $request->validate([
            'rating' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
        ]);

        abort_unless($booking->status === Booking::STATUS_COMPLETED, 422, 'You can only rate completed trips.');
        abort_unless($user->isPassenger() && $booking->passenger_id === $user->id, 403, 'You can only rate your own trips.');

        $booking->update(['passenger_rating' => $data['rating']]);

        return new BookingResource($booking->fresh(['passenger', 'rider.riderProfile']));
    }

    private function authorizeParticipant(Request $request, Booking $booking): void
    {
        $user = $request->user();

        $isParticipant = $booking->passenger_id === $user->id
            || $booking->rider_id === $user->id
            || $user->isAdmin();

        abort_unless($isParticipant, 403, 'You are not part of this booking.');
    }
}
