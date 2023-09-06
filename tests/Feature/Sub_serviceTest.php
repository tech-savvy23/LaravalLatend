<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SubService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Sub_serviceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_can_give_all_sub_service()
    {
        $this->create_sub_service();
        $this->getJson(route('sub_service.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_sub_service()
    {
        $sub_service = $this->create_sub_service();
        $this->getJson(route('sub_service.show', $sub_service->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_sub_service()
    {
        $sub_service = factory(SubService::class)->make(['service_id'=>'Laravel']);
        $this->postJson(route('sub_service.store'), $sub_service->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Sub_services', ['service_id'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_sub_service()
    {
        $sub_service = $this->create_sub_service();
        $this->putJson(route('sub_service.update', $sub_service->id), ['service_id'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Sub_services', ['service_id'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_sub_service()
    {
        $sub_service = $this->create_sub_service();
        $this->deleteJson(route('sub_service.destroy', $sub_service->id))->assertStatus(204);
        $this->assertDatabaseMissing('Sub_services', ['service_id'=>$sub_service->service_id]);
    }
}
