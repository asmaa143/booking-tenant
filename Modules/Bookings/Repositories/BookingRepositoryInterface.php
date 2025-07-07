<?php

namespace Modules\Bookings\Repositories;

interface BookingRepositoryInterface
{
    public function getAllForUser(int $userId);

    public function create(array $data);

    public function findForUser(int $bookingId, int $userId);

    public function existsConflict(array $data): bool;
}
