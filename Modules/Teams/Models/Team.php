<?php

namespace Modules\Teams\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Availability\Models\TeamAvailability;
use Modules\Bookings\Models\Booking;
use Modules\Tenants\Models\Tenant;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

// use Modules\Teams\Database\Factories\TeamFactory;

class Team extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    protected $fillable = ['tenant_id', 'name'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function availabilities()
    {
        return $this->hasMany(TeamAvailability::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
