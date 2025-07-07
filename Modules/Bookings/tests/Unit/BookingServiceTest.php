<?php

namespace Modules\Bookings\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Bookings\Models\Booking;
use Modules\Bookings\Repositories\BookingRepository;
use Modules\Bookings\Services\BookingService;
use Modules\Bookings\Tests\TestHelperTrait;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase, TestHelperTrait;

    protected BookingService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $repo = new BookingRepository();
        $this->service = new BookingService($repo, app('Modules\\Teams\\Services\\TimeSlotService'));
    }

    public function test_create_booking_successful()
    {
        extract($this->createTenantUserTeam());

        $this->addAvailability($team);

        $slot = $this->getValidSlot($team->id);

        $data = [
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
            'date' => $slot['date'],
            'start_time' => $slot['start_time'],
            'end_time' => $slot['end_time'],
        ];

        $booking = $this->service->createBooking($data);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertDatabaseHas('bookings', [
            'team_id' => $team->id,
            'start_time' => $slot['start_time'],
        ]);
    }

    public function test_create_booking_conflict_throws_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Slot is already booked.');

        extract($this->createTenantUserTeam());

        $this->addAvailability($team);

        $slot = $this->getValidSlot($team->id);

        // existing booking
        Booking::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
            'date' => $slot['date'],
            'start_time' => $slot['start_time'],
            'end_time' => $slot['end_time'],
        ]);

        $data = [
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
            'date' => $slot['date'],
            'start_time' => $slot['start_time'],
            'end_time' => $slot['end_time'],
        ];

        $this->service->createBooking($data);
    }
}
