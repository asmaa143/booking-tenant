<?php

namespace Modules\Bookings\Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Bookings\Models\Booking;
use Modules\Teams\Models\Team;
use Modules\Tenants\Models\Tenant;
use Modules\Users\Models\User;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_bookings()
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
            ->assertJsonFragment(['message' => 'Bookings retrieved successfully.']);
    }

    public function test_user_can_create_booking()
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

        // Ensure availability exists
        $team->availabilities()->create([
            'day_of_week' => Carbon::today()->dayOfWeek,
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
        ]);

        $payload = [
            'team_id' => $team->id,
            'date' => Carbon::today()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/v1/bookings', $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Booking created successfully.']);

        $this->assertDatabaseHas('bookings', [
            'team_id' => $team->id,
            'start_time' => '09:00:00',
        ]);
    }

    public function test_user_cannot_create_conflicting_booking()
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

        // existing booking
        Booking::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
            'date' => Carbon::today()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ]);

        // Ensure availability exists
        $team->availabilities()->create([
            'day_of_week' => Carbon::today()->dayOfWeek,
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
        ]);

        $payload = [
            'team_id' => $team->id,
            'date' => Carbon::today()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/v1/bookings', $payload);

        $response->assertStatus(409)
            ->assertJsonFragment(['message' => 'Slot is already booked.']);
    }

    public function test_user_can_cancel_booking()
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
            ->assertJsonFragment(['message' => 'Booking cancelled successfully.']);

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }
}
