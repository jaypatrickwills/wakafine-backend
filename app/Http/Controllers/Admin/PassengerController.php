<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PassengerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', User::ROLE_PASSENGER)->withCount('passengerBookings')->latest();

        if ($request->query('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $passengers = $query->paginate(15)->withQueryString();

        return view('admin.passengers.index', compact('passengers'));
    }

    public function show(User $passenger): View
    {
        abort_unless($passenger->isPassenger(), 404);

        $passenger->load('complaints');
        $bookings = $passenger->passengerBookings()->with('rider')->latest()->limit(20)->get();

        return view('admin.passengers.show', compact('passenger', 'bookings'));
    }

    public function suspend(User $passenger): RedirectResponse
    {
        abort_unless($passenger->isPassenger(), 404);
        $passenger->update(['status' => User::STATUS_SUSPENDED]);

        return back()->with('success', 'Passenger suspended.');
    }

    public function reactivate(User $passenger): RedirectResponse
    {
        abort_unless($passenger->isPassenger(), 404);
        $passenger->update(['status' => User::STATUS_ACTIVE]);

        return back()->with('success', 'Passenger reactivated.');
    }
}
