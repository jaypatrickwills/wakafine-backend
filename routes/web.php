<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PassengerController;
use App\Http\Controllers\Admin\RiderController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/riders', [RiderController::class, 'index'])->name('riders.index');
        Route::get('/riders/{rider}', [RiderController::class, 'show'])->name('riders.show');
        Route::post('/riders/{rider}/approve', [RiderController::class, 'approve'])->name('riders.approve');
        Route::post('/riders/{rider}/reject', [RiderController::class, 'reject'])->name('riders.reject');
        Route::post('/riders/{rider}/suspend', [RiderController::class, 'suspend'])->name('riders.suspend');
        Route::post('/riders/{rider}/reactivate', [RiderController::class, 'reactivate'])->name('riders.reactivate');

        Route::get('/passengers', [PassengerController::class, 'index'])->name('passengers.index');
        Route::get('/passengers/{passenger}', [PassengerController::class, 'show'])->name('passengers.show');
        Route::post('/passengers/{passenger}/suspend', [PassengerController::class, 'suspend'])->name('passengers.suspend');
        Route::post('/passengers/{passenger}/reactivate', [PassengerController::class, 'reactivate'])->name('passengers.reactivate');

        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

        Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
        Route::patch('/complaints/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
    });
});
