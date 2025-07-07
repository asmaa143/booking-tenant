<?php

use Illuminate\Support\Facades\Route;
use Modules\Bookings\Http\Controllers\BookingsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('bookings', BookingsController::class)->names('bookings');
});
Route::middleware(['auth:sanctum', 'tenant'])->prefix('/v1')->group(function () {
    Route::get('/bookings', [BookingsController::class, 'index']);
    Route::post('/bookings', [BookingsController::class, 'store']);
    Route::delete('/bookings/{id}', [BookingsController::class, 'destroy']);


});
