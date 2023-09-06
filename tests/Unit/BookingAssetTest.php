<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookingAssetTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_item()
    {
        $item          = factory(\App\Models\AssetItem::class)->create();
        $BookingAsset  = factory(\App\Models\BookingAsset::class)->create(['asset_item_id' => $item->id]);
        $this->assertInstanceOf(\App\Models\AssetItem::class, $BookingAsset->item);
    }

    /** @test */
    public function it_belongs_to_booking()
    {
        $booking       = factory(\App\Models\Booking::class)->create();
        $BookingAsset  = factory(\App\Models\BookingAsset::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(\App\Models\Booking::class, $BookingAsset->booking);
    }
}
