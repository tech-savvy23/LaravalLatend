<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\BookingCancel;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Booking_cancelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_booking()
    {
        $booking         = factory(Booking::class)->create();
        $Booking_cancel  = factory(BookingCancel::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(Booking::class, $Booking_cancel->booking);
    }
}
