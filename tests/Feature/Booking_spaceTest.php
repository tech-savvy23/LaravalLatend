<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BookingSpace;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Booking_spaceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_give_all_booking_space()
    {
        $this->create_booking_space();
        $this->getJson(route('booking_space.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_booking_space()
    {
        $booking_space = $this->create_booking_space();
        $this->getJson(route('booking_space.show', $booking_space->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_booking_space()
    {
        $booking_space = factory(BookingSpace::class)->make(['booking_id'=>'Laravel']);
        $this->postJson(route('booking_space.store'), $booking_space->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Booking_spaces', ['booking_id'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_booking_space()
    {
        $booking_space = $this->create_booking_space();
        $this->putJson(route('booking_space.update', $booking_space->id), ['booking_id'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Booking_spaces', ['booking_id'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_booking_space()
    {
        $booking_space = $this->create_booking_space();
        $this->deleteJson(route('booking_space.destroy', $booking_space->id))->assertStatus(204);
        $this->assertDatabaseMissing('Booking_spaces', ['booking_id'=>$booking_space->booking_id]);
    }
}
