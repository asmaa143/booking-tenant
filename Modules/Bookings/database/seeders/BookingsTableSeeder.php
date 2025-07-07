<?php

namespace Modules\Bookings\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Bookings\Models\Booking;
use Modules\Teams\Models\Team;
use Modules\Users\Models\User;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::all();
        $users = User::all();

        foreach ($teams as $team) {
            Booking::create([
                'tenant_id' => $team->tenant_id,
                'team_id' => $team->id,
                'user_id' => $users->where('tenant_id', $team->tenant_id)->first()->id ?? $users->first()->id,
                'date' => now()->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
            ]);
        }
    }
}
