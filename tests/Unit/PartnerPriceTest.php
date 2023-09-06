<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PartnerPriceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_state()
    {
        $state         = factory(\App\Models\State::class)->create();
        $PartnerPrice  = factory(\App\Models\PartnerPrice::class)->create(['state_id' => $state->id]);
        $this->assertInstanceOf(\App\Models\State::class, $PartnerPrice->state);
    }

    /** @test */
    public function it_belongs_to_city()
    {
        $city          = factory(\App\Models\City::class)->create();
        $PartnerPrice  = factory(\App\Models\PartnerPrice::class)->create(['city_id' => $city->id]);
        $this->assertInstanceOf(\App\Models\City::class, $PartnerPrice->city);
    }

}
