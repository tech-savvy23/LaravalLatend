<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Checklist;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChecklistTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_give_all_checklist()
    {
        $this->create_checklist();
        $this->getJson(route('checklist.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_checklist()
    {
        $checklist = $this->create_checklist();
        $this->getJson(route('checklist.show', $checklist->slug))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_checklist()
    {
        $checklist = factory(Checklist::class)->make(['title'=>'Laravel']);
        $this->postJson(route('checklist.store'), $checklist->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Checklists', ['title'=>'Laravel']);
    }

    /** @test */
    public function api_can_validate_title_field_on_save()
    {
        $this->withExceptionHandling();
        $checklist = factory(Checklist::class)->make(['title'=>'Laravel']);
        $res       = $this->post(route('checklist.store'));
        $res->assertSessionHasErrors('title');
        $this->assertDatabaseMissing('Checklists', ['title'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_checklist()
    {
        $checklist = $this->create_checklist();
        $this->putJson(route('checklist.update', $checklist->slug), ['title'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Checklists', ['title'=>'Updatedvalue']);
    }

    /** @test */
    public function api_can_delete_checklist()
    {
        $checklist = $this->create_checklist();
        $this->deleteJson(route('checklist.destroy', $checklist->slug))->assertStatus(204);
        $this->assertDatabaseMissing('Checklists', ['title'=>$checklist->title]);
    }
}
