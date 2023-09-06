<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\City;
use App\Models\State;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_state()
    {
        $state    = factory(State::class)->create();
        $Product  = factory(Product::class)->create(['state_id' => $state->id]);
        $this->assertInstanceOf(State::class, $Product->state);
    }

    /** @test */
    public function it_belongs_to_city()
    {
        $city        = factory(City::class)->create();
        $Product     = factory(Product::class)->create(['city_id' => $city->id]);
        $this->assertInstanceOf(city::class, $Product->city);
    }
}
