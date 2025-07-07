<?php

use Illuminate\Support\Facades\Route;
use Modules\Tenants\Models\Tenant;

Route::get('/', function () {

    return view('welcome');
});
