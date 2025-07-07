<?php

namespace Modules\Availability\Models;

use App\Support\Enum\DayOfWeekEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Teams\Models\Team;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

// use Modules\Availability\Database\Factories\TeamAvailabilityFactory;

class TeamAvailability extends Model
{

    protected $fillable = ['team_id', 'day_of_week', 'start_time', 'end_time'];
    protected $appends = ['day_name'];

    public function team(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    public function getDayNameAttribute(): string|null
    {
        if ($this->day_of_week !== null) {
            $dayEnum = DayOfWeekEnum::tryFrom($this->day_of_week);
            return $dayEnum ? $dayEnum->label() : null;
        }
        return null;
    }
}
