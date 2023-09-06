<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AssetArea;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetAreaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_can_give_all_assetarea()
    {
        $this->create_assetarea();
        $this->getJson(route('assetarea.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_assetarea()
    {
        $assetarea = $this->create_assetarea();
        $this->getJson(route('assetarea.show', $assetarea->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_assetarea()
    {
        $assetarea = factory(AssetArea::class)->make(['title'=>'Laravel']);
        $this->postJson(route('assetarea.store'), $assetarea->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Asset_areas', ['title'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_assetarea()
    {
        $assetarea = $this->create_assetarea();
        $this->putJson(route('assetarea.update', $assetarea->id), ['title'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Asset_areas', ['title'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_assetarea()
    {
        $assetarea = $this->create_assetarea();
        $this->deleteJson(route('assetarea.destroy', $assetarea->id))->assertStatus(204);
        $this->assertDatabaseMissing('Asset_areas', ['title'=>$assetarea->title]);
    }
}
