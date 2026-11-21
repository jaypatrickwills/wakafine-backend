<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'fare' => $this->fare,
            'pickup_address' => $this->pickup_address,
            'pickup_lat' => $this->pickup_lat,
            'pickup_lng' => $this->pickup_lng,
            'destination_address' => $this->destination_address,
            'destination_lat' => $this->destination_lat,
            'destination_lng' => $this->destination_lng,
            'sos_triggered' => $this->sos_triggered,
            'sos_at' => $this->sos_at?->toIso8601String(),
            'passenger_rating' => $this->passenger_rating,
            'rider_rating' => $this->rider_rating,
            'requested_at' => $this->requested_at?->toIso8601String(),
            'accepted_at' => $this->accepted_at?->toIso8601String(),
            'arrived_at' => $this->arrived_at?->toIso8601String(),
            'started_at' => $this->started_at?->toIso8601String(),
            'completed_at' => $this->completed_at?->toIso8601String(),
            'cancelled_at' => $this->cancelled_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'passenger' => new UserResource($this->whenLoaded('passenger')),
            'rider' => new UserResource($this->whenLoaded('rider')),
        ];
    }
}
