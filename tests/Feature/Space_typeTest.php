<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Media;
use App\Models\SpaceType;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Space_typeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_can_give_all_space_type()
    {
        $this->create_space_type();
        $this->getJson(route('space_type.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_space_type()
    {
        $space_type = $this->create_space_type();
        $this->getJson(route('space_type.show', $space_type->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_space_type()
    {
        $space_type = factory(SpaceType::class)->make(['space_id' => 'Laravel']);
        $this->postJson(route('space_type.store'), $space_type->toArray())
            ->assertStatus(201);
        $this->assertDatabaseHas('Space_types', ['space_id' => 'Laravel']);
    }

    /** @test */
    public function api_can_store_new_space_type_with_thumbnail()
    {
        $image            = \Illuminate\Http\Testing\File::image('image.jpg');
        $image            = base64_encode(file_get_contents($image));
        $space            = $this->create_space();
        $this->postJson(route('space_type.store'), [
            'name'      => 'laravel',
            'thumbnail' => $image,
            'space_id'  => $space->id,
            'value' => '123',
        ])->assertStatus(201);
        array_map('unlink', glob(storage_path('app/public/*.*')));
        $this->assertDatabaseHas('Space_types', ['name' => 'laravel']);
    }

    /** @test */
    public function api_can_update_new_space_type_with_thumbnail()
    {
        $image            = \Illuminate\Http\Testing\File::image('image.jpg');
        $image            = base64_encode(file_get_contents($image));
        $space            = $this->create_space();
        $space_type       = $this->create_space_type(['space_id'=>$space->id]);
        factory(Media::class)->create(['model_id'=>$space_type->id, 'model_type'=>get_class($space_type)]);
        $this->putJson(route('space_type.update', $space_type->id), [
            'name'      => 'laravel',
            'thumbnail' => $image,
            'space_id'  => $space->id,
            'value' => '123',
        ])->assertStatus(202);
        array_map('unlink', glob(storage_path('app/public/*.*')));
        $this->assertDatabaseHas('media', ['model_id'=>1]);
        $this->assertDatabaseHas('Space_types', ['name' => 'laravel', 'value' => '123']);
    }

    /** @test */
    public function api_can_update_space_type()
    {
        $space_type = $this->create_space_type();
        $this->putJson(route('space_type.update', $space_type->id), ['space_id' => 'UpdatedValue'])
            ->assertStatus(202);
        $this->assertDatabaseHas('Space_types', ['space_id' => 'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_space_type()
    {
        $space_type = $this->create_space_type();
        $this->deleteJson(route('space_type.destroy', $space_type->id))->assertStatus(204);
        $this->assertDatabaseMissing('Space_types', ['space_id' => $space_type->space_id]);
    }
}
