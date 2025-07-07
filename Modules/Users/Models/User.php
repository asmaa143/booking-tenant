<?php

namespace Modules\Users\Models;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Bookings\Models\Booking;
use Modules\Tenants\Models\Tenant;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable;
    use UsesTenantConnection;
    use HasApiTokens;
    use HasFactory;
    protected $fillable = ['tenant_id', 'name', 'email', 'password'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    // ✅ حل factory path issue
    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
