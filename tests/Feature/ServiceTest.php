<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_can_give_all_service()
    {
        $this->create_service();
        $this->create_area();
        $res = $this->getJson(route('service.index'))->assertOk()->json();
        $this->assertArrayHasKey('area', $res['data'][0]);
        $this->assertArrayHasKey('body', $res['data'][0]);
        $this->assertEquals(1, count($res['data']));
    }

    /** @test */
    public function api_can_give_single_service()
    {
        $service = $this->create_service();
        $this->getJson(route('service.show', $service->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_service()
    {
        $service = factory(Service::class)->make(['name' => 'Laravel']);
        $this->postJson(route('service.store'), $service->toArray())
            ->assertStatus(201);
        $this->assertDatabaseHas('Services', ['name' => 'Laravel']);
    }

    /** @test */
    public function api_can_update_service()
    {
        $service = $this->create_service();
        $this->putJson(route('service.update', $service->id), ['name' => 'UpdatedValue'])
            ->assertStatus(202);
        $this->assertDatabaseHas('Services', ['name' => 'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_service()
    {
        $service = $this->create_service();
        $this->deleteJson(route('service.destroy', $service->id))->assertStatus(204);
        $this->assertDatabaseMissing('Services', ['name' => $service->name]);
    }

    /** @test */
    public function api_can_not_delete_service_if_booing_exists_on_it()
    {
        $service = $this->create_service();
        $space   = $this->create_space();
        $booking = $this->create_booking();
        $booking->booking_service()->create([
            'service_id'      => $service->id,
            'service_type'    => $service->type,
            'sub_service_id'  => null,
        ]);

        $this->deleteJson(route('service.destroy', $service->id))->assertStatus(406);
        $this->assertDatabaseHas('Services', ['name' => $service->name]);
    }
}
