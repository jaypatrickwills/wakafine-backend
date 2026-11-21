<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\ComplaintController;
use App\Http\Controllers\Api\V1\RiderController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:6,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Passenger bookings
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::get('/bookings/{booking}', [BookingController::class, 'show']);
        Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
        Route::post('/bookings/{booking}/sos', [BookingController::class, 'sos']);
        Route::post('/bookings/{booking}/rate', [BookingController::class, 'rate']);

        // Rider
        Route::prefix('rider')->group(function () {
            Route::get('/profile', [RiderController::class, 'profile']);
            Route::patch('/profile', [RiderController::class, 'updateProfile']);
            Route::post('/documents', [RiderController::class, 'uploadDocuments']);
            Route::post('/online', [RiderController::class, 'toggleOnline']);
            Route::get('/requests', [RiderController::class, 'requests']);
            Route::get('/trips', [RiderController::class, 'trips']);
            Route::get('/earnings', [RiderController::class, 'earnings']);

            Route::post('/bookings/{booking}/accept', [RiderController::class, 'accept']);
            Route::post('/bookings/{booking}/reject', [RiderController::class, 'reject']);
            Route::post('/bookings/{booking}/arrive', [RiderController::class, 'arrive']);
            Route::post('/bookings/{booking}/start', [RiderController::class, 'start']);
            Route::post('/bookings/{booking}/complete', [RiderController::class, 'complete']);
            Route::post('/bookings/{booking}/rate', [RiderController::class, 'ratePassenger']);
        });

        // Complaints
        Route::post('/complaints', [ComplaintController::class, 'store']);
        Route::get('/complaints', [ComplaintController::class, 'index']);
    });
});
