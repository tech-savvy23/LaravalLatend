<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Report;
use App\Models\Checklist;
use App\Models\ChecklistType;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChecklistTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_many_reports()
    {
        $Checklist  = factory(Checklist::class)->create();
        $reports    = factory(Report::class)->create(['Checklist_id' => $Checklist->id]);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $Checklist->reports);
    }

    /** @test */
    public function it_has_many_types()
    {
        $Checklist  = factory(Checklist::class)->create();
        $types      = factory(ChecklistType::class)->create(['Checklist_id' => $Checklist->id]);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $Checklist->types);
    }
}
