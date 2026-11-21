<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Complaint;
use App\Models\RiderProfile;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'passengers' => User::where('role', User::ROLE_PASSENGER)->count(),
            'riders' => User::where('role', User::ROLE_RIDER)->count(),
            'verified_riders' => RiderProfile::where('verification_status', RiderProfile::VERIFICATION_APPROVED)->count(),
            'pending_verifications' => RiderProfile::where('verification_status', RiderProfile::VERIFICATION_PENDING)->count(),
            'bookings_today' => Booking::whereDate('created_at', today())->count(),
            'active_trips' => Booking::whereIn('status', Booking::ACTIVE_STATUSES)->count(),
            'completed_trips' => Booking::where('status', Booking::STATUS_COMPLETED)->count(),
            'open_complaints' => Complaint::whereIn('status', [Complaint::STATUS_OPEN, Complaint::STATUS_INVESTIGATING])->count(),
            'sos_alerts' => Booking::where('sos_triggered', true)->where('status', '!=', Booking::STATUS_COMPLETED)->count(),
        ];

        $recentBookings = Booking::with(['passenger', 'rider'])
            ->latest()
            ->limit(8)
            ->get();

        $recentComplaints = Complaint::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $pendingRiders = RiderProfile::with('user')
            ->where('verification_status', RiderProfile::VERIFICATION_PENDING)
            ->latest()
            ->limit(5)
            ->get();

        $bookingsThisWeek = Booking::query()
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        return view('admin.dashboard', compact(
            'stats', 'recentBookings', 'recentComplaints', 'pendingRiders', 'bookingsThisWeek'
        ));
    }
}
