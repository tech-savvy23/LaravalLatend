<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BookingCancel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Booking_cancelTest extends TestCase
{
    use RefreshDatabase;

    public function create_booking_cancel($args = [], $num = null)
    {
        return factory(BookingCancel::class, $num)->create($args);
    }

    /** @test */
    public function api_can_give_all_booking_cancel()
    {
        $this->create_booking_cancel();
        $this->getJson(route('booking_cancel.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_booking_cancel()
    {
        $booking_cancel = $this->create_booking_cancel();
        $this->getJson(route('booking_cancel.show', $booking_cancel->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_booking_cancel()
    {
        $booking_cancel = factory(BookingCancel::class)->make(['booking_id'=>22]);
        $this->postJson(route('booking_cancel.store'), $booking_cancel->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Booking_cancels', ['booking_id'=>22]);
    }

    /** @test */
    public function api_can_update_booking_cancel()
    {
        $booking_cancel = $this->create_booking_cancel();
        $this->putJson(route('booking_cancel.update', $booking_cancel->id), ['booking_id'=>2222])
        ->assertStatus(202);
        $this->assertDatabaseHas('Booking_cancels', ['booking_id'=>2222]);
    }

    /** @test */
    public function api_can_delete_booking_cancel()
    {
        $booking_cancel = $this->create_booking_cancel();
        $this->deleteJson(route('booking_cancel.destroy', $booking_cancel->id))->assertStatus(204);
        $this->assertDatabaseMissing('Booking_cancels', ['booking_id'=>$booking_cancel->booking_id]);
    }
}
