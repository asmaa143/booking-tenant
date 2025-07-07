<?php

namespace Modules\Auth\Services;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;

interface AuthServiceInterface
{
    public function register(RegisterDTO $dto): array;
    public function login(LoginDTO $dto): array|string;
}
