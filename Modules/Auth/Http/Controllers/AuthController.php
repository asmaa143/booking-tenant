<?php

namespace Modules\Auth\Http\Controllers;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Http\Controllers\Controller;

use App\Support\Traits\Api\ApiResponseTrait;
use AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Services\AuthServiceInterface;
use Modules\Tenants\Models\Tenant;
use Modules\Users\Http\Resources\UserResource;
use Modules\Users\Models\User;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $dto = new RegisterDTO($request->validated());
        $result = $this->authService->register($dto);

        return $this->responseData($result, msg: 'Registration successful');
    }

    public function login(LoginRequest $request)
    {
        $dto = new LoginDTO($request->validated());
        $result = $this->authService->login($dto);


        if (is_string($result)) {
            return $this->errorResponse(401, $result);
        }

        return $this->responseData($result, msg:'Login successful');
    }

}
