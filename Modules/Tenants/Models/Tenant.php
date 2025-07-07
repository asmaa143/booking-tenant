<?php

namespace Modules\Tenants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Teams\Models\Team;
use Modules\Users\Models\User;
use Spatie\Multitenancy\Database\Factories\TenantFactory;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;

// use Modules\Tenants\Database\Factories\TenantFactory;

class Tenant extends BaseTenant
{
    use HasFactory;
    protected $table = 'tenants';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

}
