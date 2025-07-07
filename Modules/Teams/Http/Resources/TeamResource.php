<?php

namespace Modules\Teams\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'name' => $this->name,

        ];
    }
}
