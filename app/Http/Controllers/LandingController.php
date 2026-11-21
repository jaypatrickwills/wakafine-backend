<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\RiderProfile;
use App\Models\User;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        return view('landing', [
            'stats' => [
                'trips' => Booking::where('status', Booking::STATUS_COMPLETED)->count(),
                'riders' => RiderProfile::where('verification_status', RiderProfile::VERIFICATION_APPROVED)->count(),
                'passengers' => User::where('role', User::ROLE_PASSENGER)->count(),
            ],
        ]);
    }
}
