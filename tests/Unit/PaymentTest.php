<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PaymentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_booking()
    {
        $booking  = factory(\App\Models\Booking::class)->create();
        $Payment  = factory(\App\Models\Payment::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(\App\Models\Booking::class, $Payment->booking);
    }
}
