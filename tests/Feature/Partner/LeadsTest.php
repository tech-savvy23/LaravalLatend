<?php

namespace Tests\Feature\Partner;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\Common\Otp;
use App\Models\BookingService;
use App\Models\BookingAllottee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifyUserServiceStarted;
use App\Notifications\NotifyUserBookingAccepted;
use App\Notifications\NotifyUserBookingCancelled;
use App\Notifications\NotifyUserServiceCompleted;
use App\Notifications\NotifyPartnerServiceStarted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\NotifyPartnerBookingAccepted;
use App\Notifications\NotifyPartnerBookingCancelled;
use App\Notifications\NotifyPartnerServiceCompleted;
use App\Notifications\NotifyDeclineRescheduleRequest;
use App\Notifications\NotifyRescheduleBookingSuccess;

class LeadsTest extends TestCase
{
    use RefreshDatabase;

    public function setup():void
    {
        parent::setup();
        $this->partner = $this->create_partner(['latitude'=>'23.123', 'longitude'=>'23.123', 'type' => Partner::TYPE_AUDITOR, 'active' => true]);
        $this->actingAs($this->partner, 'partner');
    }

    /** @test */
    public function auditor_can_get_its_new_leads_which_are_not_prev_accepted()
    {
        //  auditor will only get bookings with status = 0
        $this->create_booking(['status'=>BOOKING::AUDITOR_ACCEPTED], 10);
        $booking = $this->create_booking(['status'=>Booking::STARTED], 10);
        $booking->each(function ($booking) {
            $this->create_payment(['booking_id' => $booking->id]);
        });
        // $this->create_booking_service(['booking_id'=> $booking[0]->id]);
        // $this->create_booking_space(['booking_id' => $booking[0]->id]);
        factory(BookingAllottee::class)->create(['booking_id'=>$booking[0]->id, 'allottee_id'=>$this->partner->id, 'allottee_type'=>get_class($this->partner)]);
        $res     = $this->getJson(route('partner.lead.new'))->json();
        $this->assertEquals(9, count($res['data']));
    }

    /** @test */
    public function auditor_can_get_its_new_leads_which_have_payment_done()
    {
        $auditor = $this->create_partner(['type' =>  Partner::TYPE_AUDITOR, 'longitude' => '23.123', 'latitude' => '23.123']);
        $this->actingAs($auditor, 'partner');

        $booking = $this->create_booking(['status'=>Booking::STARTED], 2);
        $this->create_payment(['booking_id' => $booking[0]->id]);
        $res     = $this->getJson(route('partner.lead.new'))->json();
        $this->assertEquals(1, count($res['data']));
    }

