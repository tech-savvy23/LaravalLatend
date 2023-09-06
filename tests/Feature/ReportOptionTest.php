<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ReportOption;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportOptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_can_give_all_reportoption()
    {
        $this->create_reportoption();
        $this->getJson(route('reportoption.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_reportoption()
    {
        $reportoption = $this->create_reportoption();
        $this->getJson(route('reportoption.show', $reportoption->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_reportoption()
    {
        $reportoption = factory(ReportOption::class)->make(['title'=>'Laravel']);
        $this->postJson(route('reportoption.store'), $reportoption->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Report_options', ['title'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_reportoption()
    {
        $reportoption = $this->create_reportoption();
        $this->putJson(route('reportoption.update', $reportoption->id), ['title'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Report_options', ['title'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_reportoption()
    {
        $reportoption = $this->create_reportoption();
        $this->deleteJson(route('reportoption.destroy', $reportoption->id))->assertStatus(204);
        $this->assertDatabaseMissing('Report_options', ['title'=>$reportoption->title]);
    }
}
