<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Space;
use App\Models\Booking;
use App\Models\BookingSpace;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Booking_spaceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_booking()
    {
        $booking        = factory(Booking::class)->create();
        $Booking_space  = factory(BookingSpace::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(Booking::class, $Booking_space->booking);
    }

    /** @test */
    public function it_belongs_to_space()
    {
        $space          = factory(Space::class)->create();
        $Booking_space  = factory(BookingSpace::class)->create(['space_id' => $space->id]);
        $this->assertInstanceOf(Space::class, $Booking_space->space);
    }
}
