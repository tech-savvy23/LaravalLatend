<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AreaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_can_give_all_area()
    {
        $this->create_area();
        $this->getJson(route('area.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_area()
    {
        $area = $this->create_area();
        $this->getJson(route('area.show', $area->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_area()
    {
        $area = factory(Area::class)->make(['type'=>'Laravel']);
        $this->postJson(route('area.store'), $area->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Areas', ['type'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_area()
    {
        $area = $this->create_area();
        $this->putJson(route('area.update', $area->id), ['type'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Areas', ['type'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_area()
    {
        $area = $this->create_area();
        $this->deleteJson(route('area.destroy', $area->id))->assertStatus(204);
        $this->assertDatabaseMissing('Areas', ['type'=>$area->type]);
    }
}
