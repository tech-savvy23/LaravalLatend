<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_give_all_product()
    {
        $this->create_product();
        $this->getJson(route('product.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_product()
    {
        $product = $this->create_product();
        $this->getJson(route('product.show', $product->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_product()
    {
        $product = factory(Product::class)->make();
        $this->postJson(route('product.store'), $product->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Products', ['description'=>$product->description]);
    }

    /** @test */
    public function api_can_update_product()
    {
        $product = $this->create_product();
        $this->putJson(route('product.update', $product->id), ['description'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Products', ['description'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_product()
    {
        $product = $this->create_product();
        $this->deleteJson(route('product.destroy', $product->id))->assertStatus(204);
        $this->assertDatabaseMissing('Products', ['description'=>$product->description]);
    }
}
