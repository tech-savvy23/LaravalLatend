<?php

namespace Tests\Feature;

use App\Models\Address;
use Tests\TestCase;
use App\Models\Aaddress;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends TestCase
{
    use RefreshDatabase;


    public function setup():void
    {
        parent::setup();
        $this->user = $this->authUser();
    }

    /** @test */
    public function api_can_give_all_address()
    {
        $this->create_address();
        $this->getJson(route('address.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_address()
    {
        $address = $this->create_address();
        $this->getJson(route('address.show', $address->id))->assertJsonStructure(['data']);

    }

    /** @test */
    public function api_can_store_new_address()
    {
        $address = factory(Address::class)->make(['pin' => 'Laravel']);
        $this->postJson(route('address.store'), $address->toArray())
            ->assertStatus(201);
        $this->assertDatabaseHas('Addresses', ['pin' => 'Laravel']);

    }

    /** @test */
    public function api_can_update_address()
    {
        $address = $this->create_address();
        $this->putJson(route('address.update', $address->id), ['pin' => 'UpdatedValue'])
            ->assertStatus(202);
        $this->assertDatabaseHas('Addresses', ['pin' => 'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_address()
    {
        $address = $this->create_address();
        $this->deleteJson(route('address.destroy', $address->id))->assertStatus(204);
        $this->assertDatabaseMissing('Addresses', ['pin' => $address->pin]);
    }

    /** @test */
    public function api_can_give_all_address_of_single_user()
    {
        $this->create_address(['user_id' => $this->user->id], 2);
        $this->getJson(route('user.address.index'))->assertOk()->assertJsonStructure(['data']);
    }

}
