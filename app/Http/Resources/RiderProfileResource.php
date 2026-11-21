<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderProfileResource extends JsonResource
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
            'bike_type' => $this->bike_type,
            'bike_plate' => $this->bike_plate,
            'bike_color' => $this->bike_color,
            'verification_status' => $this->verification_status,
            'verification_notes' => $this->verification_notes,
            'is_online' => $this->is_online,
            'current_lat' => $this->current_lat,
            'current_lng' => $this->current_lng,
            'has_id_document' => $this->id_document_path !== null,
            'has_license' => $this->license_path !== null,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
