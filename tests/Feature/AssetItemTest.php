<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AssetItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_can_give_all_assetitem()
    {
        $this->create_assetitem();
        $this->getJson(route('assetitem.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_assetitem()
    {
        $assetitem = $this->create_assetitem();
        $this->getJson(route('assetitem.show', $assetitem->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_assetitem()
    {
        $assetitem = factory(AssetItem::class)->make(['area_id'=>'Laravel']);
        $this->postJson(route('assetitem.store'), $assetitem->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Asset_items', ['area_id'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_assetitem()
    {
        $assetitem = $this->create_assetitem();
        $this->putJson(route('assetitem.update', $assetitem->id), ['area_id'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Asset_items', ['area_id'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_assetitem()
    {
        $assetitem = $this->create_assetitem();
        $this->deleteJson(route('assetitem.destroy', $assetitem->id))->assertStatus(204);
        $this->assertDatabaseMissing('Asset_items', ['area_id'=>$assetitem->area_id]);
    }
}
