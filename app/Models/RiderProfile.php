<?php

namespace App\Models;

use Database\Factories\RiderProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'bike_type', 'bike_plate', 'bike_color',
    'id_document_path', 'license_path',
    'verification_status', 'verification_notes',
    'is_online', 'current_lat', 'current_lng',
])]
class RiderProfile extends Model
{
    /** @use HasFactory<RiderProfileFactory> */
    use HasFactory;

    public const VERIFICATION_PENDING = 'pending';

    public const VERIFICATION_APPROVED = 'approved';

    public const VERIFICATION_REJECTED = 'rejected';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_online' => 'boolean',
            'current_lat' => 'float',
            'current_lng' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === self::VERIFICATION_APPROVED;
    }

    public function canGoOnline(): bool
    {
        return $this->isVerified() && $this->user->status === User::STATUS_ACTIVE;
    }
}
