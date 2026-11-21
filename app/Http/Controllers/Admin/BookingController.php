<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['passenger', 'rider'])->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($request->query('sos') === '1') {
            $query->where('sos_triggered', true);
        }

        $bookings = $query->paginate(20)->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'statuses' => [
                Booking::STATUS_SEARCHING,
                Booking::STATUS_REQUESTED,
                Booking::STATUS_ACCEPTED,
                Booking::STATUS_ARRIVED,
                Booking::STATUS_ONGOING,
                Booking::STATUS_COMPLETED,
                Booking::STATUS_CANCELLED,
            ],
        ]);
    }

    public function show(Booking $booking): View
    {
        $booking->load(['passenger', 'rider.riderProfile']);

        return view('admin.bookings.show', compact('booking'));
    }
}
