<?php

namespace Modules\Teams\Services;

use Carbon\Carbon;
use Modules\Teams\Models\Team;

class TimeSlotService
{
    public function generateSlots(Team $team, Carbon $from, Carbon $to): array
    {
        $slots = [];
        $today = Carbon::today();
        $now = Carbon::now();

        $bookedSlots = $team->bookings()
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->get(['date', 'start_time', 'end_time']);

        $bookedMap = [];
        foreach ($bookedSlots as $booking) {
            $bookedMap[$booking->date][] = [$booking->start_time, $booking->end_time];
        }

        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {

            if ($date->lt($today)) {
                continue;
            }

            $dayOfWeek = $date->dayOfWeek;
            $availability = $team->availabilities->where('day_of_week', $dayOfWeek);

            foreach ($availability as $slot) {
                $start = $date->copy()->setTimeFrom(Carbon::parse($slot->start_time));
                $end = $date->copy()->setTimeFrom(Carbon::parse($slot->end_time));

                while ($start->lt($end)) {
                    $slotStart = $start->copy();
                    $slotEnd = $slotStart->copy()->addHour();

                    if ($slotEnd->gt($end)) {
                        break;
                    }

                    // skip if slot in the past (today only)
                    if ($date->isSameDay($today) && $slotStart->lt($now)) {
                        $start->addHour();
                        continue;
                    }

                    $isBooked = false;
                    if (isset($bookedMap[$date->toDateString()])) {
                        foreach ($bookedMap[$date->toDateString()] as $b) {
                            if (
                                ($slotStart->format('H:i:s') >= $b[0] && $slotStart->format('H:i:s') < $b[1]) ||
                                ($slotEnd->format('H:i:s') > $b[0] && $slotEnd->format('H:i:s') <= $b[1])
                            ) {
                                $isBooked = true;
                                break;
                            }
                        }
                    }

                    if (! $isBooked) {
                        $slots[] = [
                            'date' => $date->toDateString(),
                            'start_time' => $slotStart->format('H:i:s'),
                            'end_time' => $slotEnd->format('H:i:s'),
                        ];
                    }

                    $start->addHour();
                }

            }
        }

        return $slots;
    }


}
