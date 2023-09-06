<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BookingAsset;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookingAssetTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_give_all_bookingasset()
    {
        $this->create_bookingasset();
        $this->getJson(route('bookingasset.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_bookingasset()
    {
        $bookingasset = $this->create_bookingasset();
        $this->getJson(route('bookingasset.show', $bookingasset->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_bookingasset()
    {
        $bookingasset = factory(BookingAsset::class)->make(['asset_item_id'=>'Laravel']);
        $this->postJson(route('bookingasset.store'), $bookingasset->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Booking_assets', ['asset_item_id'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_bookingasset()
    {
        $bookingasset = $this->create_bookingasset();
        $this->putJson(route('bookingasset.update', $bookingasset->id), ['asset_item_id'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Booking_assets', ['asset_item_id'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_bookingasset()
    {
        $bookingasset = $this->create_bookingasset();
        $this->deleteJson(route('bookingasset.destroy', $bookingasset->id))->assertStatus(204);
        $this->assertDatabaseMissing('Booking_assets', ['asset_item_id'=>$bookingasset->asset_item_id]);
    }
}
