<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BookingService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Booking_serviceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_give_all_booking_service()
    {
        $this->create_booking_service();
        $this->getJson(route('booking_service.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_booking_service()
    {
        $booking_service = $this->create_booking_service();
        $this->getJson(route('booking_service.show', $booking_service->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_booking_service()
    {
        $booking_service = factory(BookingService::class)->make(['booking_id'=>'Laravel']);
        $this->postJson(route('booking_service.store'), $booking_service->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Booking_services', ['booking_id'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_booking_service()
    {
        $booking_service = $this->create_booking_service();
        $this->putJson(route('booking_service.update', $booking_service->id), ['booking_id'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Booking_services', ['booking_id'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_booking_service()
    {
        $booking_service = $this->create_booking_service();
        $this->deleteJson(route('booking_service.destroy', $booking_service->id))->assertStatus(204);
        $this->assertDatabaseMissing('Booking_services', ['booking_id'=>$booking_service->booking_id]);
    }
}
