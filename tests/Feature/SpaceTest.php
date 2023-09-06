<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Space;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SpaceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_give_all_space()
    {
        $this->create_space();
        $this->getJson(route('space.index'))->assertOk();
    }

    /** @test */
    public function api_can_give_all_space_with_its_type()
    {
        $space = $this->create_space([], 2);
        $type  = $this->create_space_type(['space_id' => $space[0]->id], 10);
        $type  = $this->create_space_type(['space_id' => $space[1]->id], 10);
        // DB::enableQueryLog();
        $res   = $this->getJson(route('space.index'))->assertOk()->json();
        // dd(DB::getQueryLog());
        $this->assertArrayHasKey('type', $res['data'][0]);
        $this->assertNotNull($res['data'][0]['type']);
    }

    /** @test */
    public function api_can_give_single_space()
    {
        $space = $this->create_space();
        $this->getJson(route('space.show', $space->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_space()
    {
        $space = factory(Space::class)->make(['name' => 'Laravel']);
        $this->postJson(route('space.store'), $space->toArray())
            ->assertStatus(201);
        $this->assertDatabaseHas('Spaces', ['name' => 'Laravel']);
    }

    /** @test */
    public function api_can_update_space()
    {
        $space = $this->create_space();
        $this->putJson(route('space.update', $space->id), ['name' => 'UpdatedValue'])
            ->assertStatus(202);
        $this->assertDatabaseHas('Spaces', ['name' => 'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_space()
    {
        $space = $this->create_space();
        $this->deleteJson(route('space.destroy', $space->id))->assertStatus(204);
        $this->assertDatabaseMissing('Spaces', ['name' => $space->name]);
    }
}