    /** @test */
    public function contractor_can_get_its_new_leads_which_have_payment_done()
    {
        $contractor = $this->create_partner(['type' =>  Partner::TYPE_CONTRACTOR, 'longitude' => '23.123', 'latitude' => '23.123']);
        $this->actingAs($contractor, 'partner');

        $this->create_booking(['status'=>Booking::STARTED], 2);
        $booking = $this->create_booking(['status'=>Booking::CONTRACTOR_REQUIRED], 2);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking[0]->id, 'allottee_id'=>$this->partner->id, 'allottee_type'=>get_class($this->partner)]);

        $this->create_payment(['booking_id' => $booking[0]->id]);
        $this->create_payment(['booking_id' => $booking[0]->id, 'partner_type' => Partner::TYPE_CONTRACTOR]);

        $res     = $this->getJson(route('partner.lead.new'))->json();
        $this->assertEquals(1, count($res['data']));
    }

    /** @test */
    public function contractor_can_get_its_new_leads_which_are_not_prev_accepted()
    {
        $contractor = $this->create_partner(['type' => Partner::TYPE_CONTRACTOR, 'longitude' => '23.123', 'latitude' => '23.123']);
        $this->actingAs($contractor, 'partner');

        // $this->create_booking(['status'=>Booking::CONTRACTOR_ACCEPTED], 10);

        $booking = factory(Booking::class, 10)->create(['status'=>BOOKING::CONTRACTOR_REQUIRED])->each(function ($booking) use ($contractor) {
            factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'status' => false]);
            $this->create_payment(['booking_id' => $booking->id, 'partner_type' => Partner::TYPE_CONTRACTOR]);
        });

        BookingAllottee::where('booking_id', $booking[0]->id)->update(['allottee_id'=>$contractor->id, 'allottee_type'=>get_class($contractor), 'status' => false]);

        $res = $this->getJson(route('partner.lead.new'))->json();
        $this->assertEquals(9, count($res['data']));
    }

    /** @test */
    public function partner_can_get_its_new_leads_which_are_nearby()
    {
        $this->withoutExceptionHandling();
        $user    = factory(User::class)->create();

        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id], 2);

        factory(Payment::class)->create(['status'=>0, 'booking_id'=> $booking[0]->id]);
        // login another auditor and can see prevkious released booking

        $partner = $this->create_partner(['type' => Partner::TYPE_AUDITOR, 'active'=>true, 'longitude' => '23.123', 'latitude' => '23.123']);
        $this->actingAs($partner, 'partner');
        $res = $this->getJson(route('partner.lead.new'))->json();
        $this->assertEquals(1, count($res['data']));
    }

    /** @test */
    public function partner_can_see_lead_details()
    {
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id], 10);
        $this->create_booking_service(['booking_id'=> $booking[0]->id]);
        $this->create_booking_space(['booking_id' => $booking[0]->id]);
        $res     = $this->getJson(route('partner.lead.details', $booking[0]->id))->json();
        $this->assertArrayHasKey('booking_space', $res);
    }

    /** @test */
    public function partner_can_accept_new_leads_and_customer_partner_get_notified()
    {
        Queue::fake();
        Notification::fake();
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id], 2);
        $res     = $this->postJson(route('partner.lead.accept', $booking[0]->id));
        $this->assertDatabaseHas('bookings', ['status' => 1, 'id' => $booking[0]->id]);
        $this->assertDatabaseHas('booking_allottees', ['allottee_id' => $this->partner->id]);
        $this->assertDatabaseHas('otps', ['model_id' => $user->id]);

        Notification::assertSentTo($user, NotifyUserBookingAccepted::class);
        Notification::assertSentTo($this->partner, NotifyPartnerBookingAccepted::class);
    }

    /** @test */
    public function partner_will_get_error_when_accepting_accepted_leads()
    {
        Notification::fake();
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id], 10);
        $res     = $this->postJson(route('partner.lead.accept', $booking[0]->id));

        $this->partner = $this->create_partner(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($this->partner, 'partner');

        $this->postJson(route('partner.lead.accept', $booking[0]->id))->assertStatus(404);
    }

    /** @test */
    public function partner_can_get_all_accepted_leads()
    {
        Notification::fake();
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id], 10);
        // Accepting first two leads
        $this->postJson(route('partner.lead.accept', $booking[0]->id));
        $this->postJson(route('partner.lead.accept', $booking[1]->id));

        $res = $this->getJson(route('partner.lead.accepted'))->json();
        $this->assertEquals(2, count($res['data']));

        $newAuditor = $this->create_partner(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($newAuditor, 'partner');

        $res = $this->getJson(route('partner.lead.accepted'))->json();
        $this->assertEquals(0, count($res['data']));
    }

    /** @test */
    public function partner_can_get_all_cancelled_leads()
    {
        Notification::fake();
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id], 10);
        // Accepting first two leads
        $this->postJson(route('partner.lead.accept', $booking[0]->id));
        $this->postJson(route('partner.lead.accept', $booking[1]->id));
        $this->deleteJson(route('partner.lead.cancel', $booking[0]->id));
        $res = $this->getJson(route('partner.lead.accepted'))->json();
        $this->assertEquals(1, count($res['data']));

        $res = $this->getJson(route('partner.lead.cancelled'))->json();
        $this->assertEquals(1, count($res['data']));
    }

    /** @test */
    public function partner_can_get_all_completed_leads()
    {
        Notification::fake();
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id], 10);
        // Accepting first two leads
        $this->postJson(route('partner.lead.accept', $booking[0]->id));
        $this->postJson(route('partner.lead.accept', $booking[1]->id));
        $this->postJson(route('partner.lead.submit', $booking[1]->id));

        $res = $this->getJson(route('partner.lead.accepted'))->json();
        $this->assertEquals(1, count($res['data']));

        $res = $this->getJson(route('partner.lead.completed'))->json();
        $this->assertEquals(1, count($res['data']));

        $newAuditor = $this->create_partner(['type' => Partner::TYPE_AUDITOR]);
        $this->actingAs($newAuditor, 'partner');

        $res = $this->getJson(route('partner.lead.completed'))->json();
        $this->assertEquals(0, count($res['data']));
    }

    /** @test */
    public function partner_can_cancel_new_leads()
    {
        Notification::fake();
        $user    = factory(User::class)->create();
        $this->create_booking(['status'=>1, 'user_id' => $user->id], 3);
        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id]);
        $res     = $this->postJson(route('partner.lead.accept', $booking->id));
        $res     = $this->deleteJson(route('partner.lead.cancel', $booking->id));
        $this->assertDatabaseHas('bookings', ['status' => 0, 'id' => $booking->id, 'otp_status'=>false]);
        $this->assertDatabaseHas('booking_allottees', ['allottee_id' => $this->partner->id, 'status'=>0]);
        $this->assertDatabaseMissing('otps', ['model_id' => $user->id]);
        // $this->assertDatabaseHas('notifications', ['notifiable_id' => $user->id]);
        Notification::assertSentTo($user, NotifyUserBookingCancelled::class);
        Notification::assertSentTo($this->partner, NotifyPartnerBookingCancelled::class);
    }

    /** @test */
    public function partner_cancel_lead_and_it_become_fresh()
    {
        Notification::fake();
        $user    = factory(User::class)->create();

        $booking = $this->create_booking(['status'=> 1, 'user_id' => $user->id], 3);

        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id], 2);

        $res     = $this->postJson(route('partner.lead.accept', $booking[0]->id));
        factory(Payment::class)->create(['status'=>0, 'booking_id'=> $booking[0]->id]);

        $res     = $this->postJson(route('partner.lead.accept', $booking[1]->id));
        factory(Payment::class)->create(['status'=>0, 'booking_id'=> $booking[1]->id]);

        $res     = $this->deleteJson(route('partner.lead.cancel', $booking[0]->id));

        // login another auditor and can see prevkious released booking

        $partner = $this->create_partner(['type' => Partner::TYPE_AUDITOR, 'active'=>true, 'longitude' => '23.123', 'latitude' => '23.123']);
        $this->actingAs($partner, 'partner');

        $res = $this->getJson(route('partner.lead.new'))->json();

        $this->assertEquals(1, count($res['data']));

        // $this->assertDatabaseHas('bookings', ['status' => 0, 'id' => $booking->id, 'otp_status'=>false]);
        // $this->assertDatabaseHas('booking_allottees', ['allottee_id' => $this->partner->id, 'status'=>0]);
        // $this->assertDatabaseMissing('otps', ['model_id' => $user->id]);
    }

    /** @test */
    public function partner_can_verify_otp()
    {
        Notification::fake();
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=> 0, 'user_id' => $user->id], 10);
        $res     = $this->postJson(route('partner.lead.accept', $booking[0]->id));

        $otp     = Otp::first();
        // dd($otp);
        $res     = $this->postJson(route('partner.otp.verify', $booking[0]->id), ['otp' => $otp->otp]);

        $this->assertDatabaseHas('bookings', ['status' => 1, 'id' => $booking[0]->id, 'otp_status'=>true]);
        $this->assertDatabaseMissing('otps', ['model_id' => $user->id]);
        // $this->assertDatabaseHas('notifications', ['notifiable_id' => $user->id]);
        Notification::assertSentTo($user, NotifyUserServiceStarted::class);
        Notification::assertSentTo($this->partner, NotifyPartnerServiceStarted::class);
    }

    /** @test */
    public function partner_submit_the_lead()
    {
        Notification::fake();
        $user    = factory(User::class)->create();
        $service = $this->create_service();
        $booking = $this->create_booking(['status'=> 0, 'user_id' => $user->id], 10);
        $service = factory(BookingService::class)->create(['booking_id' => $booking[0]->id, 'service_id' => $service->id]);
        factory(Payment::class)->create(['status'=>0, 'booking_id'=> $booking[0]->id]);
        $res     = $this->postJson(route('partner.lead.accept', $booking[0]->id));

        $otp     = Otp::first();
        // // dd($otp);
        $res     = $this->postJson(route('partner.otp.verify', $booking[0]->id), ['otp' => $otp->otp]);
        $res     = $this->postJson(route('partner.lead.cod-submit', $booking[0]->id));

        $this->assertDatabaseHas('bookings', ['status' => 2, 'id' => $booking[0]->fresh()->id, 'otp_status'=>true]);
        $this->assertDatabaseHas('payments', ['status' => 1]);
        Notification::assertSentTo($user, NotifyUserServiceCompleted::class);
        Notification::assertSentTo($this->partner, NotifyPartnerServiceCompleted::class);
        // $this->assertDatabaseHas('notifications', ['notifiable_id' => $user->id]);
    }

    /** @test */
    public function api_approve_reschedule_request_booking_time()
    {
        Notification::fake();

        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>Booking::AUDITOR_ACCEPTED, 'reschedule_status' => 1]);

        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);

        $reschedule_request = $this->create_reschedule_request([
            'booking_id'    => $booking,
            'date_time'     => Carbon::parse($booking->booking_time)->addDay()->format('Y-m-d h:i:s'),
            'allottee_id'   => $user->id,
            'allottee_type' => 'Client',
            'status'        => 1,
        ]);

        $this->postJson(route('approve.reschedule.request-by-partner', [$booking->id]))
            ->assertStatus(200);

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'reschedule_request' => 0, 'booking_time' => $reschedule_request->date_time]);
        $this->assertDatabaseHas('reschedule_requests', ['id' => $reschedule_request->id, 'status' => false]);

        Notification::assertSentTo($booking->user, NotifyRescheduleBookingSuccess::class);
    }

    /** @test */
    public function api_approve_reschedule_request_contractor_time()
    {
        Notification::fake();

        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_CONTRACTOR]);
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>Booking::CONTRACTOR_ACCEPTED, 'reschedule_status' => 1]);

        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>get_class($partner)]);

        $reschedule_request = $this->create_reschedule_request([
            'booking_id'    => $booking,
            'date_time'     => Carbon::parse($booking->booking_time)->addDay()->format('Y-m-d h:i:s'),
            'allottee_id'   => $user->id,
            'allottee_type' => 'Client',
            'status'        => 1,
        ]);

        $this->postJson(route('approve.reschedule.request-by-partner', [$booking->id]))
            ->assertStatus(200);

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'reschedule_request' => 0, 'contractor_time' => $reschedule_request->date_time]);
        $this->assertDatabaseHas('reschedule_requests', ['id' => $reschedule_request->id, 'status' => false]);

        Notification::assertSentTo($booking->user, NotifyRescheduleBookingSuccess::class);
    }

    /** @test */
    public function api_decline_reschedule_request_of_contractor_time()
    {
        Notification::fake();

        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_CONTRACTOR]);
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>Booking::CONTRACTOR_ACCEPTED, 'reschedule_status' => 1, 'user_id' => $user->id]);
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>$partner->type]);

        $reschedule_request = $this->create_reschedule_request([
            'booking_id'    => $booking,
            'date_time'     => Carbon::parse($booking->booking_time)->addDay()->format('Y-m-d h:i:s'),
            'allottee_id'   => $user->id,
            'allottee_type' => 'Client',
            'status'        => 1,
        ]);

        $this->postJson(route('decline.reschedule.request-by-partner', [$booking->id]))
            ->assertStatus(200);

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'reschedule_request' => false, 'contractor_time' => $booking->contractor_time]);
        $this->assertDatabaseHas('reschedule_requests', ['id' => $reschedule_request->id, 'status' => false]);

        Notification::assertSentTo($user, NotifyDeclineRescheduleRequest::class);
    }

    /** @test */
    public function api_decline_reschedule_request_of_booking_time()
    {
        Notification::fake();

        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>Booking::CONTRACTOR_ACCEPTED, 'reschedule_status' => 1, 'user_id' => $user->id]);
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$partner->id, 'allottee_type'=>$partner->type]);

        $reschedule_request = $this->create_reschedule_request([
            'booking_id'    => $booking,
            'date_time'     => Carbon::parse($booking->booking_time)->addDay()->format('Y-m-d h:i:s'),
            'allottee_id'   => $user->id,
            'allottee_type' => 'Client',
            'status'        => 1,
        ]);

        $this->postJson(route('decline.reschedule.request-by-partner', [$booking->id]))
        ->assertStatus(200);

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'reschedule_status' => false, 'contractor_time' => $booking->contractor_time]);
        $this->assertDatabaseHas('reschedule_requests', ['id' => $reschedule_request->id, 'status' => false]);

        Notification::assertSentTo($user, NotifyDeclineRescheduleRequest::class);
    }
}
