<?php

namespace Modules\Availability\Database\Seeders;

use App\Support\Enum\DayOfWeekEnum;
use Illuminate\Database\Seeder;
use Modules\Availability\Models\TeamAvailability;
use Modules\Teams\Models\Team;

class TeamAvailabilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::all();

        foreach ($teams as $team) {
            // Example: Sunday to Thursday 9am - 8pm
            foreach ([
                         DayOfWeekEnum::Sunday,
                         DayOfWeekEnum::Monday,
                         DayOfWeekEnum::Tuesday,
                         DayOfWeekEnum::Wednesday,
                         DayOfWeekEnum::Thursday
                     ] as $day) {
                TeamAvailability::create([
                    'team_id' => $team->id,
                    'day_of_week' => $day->value,
                    'start_time' => '09:00:00',
                    'end_time' => '20:00:00',
                ]);
            }
        }

    }
}
