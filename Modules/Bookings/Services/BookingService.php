<?php

namespace Modules\Bookings\Services;

use Modules\Bookings\Repositories\BookingRepositoryInterface;
use Modules\Teams\Services\TimeSlotService;

class BookingService implements BookingServiceInterface
{
    protected $bookings;
    protected $slots;

    public function __construct(BookingRepositoryInterface $bookings,TimeSlotService $slots)
    {
        $this->bookings = $bookings;
        $this->slots = $slots;
    }

    public function listUserBookings(int $userId)
    {
        return $this->bookings->getAllForUser($userId);
    }

    public function createBooking(array $data)
    {
        if ($this->bookings->existsConflict($data)) {
            throw new \Exception('Slot is already booked.');
        }

        // ✅ **Validate slot against generated slots**
        $team = \Modules\Teams\Models\Team::with('availabilities')->findOrFail($data['team_id']);
        $from = \Carbon\Carbon::parse($data['date']);
        $to = $from->copy();

        $slots = $this->slots->generateSlots($team, $from, $to);

        $valid = collect($slots)->first(function ($slot) use ($data) {
            return $slot['start_time'] == $data['start_time'] && $slot['end_time'] == $data['end_time'];
        });

        if (! $valid) {
            throw new \Exception('Invalid slot. Please select an available slot.');
        }

        return $this->bookings->create($data);
    }


    public function cancelBooking(int $bookingId, int $userId)
    {
        $booking = $this->bookings->findForUser($bookingId, $userId);
        $booking->delete();
        return true;
    }
}
