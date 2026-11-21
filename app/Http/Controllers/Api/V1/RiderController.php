<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpdateRiderProfileRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\RiderProfileResource;
use App\Models\Booking;
use App\Models\RiderProfile;
use App\Services\BookingDispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RiderController extends Controller
{
    public function profile(Request $request): RiderProfileResource
    {
        return new RiderProfileResource($this->profileOrFail($request));
    }

    public function updateProfile(UpdateRiderProfileRequest $request): RiderProfileResource
    {
        $profile = $this->profileOrFail($request);
        $profile->update($request->validated());

        return new RiderProfileResource($profile->fresh('user'));
    }

    public function uploadDocuments(Request $request): RiderProfileResource|JsonResponse
    {
        $profile = $this->profileOrFail($request);

        $data = $request->validate([
            'id_document' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'license' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if (! isset($data['id_document']) && ! isset($data['license'])) {
            throw ValidationException::withMessages([
                'documents' => ['Provide at least one document to upload.'],
            ]);
        }

        DB::transaction(function () use ($request, $profile) {
            if ($request->hasFile('id_document')) {
                $profile->id_document_path = $request->file('id_document')
                    ->store('rider-documents', 'public');
            }

            if ($request->hasFile('license')) {
                $profile->license_path = $request->file('license')
                    ->store('rider-documents', 'public');
            }

            // New documents mean the rider must be re-verified.
            if ($profile->isVerified()) {
                $profile->verification_status = RiderProfile::VERIFICATION_PENDING;
                $profile->verification_notes = null;
            }

            $profile->save();
        });

        return new RiderProfileResource($profile->fresh('user'));
    }

    public function toggleOnline(Request $request): RiderProfileResource|JsonResponse
    {
        $profile = $this->profileOrFail($request);

        $data = $request->validate([
            'is_online' => ['required', 'boolean'],
        ]);

        if ($data['is_online'] && ! $profile->canGoOnline()) {
            return response()->json([
                'message' => 'You cannot go online yet.',
                'verification_status' => $profile->verification_status,
                'user_status' => $profile->user->status,
            ], 422);
        }

        $profile->update(['is_online' => $data['is_online']]);

        return new RiderProfileResource($profile->fresh('user'));
    }

    public function requests(Request $request): AnonymousResourceCollection
    {
        $bookings = Booking::query()
            ->where('rider_id', $request->user()->id)
            ->where('status', Booking::STATUS_REQUESTED)
            ->with(['passenger', 'rider.riderProfile'])
            ->latest('requested_at')
            ->get();

        return BookingResource::collection($bookings);
    }

    public function trips(Request $request): AnonymousResourceCollection
    {
        $query = Booking::query()
            ->where('rider_id', $request->user()->id)
            ->with(['passenger', 'rider.riderProfile'])
            ->latest();

        if ($request->query('filter') === 'active') {
            $query->whereIn('status', Booking::ACTIVE_STATUSES);
        } elseif ($request->query('filter') === 'history') {
            $query->whereNotIn('status', Booking::ACTIVE_STATUSES);
        }

        return BookingResource::collection($query->paginate(15));
    }

    public function earnings(Request $request): JsonResponse
    {
        $user = $request->user();

        $completed = Booking::query()
            ->where('rider_id', $user->id)
            ->where('status', Booking::STATUS_COMPLETED);

        $stats = (clone $completed)
            ->selectRaw('COUNT(*) as trips_count, COALESCE(SUM(fare), 0) as total_earnings')
            ->first();

        $today = (clone $completed)
            ->whereDate('completed_at', today())
            ->selectRaw('COUNT(*) as trips_count, COALESCE(SUM(fare), 0) as total_earnings')
            ->first();

        return response()->json([
            'currency' => 'XAF',
            'total_trips' => (int) $stats->trips_count,
            'total_earnings' => (int) $stats->total_earnings,
            'today_trips' => (int) $today->trips_count,
            'today_earnings' => (int) $today->total_earnings,
        ]);
    }

    public function accept(Request $request, Booking $booking): BookingResource
    {
        $this->authorizeRiderBooking($request, $booking);
        $this->assertStatus($booking, Booking::STATUS_REQUESTED, 'accept');

        $booking->update([
            'status' => Booking::STATUS_ACCEPTED,
            'accepted_at' => now(),
        ]);

        return new BookingResource($booking->fresh(['passenger', 'rider.riderProfile']));
    }

    public function reject(Request $request, Booking $booking, BookingDispatcher $dispatcher): BookingResource
    {
        $this->authorizeRiderBooking($request, $booking);
        $this->assertStatus($booking, Booking::STATUS_REQUESTED, 'reject');

        $declined = $booking->declined_rider_ids ?? [];
        $declined[] = $request->user()->id;

        $booking->update([
            'rider_id' => null,
            'status' => Booking::STATUS_SEARCHING,
            'requested_at' => null,
            'accepted_at' => null,
            'declined_rider_ids' => array_values(array_unique($declined)),
        ]);

        // Immediately try to dispatch to the next nearest rider.
        $dispatcher->dispatch($booking->fresh());

        return new BookingResource($booking->fresh(['passenger', 'rider.riderProfile']));
    }

    public function arrive(Request $request, Booking $booking): BookingResource
    {
        return $this->transition($request, $booking, Booking::STATUS_ACCEPTED, Booking::STATUS_ARRIVED, 'arrived_at');
    }

    public function start(Request $request, Booking $booking): BookingResource
    {
        $this->authorizeRiderBooking($request, $booking);

        if (! in_array($booking->status, [Booking::STATUS_ACCEPTED, Booking::STATUS_ARRIVED], true)) {
            throw ValidationException::withMessages([
                'booking' => ['The trip cannot be started from its current status.'],
            ]);
        }

        $booking->update([
            'status' => Booking::STATUS_ONGOING,
            'started_at' => now(),
        ]);

        return new BookingResource($booking->fresh(['passenger', 'rider.riderProfile']));
    }

    public function complete(Request $request, Booking $booking): BookingResource
    {
        $this->authorizeRiderBooking($request, $booking);

        if ($booking->status !== Booking::STATUS_ONGOING) {
            throw ValidationException::withMessages([
                'booking' => ['Only an ongoing trip can be completed.'],
            ]);
        }

        $booking->update([
            'status' => Booking::STATUS_COMPLETED,
            'completed_at' => now(),
            'fare' => $booking->calculateFare(),
        ]);

        return new BookingResource($booking->fresh(['passenger', 'rider.riderProfile']));
    }

    public function ratePassenger(Request $request, Booking $booking): BookingResource
    {
        $this->authorizeRiderBooking($request, $booking);

        $data = $request->validate([
            'rating' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
        ]);

        if ($booking->status !== Booking::STATUS_COMPLETED) {
            throw ValidationException::withMessages([
                'booking' => ['You can only rate completed trips.'],
            ]);
        }

        $booking->update(['rider_rating' => $data['rating']]);

        return new BookingResource($booking->fresh(['passenger', 'rider.riderProfile']));
    }

    private function transition(Request $request, Booking $booking, string $from, string $to, string $timestamp): BookingResource
    {
        $this->authorizeRiderBooking($request, $booking);
        $this->assertStatus($booking, $from, $to);

        $booking->update([
            'status' => $to,
            $timestamp => now(),
        ]);

        return new BookingResource($booking->fresh(['passenger', 'rider.riderProfile']));
    }

    private function authorizeRiderBooking(Request $request, Booking $booking): void
    {
        abort_unless(
            $booking->rider_id === $request->user()->id,
            403,
            'This booking is not assigned to you.'
        );
    }

    private function assertStatus(Booking $booking, string $expected, string $action): void
    {
        if ($booking->status !== $expected) {
            throw ValidationException::withMessages([
                'booking' => ["Cannot {$action} a booking in status '{$booking->status}'."],
            ]);
        }
    }

    private function profileOrFail(Request $request): RiderProfile
    {
        $profile = $request->user()->riderProfile;

        abort_unless($profile !== null, 404, 'Rider profile not found. Register as a rider first.');

        return $profile;
    }
}
