<?php

namespace Modules\Bookings\Services;

interface BookingServiceInterface
{
    public function listUserBookings(int $userId);

    public function createBooking(array $data);

    public function cancelBooking(int $bookingId, int $userId);
}
