<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BookingAllottee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Booking_allotteeTest extends TestCase
{
    use RefreshDatabase;

    public function create_booking_allottee($args = [], $num = null)
    {
        return factory(BookingAllottee::class, $num)->create($args);
    }

    /** @test */
    public function api_can_give_all_booking_allottee()
    {
        $this->create_booking_allottee();
        $this->getJson(route('booking_allottee.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_booking_allottee()
    {
        $booking_allottee = $this->create_booking_allottee();
        $this->getJson(route('booking_allottee.show', $booking_allottee->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_booking_allottee()
    {
        $booking_allottee = factory(BookingAllottee::class)->make(['booking_id' => 22]);
        $this->postJson(route('booking_allottee.store'), $booking_allottee->toArray())
            ->assertStatus(201);
        $this->assertDatabaseHas('Booking_allottees', ['booking_id' => 22]);
    }

    /** @test */
    public function api_can_update_booking_allottee()
    {
        $booking_allottee = $this->create_booking_allottee();
        $this->putJson(route('booking_allottee.update', $booking_allottee->id), ['booking_id' => 22222])
            ->assertStatus(202);
        $this->assertDatabaseHas('Booking_allottees', ['booking_id' => 22222]);
    }

    /** @test */
    public function api_can_delete_booking_allottee()
    {
        $booking_allottee = $this->create_booking_allottee();
        $this->deleteJson(route('booking_allottee.destroy', $booking_allottee->id))->assertStatus(204);
        $this->assertDatabaseMissing('Booking_allottees', ['booking_id' => $booking_allottee->booking_id]);
    }
}
