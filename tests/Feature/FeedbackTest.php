<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Feedback;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FeedbackTest extends TestCase
{
    use DatabaseMigrations;

    public function create_feedback($args = [], $num = null)
    {
        $booking = $this->create_booking_service_space();
        $args    = array_merge(['booking_id' => $booking->id], $args);
        return factory(Feedback::class, $num)->create($args);
    }

    protected function create_booking_service_space()
    {
        $booking         = $this->create_booking();
        $service         = $this->create_service();
        $this->create_booking_service(['service_id' => $service->id, 'booking_id' => $booking->id]);
        $space = $this->create_space();
        $this->create_booking_space(['space_id' => $space->id, 'booking_id' => $booking->id]);
        return $booking;
    }

    /** @test */
    public function api_can_give_all_feedback_for_loggedIn_user()
    {
        $this->authUser();
        $this->create_feedback(['user_id' => auth()->id()]);
        $res = $this->getJson(route('feedback.index'))->assertOk()->assertJsonStructure(['data'])->json();
        $this->assertEquals(1, count($res['data']));
    }

    /** @test */
    public function api_can_give_all_feedback_for_partner()
    {
        $partner = $this->create_partner();
        $this->actingAs($partner, 'partner');
        $this->create_feedback(['partner_id' => $partner->id]);
        $res = $this->getJson(route('feedback.partner'))->assertOk()->assertJsonStructure(['data'])->json();
        $this->assertEquals(1, count($res['data']));
    }

    /** @test */
    public function api_can_give_single_feedback()
    {
        $feedback = $this->create_feedback();
        $res      = $this->getJson(route('feedback.show', $feedback->id))->json();
        $this->assertArrayHasKey('data', $res);
    }

    /** @test */
    public function api_can_store_new_feedback()
    {
        $booking  = $this->create_booking_service_space();
        $feedback = factory(Feedback::class)->make(['booking_id'=>$booking->id]);
        $res      = $this->postJson(route('feedback.store'), $feedback->toArray())
        ->assertStatus(201)->json();
        $this->assertDatabaseHas('Feedback', ['booking_id'=>$booking->id]);
    }

    /** @test */
    public function api_can_update_feedback()
    {
        $feedback = $this->create_feedback(['rating' => 2]);
        $this->putJson(route('feedback.update', $feedback->id), ['rating'=>3])
        ->assertStatus(202);
        $this->assertDatabaseHas('Feedback', ['rating'=>3]);
    }

    /** @test */
    public function api_can_delete_feedback()
    {
        $feedback = $this->create_feedback();
        $this->deleteJson(route('feedback.destroy', $feedback->id))->assertStatus(204);
        $this->assertDatabaseMissing('Feedback', ['booking_id'=>$feedback->booking_id]);
    }
}
