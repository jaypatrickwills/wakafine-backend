<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\RiderProfile;
use App\Models\User;
use Illuminate\Support\Collection;

class BookingDispatcher
{
    /**
     * Assign a searching booking to the best available rider.
     *
     * Picks the nearest online, verified rider who has not already
     * declined this booking. When coordinates are available the
     * nearest rider wins; otherwise riders are considered in order.
     */
    public function dispatch(Booking $booking): ?RiderProfile
    {
        if ($booking->status !== Booking::STATUS_SEARCHING) {
            return $booking->rider_id
                ? RiderProfile::where('user_id', $booking->rider_id)->first()
                : null;
        }

        $declinedIds = $booking->declined_rider_ids ?? [];

        /** @var Collection<int, RiderProfile> $candidates */
        $candidates = RiderProfile::query()
            ->where('is_online', true)
            ->where('verification_status', RiderProfile::VERIFICATION_APPROVED)
            ->whereHas('user', fn ($query) => $query
                ->where('status', User::STATUS_ACTIVE)
                ->where('role', User::ROLE_RIDER)
                ->where('id', '!=', $booking->passenger_id))
            ->whereNotIn('user_id', $declinedIds)
            ->with('user')
            ->get();

        if ($candidates->isEmpty()) {
            return null;
        }

        $rider = $this->nearestRider($booking, $candidates);

        $booking->update([
            'rider_id' => $rider->user_id,
            'status' => Booking::STATUS_REQUESTED,
            'requested_at' => now(),
        ]);

        return $rider;
    }

    /**
     * @param  Collection<int, RiderProfile>  $candidates
     */
    private function nearestRider(Booking $booking, Collection $candidates): RiderProfile
    {
        $hasPickupCoords = $booking->pickup_lat !== null && $booking->pickup_lng !== null;

        return $candidates
            ->sortBy(function (RiderProfile $profile) use ($booking, $hasPickupCoords) {
                if (! $hasPickupCoords
                    || $profile->current_lat === null
                    || $profile->current_lng === null) {
                    return PHP_FLOAT_MAX;
                }

                return $this->distanceKm(
                    (float) $booking->pickup_lat,
                    (float) $booking->pickup_lng,
                    $profile->current_lat,
                    $profile->current_lng,
                );
            })
            ->first();
    }

    private function distanceKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
