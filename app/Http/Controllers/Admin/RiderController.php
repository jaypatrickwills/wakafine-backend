<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiderProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiderController extends Controller
{
    public function index(Request $request): View
    {
        $query = RiderProfile::with('user')->latest();

        if ($status = $request->query('verification')) {
            $query->where('verification_status', $status);
        }

        if ($request->query('online') === '1') {
            $query->where('is_online', true);
        }

        $riders = $query->paginate(15)->withQueryString();

        return view('admin.riders.index', compact('riders'));
    }

    public function show(RiderProfile $rider): View
    {
        $rider->load(['user', 'user.passengerBookings', 'user.riderBookings']);

        $recentTrips = $rider->user->riderBookings()->latest()->limit(10)->get();
        $earnings = $rider->user->riderBookings()
            ->where('status', 'completed')
            ->selectRaw('COUNT(*) as trips, COALESCE(SUM(fare),0) as total')
            ->first();

        return view('admin.riders.show', compact('rider', 'recentTrips', 'earnings'));
    }

    public function approve(Request $request, RiderProfile $rider): RedirectResponse
    {
        $data = $request->validate([
            'verification_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $rider->update([
            'verification_status' => RiderProfile::VERIFICATION_APPROVED,
            'verification_notes' => $data['verification_notes'] ?? null,
            'is_online' => false, // rider must go online manually
        ]);

        return back()->with('success', 'Rider approved.');
    }

    public function reject(Request $request, RiderProfile $rider): RedirectResponse
    {
        $data = $request->validate([
            'verification_notes' => ['required', 'string', 'max:1000'],
        ]);

        $rider->update([
            'verification_status' => RiderProfile::VERIFICATION_REJECTED,
            'verification_notes' => $data['verification_notes'],
            'is_online' => false,
        ]);

        return back()->with('success', 'Rider rejected.');
    }

    public function suspend(Request $request, RiderProfile $rider): RedirectResponse
    {
        $rider->user->update(['status' => User::STATUS_SUSPENDED]);
        $rider->update(['is_online' => false]);

        return back()->with('success', 'Rider account suspended.');
    }

    public function reactivate(Request $request, RiderProfile $rider): RedirectResponse
    {
        $rider->user->update(['status' => User::STATUS_ACTIVE]);

        return back()->with('success', 'Rider account reactivated.');
    }
}
