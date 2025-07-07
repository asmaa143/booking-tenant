<?php

namespace Modules\Tenants\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;

class TenantsController extends Controller
{
    use ApiResponseTrait;

    public function show(Request $request)
    {
        $tenant = $request->user()->tenant;

        return $this->responseData($tenant, msg: 'Current tenant info retrieved successfully.');
    }
}
