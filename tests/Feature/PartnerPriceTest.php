<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PartnerPrice;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PartnerPriceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_give_all_partnerprice()
    {
        $this->create_partnerprice();
        $this->getJson(route('partnerprice.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_partnerprice()
    {
        $partnerprice = $this->create_partnerprice();
        $this->getJson(route('partnerprice.show', $partnerprice->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_partnerprice()
    {
        $partnerprice = factory(PartnerPrice::class)->make(['state_id'=>'Laravel']);
        $this->postJson(route('partnerprice.store'), $partnerprice->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('partner_prices', ['state_id'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_partnerprice()
    {
        $partnerprice = $this->create_partnerprice();
        $this->putJson(route('partnerprice.update', $partnerprice->id), ['state_id'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('partner_prices', ['state_id'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_partnerprice()
    {
        $partnerprice = $this->create_partnerprice();
        $this->deleteJson(route('partnerprice.destroy', $partnerprice->id))->assertStatus(204);
        $this->assertDatabaseMissing('partner_prices', ['state_id'=>$partnerprice->state_id]);
    }
}
