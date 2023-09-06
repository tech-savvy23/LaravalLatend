<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ChecklistType;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChecklistTypeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_give_all_checklisttype()
    {
        $checklist  = $this->create_checklist();
        $this->create_checklisttype(['checklist_id' => $checklist->id]);
        $this->create_checklisttype();
        $res = $this->getJson(route('checklisttype.index', $checklist->slug))->assertOk()->assertJsonStructure(['data']);
        $this->assertEquals(1, count($res->json()));
    }

    /** @test */
    // public function api_can_give_single_checklisttype()
    // {
    //     $checklisttype = $this->create_checklisttype();
    //     $this->getJson(route('checklisttype.show', $checklisttype->slug))->assertJsonStructure(['data']);
    // }

    /** @test */
    public function api_can_store_new_checklisttype()
    {
        $checklist     = $this->create_checklist();
        $checklisttype = factory(ChecklistType::class)->make([
            'title'=> 'Laravel', 'checklist_id' => $checklist->id,
        ]);
        $this->postJson(route('checklisttype.store', $checklist->slug), $checklisttype->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('checklist_types', ['title'=>'Laravel', 'checklist_id' => $checklist->id]);
    }

    /** @test */
    public function api_can_update_checklisttype()
    {
        $checklist     = $this->create_checklist();
        $checklisttype = $this->create_checklisttype(['checklist_id' => $checklist->id, ]);
        $this->putJson(route('checklisttype.update', ['checklist'=> $checklist->slug, 'type'=>$checklisttype->slug]), ['title'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Checklist_types', ['title'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_checklisttype()
    {
        $checklist     = $this->create_checklist();
        $checklisttype = $this->create_checklisttype(['checklist_id'=>$checklist->id]);
        $this->deleteJson(route('checklisttype.destroy', ['checklist'=> $checklist->slug, 'type'=> $checklisttype->slug]))->assertStatus(204);
        $this->assertDatabaseMissing('Checklist_types', ['title'=>$checklisttype->title]);
    }
}
