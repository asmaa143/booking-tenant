<?php

namespace Modules\Auth\Services;
use App\Events\TenantRegistered;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Services\AuthServiceInterface;
use Modules\Tenants\Models\Tenant;
use Modules\Users\Models\User;

class AuthService implements AuthServiceInterface
{
    public function register(RegisterDTO|\App\DTOs\Auth\RegisterDTO $dto): array
    {
        $tenant = Tenant::create([
            'name' => $dto->tenant_name,
        ]);

        event(new TenantRegistered($tenant));

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
        ]);

        event(new UserRegistered($user));

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'tenant' => $tenant,
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(LoginDTO|\App\DTOs\Auth\LoginDTO $dto): array|string
    {
        $user = User::where('email', $dto->email)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            return 'Invalid credentials';
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
