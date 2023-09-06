<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AssetAreaTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_many_item()
    {
        $AssetArea  = factory(\App\Models\AssetArea::class)->create();
        $item       = factory(\App\Models\AssetItem::class)->create(['area_id' => $AssetArea->id]);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $AssetArea->item);
    }
}
