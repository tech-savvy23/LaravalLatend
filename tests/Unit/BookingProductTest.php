<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookingProductTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_booking()
    {
        $booking         = factory(\App\Models\Booking::class)->create();
        $BookingProduct  = factory(\App\Models\BookingProduct::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(\App\Models\Booking::class, $BookingProduct->booking);
    }

    /** @test */
    public function it_belongs_to_products()
    {
        $products        = factory(\App\Models\Product::class)->create();
        $BookingProduct  = factory(\App\Models\BookingProduct::class)->create(['product_id' => $products->id]);
        $this->assertInstanceOf(\App\Models\Product::class, $BookingProduct->product);
    }
}
