<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Users\Models\User;

class UserRegistered
{
    use SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
