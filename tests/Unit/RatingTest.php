<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Rating;
use App\Models\Booking;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RatingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_booking()
    {
        $booking = factory(Booking::class)->create();
        $Rating  = factory(Rating::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(Booking::class, $Rating->booking);
    }
}
