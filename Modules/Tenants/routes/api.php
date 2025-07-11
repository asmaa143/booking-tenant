<?php

use Illuminate\Support\Facades\Route;
use Modules\Tenants\Http\Controllers\TenantsController;

//Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//    Route::apiResource('tenants', TenantsController::class)->names('tenants');
//});
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
    Route::get('/v1/tenant', [TenantsController::class, 'show']);
});
