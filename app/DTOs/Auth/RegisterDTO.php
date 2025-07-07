<?php

namespace App\DTOs\Auth;

class RegisterDTO
{
    public string $tenant_name;
    public string $name;
    public string $email;
    public string $password;

    public function __construct(array $data)
    {
        $this->tenant_name = $data['tenant_name'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
    }
}
