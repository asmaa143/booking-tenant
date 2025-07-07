<?php

namespace Modules\Bookings\Tests;

use Carbon\Carbon;
use Modules\Teams\Models\Team;
use Modules\Tenants\Models\Tenant;
use Modules\Users\Models\User;

trait TestHelperTrait
{
    /**
     * 🔧 Create tenant + user + team with default data
     */
    public function createTenantUserTeam(): array
    {
        $tenant = Tenant::create(['name' => 'Test Tenant']);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $team = Team::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Team',
        ]);

        return compact('tenant', 'user', 'team');
    }

    /**
     * 🔧 Ensure availability exists for team covering full day or custom time range
     */
    public function addAvailability(Team $team, $start = '09:00:00', $end = '17:00:00')
    {
        return $team->availabilities()->create([
            'day_of_week' => Carbon::today()->dayOfWeek,
            'start_time' => $start,
            'end_time' => $end,
        ]);
    }

    /**
     * 🔧 Get first valid slot for a team for today using TimeSlotService
     */
    public function getValidSlot($teamId)
    {
        $timeSlotService = app('Modules\\Teams\\Services\\TimeSlotService');
        $slots = $timeSlotService->getAvailableSlots($teamId, Carbon::today()->toDateString());

        $this->assertNotEmpty($slots, 'No available slots returned.');

        return $slots[0];
    }
}
