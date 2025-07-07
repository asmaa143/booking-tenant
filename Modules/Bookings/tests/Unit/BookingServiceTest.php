<?php

namespace Modules\Bookings\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Bookings\Models\Booking;
use Modules\Bookings\Repositories\BookingRepository;
use Modules\Bookings\Services\BookingService;
use Modules\Teams\Models\Team;
use Modules\Tenants\Models\Tenant;
use Modules\Users\Models\User;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BookingService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $repo = new BookingRepository();
        $this->service = new BookingService($repo, app('Modules\\Teams\\Services\\TimeSlotService'));
    }

    public function test_create_booking_successful()
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

        // Ensure availability exists for slot validation logic to pass
        $team->availabilities()->create([
            'day_of_week' => Carbon::today()->dayOfWeek,
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
        ]);

        $data = [
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
            'date' => Carbon::today()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ];

        $booking = $this->service->createBooking($data);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertDatabaseHas('bookings', [
            'team_id' => $team->id,
            'start_time' => '09:00:00',
        ]);
    }

    public function test_create_booking_conflict_throws_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Slot is already booked.');

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

        // existing booking
        Booking::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
            'date' => Carbon::today()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ]);

        // Ensure availability exists for slot validation logic
        $team->availabilities()->create([
            'day_of_week' => Carbon::today()->dayOfWeek,
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
        ]);

        $data = [
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
            'date' => Carbon::today()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ];

        $this->service->createBooking($data);
    }
}
