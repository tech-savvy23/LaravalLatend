<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\BookingAllottee;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifyUserPartnerIsBlocked;
use App\Notifications\NotifyPartnerPartnerIsBlocked;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class InactivePartnerTest extends TestCase
{
    use DatabaseMigrations;

    public function setup():void
    {
        parent::setup();
        $this->partner = $this->create_partner(['type' => Partner::TYPE_AUDITOR, 'active'=>false, 'longitude' => '23.123', 'latitude' => '23.123']);
        $this->actingAs($this->partner, 'partner');
    }

    /** @test */
    public function auditor_can_not_see_new_leads_if_he_is_inactive()
    {
        $this->create_booking(['status'=>BOOKING::AUDITOR_ACCEPTED], 2);
        $booking = $this->create_booking(['status'=>Booking::STARTED], 2);
        $booking->each(function ($booking) {
            $this->create_payment(['booking_id' => $booking->id]);
        });
        factory(BookingAllottee::class)->create(['booking_id'=>$booking[0]->id, 'allottee_id'=>$this->partner->id, 'allottee_type'=>get_class($this->partner)]);

        $res     = $this->getJson(route('partner.lead.new', $this->partner->id))->json();
        $this->assertEquals(0, count($res['data']));

        $this->partner->update(['active' => true]);

        $res     = $this->getJson(route('partner.lead.new', $this->partner->id))->json();
        $this->assertEquals(1, count($res['data']));
    }

    /** @test */
    public function auditor_can_not_accept_new_lead_if_not_active()
    {
        Notification::fake();
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id], 2);
        $res     = $this->postJson(route('partner.lead.accept', $booking[0]->id))->json();
        $this->assertArrayHasKey('error', $res);
        $this->assertDatabaseMissing('bookings', ['status' => 1, 'id' => $booking[0]->id]);
        $this->assertDatabaseMissing('booking_allottees', ['allottee_id' => $this->partner->id]);
        $this->assertDatabaseMissing('otps', ['model_id' => $user->id]);
        $this->partner->update(['active'=>true]);
        $res     = $this->postJson(route('partner.lead.accept', $booking[0]->id));

        $this->assertDatabaseHas('bookings', ['status' => 1, 'id' => $booking[0]->id]);
        $this->assertDatabaseHas('booking_allottees', ['allottee_id' => $this->partner->id]);
        $this->assertDatabaseHas('otps', ['model_id' => $user->id]);
    }

    /** @test */
    public function when_admin_make_partner_inactive_then_all_its_ongoing_bookings_are_deleted()
    {
        $partner = $this->create_partner(['type' => Partner::TYPE_AUDITOR, 'active'=>true]);
        $this->actingAs($partner, 'partner');

        Notification::fake();
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id], 2);
        $multiple_checklist = $this->createMultipleChecklist();
        $this->create_bookingreport(['booking_id' => $booking[0]->id,  'multi_checklist_id' => $multiple_checklist->id]);
        $res     = $this->postJson(route('partner.lead.accept', $booking[0]->id));
        $this->assertDatabaseHas('booking_allottees', ['allottee_id' => $partner->id]);
        $this->assertDatabaseHas('booking_reports', ['booking_id' => $booking[0]->id]);

        $this->create_admin();
        $this->postJson(route('partner.block', $partner->id));
        $this->assertFalse(!!$partner->fresh()->active);

        $this->assertDatabaseMissing('booking_allottees', ['allottee_id' => $partner->id]);
        $this->assertDatabaseMissing('booking_reports', ['booking_id' => $booking[0]->id]);
        $this->assertEquals(0, $booking[0]->fresh()->status);

        Notification::assertSentTo($partner, NotifyPartnerPartnerIsBlocked::class);
        Notification::assertSentTo($booking[0]->user, NotifyUserPartnerIsBlocked::class);
    }

    /** @test */
    public function when_admin_make_partner_inactive_then_ongoing_booking_will_become_fresh()
    {
        // create auditor
        $partner = $this->create_partner(['type' => Partner::TYPE_AUDITOR, 'active'=>true, 'longitude' => '23.123', 'latitude' => '23.123']);
        $this->actingAs($partner, 'partner');

        Notification::fake();
        // create user and generate new booking
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id], 2);
        $this->create_payment(['booking_id' => $booking[0]->id]);
        $multiple_checklist = $this->createMultipleChecklist();
        $this->create_bookingreport(['booking_id' => $booking[0]->id,  'multi_checklist_id' => $multiple_checklist->id]);
        $this->assertEquals(0, $booking[0]->fresh()->status);

        // auditor accept booking
        $res     = $this->postJson(route('partner.lead.accept', $booking[0]->id));
        $this->assertDatabaseHas('booking_allottees', ['allottee_id' => $partner->id]);
        $this->assertDatabaseHas('booking_reports', ['booking_id' => $booking[0]->id]);

        // login admin and block auditor
        $this->create_admin();
        $this->postJson(route('partner.block', $partner->id));
        $this->assertFalse(!!$partner->fresh()->active);

        // login another auditor and can see previous released booking
        $partner = $this->create_partner(['type' => Partner::TYPE_AUDITOR, 'active'=>true, 'longitude' => '23.123', 'latitude' => '23.123']);
        $this->actingAs($partner, 'partner');

        $res = $this->getJson(route('partner.lead.new'))->json();
        $this->assertEquals(1, count($res['data']));
    }
}
