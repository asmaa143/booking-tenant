<?php

namespace Modules\Teams\Repositories;

use Modules\Teams\Models\Team;
use Modules\Tenants\Models\Tenant;

class TeamRepository implements TeamRepositoryInterface
{
    public function allByTenant()
    {
        return Team::where('tenant_id', $this->getCurrentTenantId())->get();
    }

    public function createForTenant(array $data)
    {
        return Team::create(array_merge($data, ['tenant_id' => $this->getCurrentTenantId()]));
    }
    public function findById(int $id, array $with = [])
    {
        $query = Team::where('tenant_id', $this->getCurrentTenantId())
            ->where('id', $id);

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->firstOrFail();
    }


    public function setAvailability(int $teamId, array $availabilities)
    {
        $team = Team::where('tenant_id', $this->getCurrentTenantId())
            ->where('id', $teamId)
            ->firstOrFail();

        // Delete old availabilities
        $team->availabilities()->delete();

        // Create new availabilities
        foreach ($availabilities as $availability) {
            $team->availabilities()->create($availability);
        }

        return $team->availabilities;
    }

    protected function getCurrentTenantId(): int
    {
        $tenant = Tenant::current();

        if (! $tenant) {
            throw new \Exception('No current tenant found');
        }

        return $tenant->id;
    }

}
