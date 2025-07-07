<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;


//Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//    Route::apiResource('auths', AuthController::class)->names('auth');
//});



Route::prefix('/v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);


});
