<?php

namespace App\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'passenger_id', 'rider_id',
    'pickup_address', 'pickup_lat', 'pickup_lng',
    'destination_address', 'destination_lat', 'destination_lng',
    'fare', 'status',
    'requested_at', 'accepted_at', 'arrived_at', 'started_at',
    'completed_at', 'cancelled_at',
    'sos_triggered', 'sos_at', 'declined_rider_ids',
    'passenger_rating', 'rider_rating',
])]
class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    public const STATUS_SEARCHING = 'searching';

    public const STATUS_REQUESTED = 'requested';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_ARRIVED = 'arrived';

    public const STATUS_ONGOING = 'ongoing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const ACTIVE_STATUSES = [
        self::STATUS_SEARCHING,
        self::STATUS_REQUESTED,
        self::STATUS_ACCEPTED,
        self::STATUS_ARRIVED,
        self::STATUS_ONGOING,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pickup_lat' => 'float',
            'pickup_lng' => 'float',
            'destination_lat' => 'float',
            'destination_lng' => 'float',
            'requested_at' => 'datetime',
            'accepted_at' => 'datetime',
            'arrived_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'sos_triggered' => 'boolean',
            'sos_at' => 'datetime',
            'declined_rider_ids' => 'array',
        ];
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function isActive(): bool
    {
        return in_array($this->status, self::ACTIVE_STATUSES, true);
    }

    /**
     * Estimate the fare in XAF. Uses a base fare plus a per-kilometre
     * rate when both endpoints have coordinates (haversine distance),
     * otherwise falls back to a flat estimate.
     */
    public function calculateFare(): int
    {
        $baseFare = 300;
        $perKm = 150;

        if ($this->pickup_lat !== null && $this->pickup_lng !== null
            && $this->destination_lat !== null && $this->destination_lng !== null) {
            $km = $this->haversineKm(
                (float) $this->pickup_lat,
                (float) $this->pickup_lng,
                (float) $this->destination_lat,
                (float) $this->destination_lng,
            );
        } else {
            $km = 3; // flat estimate when no coordinates are provided
        }

        return (int) round($baseFare + ($km * $perKm));
    }

    private function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
