<?php

namespace Modules\Bookings\Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Bookings\Models\Booking;
use Modules\Bookings\Tests\TestHelperTrait;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase, TestHelperTrait;

    public function test_user_can_list_bookings()
    {
        extract($this->createTenantUserTeam());

        Booking::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
            'date' => Carbon::today()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/v1/bookings');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Bookings retrieved successfully.');
    }

    public function test_user_can_create_booking()
    {
        extract($this->createTenantUserTeam());

        $this->addAvailability($team);

        $slot = $this->getValidSlot($team->id);

        $payload = [
            'team_id' => $team->id,
            'date' => Carbon::today()->toDateString(),
            'start_time' => $slot['start_time'],
            'end_time' => $slot['end_time'],
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/v1/bookings', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Booking created successfully');

        $this->assertDatabaseHas('bookings', [
            'team_id' => $team->id,
            'start_time' => $slot['start_time'],
        ]);
    }

    public function test_user_cannot_create_conflicting_booking()
    {
        extract($this->createTenantUserTeam());

        $this->addAvailability($team);

        // Create existing booking using valid slot
        $slot = $this->getValidSlot($team->id);

        Booking::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
            'date' => $slot['date'],
            'start_time' => $slot['start_time'],
            'end_time' => $slot['end_time'],
        ]);

        $payload = [
            'team_id' => $team->id,
            'date' => $slot['date'],
            'start_time' => $slot['start_time'],
            'end_time' => $slot['end_time'],
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/v1/bookings', $payload);

        $response->assertStatus(409)
            ->assertJsonPath('message', 'Failed to create booking: Slot is already booked.');
    }

    public function test_user_can_cancel_booking()
    {
        extract($this->createTenantUserTeam());

        $booking = Booking::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
            'date' => Carbon::today()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/v1/bookings/{$booking->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Booking cancelled successfully');

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }
}
