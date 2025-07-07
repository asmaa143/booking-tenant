<?php

namespace Modules\Bookings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Teams\Models\Team;
use Modules\Tenants\Models\Tenant;
use Modules\Users\Models\User;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

// use Modules\Bookings\Database\Factories\BookingFactory;

class Booking extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    protected $fillable = [
        'tenant_id', 'team_id', 'user_id', 'date', 'start_time', 'end_time'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
