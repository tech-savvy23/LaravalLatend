<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Space;
use App\Models\SpaceType;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Space_typeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_space()
    {
        $space       = factory(Space::class)->create();
        $SpaceType   = factory(SpaceType::class)->create(['space_id' => $space->id]);
        $this->assertInstanceOf(Space::class, $SpaceType->space);
    }
}
