<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_can_search_product_from_description()
    {
        $this->create_product(['description' => 'laravel']);
        $this->create_product(['description' => 'vuejs']);
        $this->create_product(['description' => 'react', 'active' => false]);
        // dd(route('product.search', [
        //     'filter[description]' => 'la',
        // ]));
        $res = $this->get(route('product.search', [
            // 'filter[description]' => '',
        ]));
        $res->assertStatus(200);
    }
}
