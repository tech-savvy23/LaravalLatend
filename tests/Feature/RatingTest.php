<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Rating;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RatingTest extends TestCase
{
    use DatabaseMigrations;

    public function create_rating($args = [], $num = null)
    {
        return factory(Rating::class, $num)->create($args);
    }

    /** @test */
    public function api_can_give_all_rating()
    {
        $this->create_rating();
        $this->getJson(route('rating.index'))->assertOk()->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_give_single_rating()
    {
        $rating = $this->create_rating();
        $this->getJson(route('rating.show', $rating->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_rating()
    {
        $rating = factory(Rating::class)->make(['booking_id'=>'Laravel']);
        $this->postJson(route('rating.store'), $rating->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('Ratings', ['booking_id'=>'Laravel']);
    }

    /** @test */
    public function api_can_update_rating()
    {
        $rating = $this->create_rating();
        $this->putJson(route('rating.update', $rating->id), ['booking_id'=>'UpdatedValue'])
        ->assertStatus(202);
        $this->assertDatabaseHas('Ratings', ['booking_id'=>'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_rating()
    {
        $rating = $this->create_rating();
        $this->deleteJson(route('rating.destroy', $rating->id))->assertStatus(204);
        $this->assertDatabaseMissing('Ratings', ['booking_id'=>$rating->booking_id]);
    }
}
