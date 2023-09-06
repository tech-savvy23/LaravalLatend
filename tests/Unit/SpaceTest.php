<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Space;
use App\Models\Booking;
use App\Models\SpaceType;
use App\Models\BookingSpace;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SpaceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function space_has_many_space_type()
    {
        $space       = factory(Space::class)->create();
        factory(SpaceType::class)->create(['space_id' => $space->id]);
        factory(SpaceType::class)->create(['space_id' => 22]);
        $this->assertInstanceOf(SpaceType::class, $space->type[0]);
        $this->assertEquals(1, count($space->type));
    }

    /** @test */
    public function space_has_many_bookings()
    {
        $space       = $this->create_space();
        $booking     = factory(Booking::class)->create();
        factory(BookingSpace::class)->create(['space_id' => $space->id, 'booking_id'=>$booking->id]);
        $this->assertInstanceOf(BookingSpace::class, $space->bookings[0]);
        $this->assertEquals(1, count($space->bookings));
    }
}
