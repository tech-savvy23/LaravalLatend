<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Checklist;
use App\Models\ChecklistType;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChecklistTypeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_checklist()
    {
        $checklist      = factory(Checklist::class)->create();
        $ChecklistType  = factory(ChecklistType::class)->create(['checklist_id' => $checklist->id]);
        $this->assertInstanceOf(Checklist::class, $ChecklistType->checklist);
    }
}
