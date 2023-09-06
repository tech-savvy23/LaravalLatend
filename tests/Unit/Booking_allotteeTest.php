<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\BookingAllottee;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Booking_allotteeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_booking()
    {
        $booking           = factory(Booking::class)->create();
        $Booking_allottee  = factory(BookingAllottee::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(Booking::class, $Booking_allottee->booking);
    }

    /** @test */
    public function it_belongs_to_partner()
    {
        // $booking           = factory(\App\Models\Booking::class)->create();
        $partner           = $this->create_partner();
        $Booking_allottee  = factory(BookingAllottee::class)->create(['allottee_id'=>$partner->id]);
        $this->assertInstanceOf(Partner::class, $Booking_allottee->partner);
    }
}
