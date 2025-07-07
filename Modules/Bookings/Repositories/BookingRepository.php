<?php

namespace Modules\Bookings\Repositories;

use Modules\Bookings\Models\Booking;

class BookingRepository implements BookingRepositoryInterface
{
    public function getAllForUser(int $userId)
    {
        return Booking::where('user_id', $userId)
            ->with('team')
            ->orderBy('date')
            ->get();
    }

    public function create(array $data)
    {
        return Booking::create($data);
    }

    public function findForUser(int $bookingId, int $userId)
    {
        return Booking::where('id', $bookingId)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    public function existsConflict(array $data): bool
    {
        return Booking::where('team_id', $data['team_id'])
            ->where('date', $data['date'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']]);
            })
            ->exists();
    }
}
