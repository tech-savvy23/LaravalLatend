<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BookingBeforeAcceptCancel extends TestCase
{
    use RefreshDatabase;

    public function setup():void
    {
        parent::setup();
        $this->user = $this->authUser();
    }
    /**
     * A basic feature test example.
     *
     * @test
     */
    public function api_before_accept_cancel_the_booking()
    {
        $partner = $this->create_partner();
        $this->actingAs($partner, 'partner');
        $booking = $this->create_booking([], 10);
        $data = [
          'booking_id' => $booking[0]->id
        ];
        $response = $this->postJson(route('booking-cancel.store'), $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('booking_blocks', [
            'booking_id' => $booking[0]->id,
            'partner_id' => $partner->id
        ]);

    }
}
