<?php

namespace App\Events;
use Illuminate\Queue\SerializesModels;
use Modules\Tenants\Models\Tenant;

class TenantRegistered
{
    use SerializesModels;

    public Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }
}
