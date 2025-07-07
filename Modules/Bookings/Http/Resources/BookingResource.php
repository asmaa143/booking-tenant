<?php

namespace Modules\Bookings\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'user_id' => $this->user_id,
            'team' => [
                'id' => $this->team_id,
                'name' => $this->team?->name ?? null,
            ],
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
