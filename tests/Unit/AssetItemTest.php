<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AssetItemTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_area()
    {
        $area       = factory(\App\Models\AssetArea::class)->create();
        $AssetItem  = factory(\App\Models\AssetItem::class)->create(['area_id' => $area->id]);
        $this->assertInstanceOf(\App\Models\AssetArea::class, $AssetItem->area);
    }

    /** @test */
    public function it_belongs_to_booking_asset()
    {
        $AssetItem     = factory(\App\Models\AssetItem::class)->create();
        $booking_asset = factory(\App\Models\BookingAsset::class)->create(['asset_item_id' => $AssetItem->id]);
        $this->assertInstanceOf(\App\Models\BookingAsset::class, $AssetItem->booking_asset[0]);
    }
}
