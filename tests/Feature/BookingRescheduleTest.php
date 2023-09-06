<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\BookingAllottee;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifyUserBookingReschedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\NotifyPartnerBookingReschedule;

class BookingRescheduleTest extends TestCase
{
    use RefreshDatabase;

    public function setup():void
    {
        parent::setup();
        $this->partner = $this->create_partner(['latitude'=>'19.0760', 'longitude'=>'72.8777', 'type' => Partner::TYPE_AUDITOR, 'active' => true]);
        $this->actingAs($this->partner, 'partner');

        $this->user = $this->create_user();
        $this->actingAs($this->user, 'api');
    }

    /** @test */
    public function api_date_time_is_before_today_can_show_an_error_in_send_reschedule_request_to_user()
    {
        $user       = factory(User::class)->create();
        $booking    = $this->create_booking(['status'=>Booking::STARTED, 'user_id' => $user->id]);
        $space_type = $this->create_space_type();
        $space      = $this->create_space();
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id, 'space_id' => $space->id, 'space_type_id' => $space_type->id]);

        $booking->each(function ($booking) {
            $this->create_payment(['booking_id' => $booking->id]);
        });
        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$this->partner->id, 'allottee_type'=>get_class($this->partner)]);

        $reason = $this->create_reschedule_reason();

        Notification::fake();
        $data = [
            'reason'    => $reason->id,
            'date_time' => Carbon::now()->subDays(2),
        ];
        $this->postJson(route('send.reschedule.request', [$booking->id]), $data)
            ->assertStatus(422)
            ->assertJson(['errors' => [
                'date_time' => ['The date time must be a date after yesterday.'],
            ]]);
    }

    /** @test */
    public function api_partner_send_reschedule_request_to_user()
    {
        $user       = factory(User::class)->create();
        $booking    = $this->create_booking(['status'=>Booking::STARTED, 'user_id' => $user->id]);
        $space_type = $this->create_space_type();
        $space      = $this->create_space();
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id, 'space_id' => $space->id, 'space_type_id' => $space_type->id]);

        $booking->each(function ($booking) {
            $this->create_payment(['booking_id' => $booking->id]);
        });
        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$this->partner->id, 'allottee_type'=>get_class($this->partner)]);

        $reason = $this->create_reschedule_reason();

        Notification::fake();
        $data = [
            'reason'    => $reason->id,
            'date_time' => Carbon::now(),
        ];
        $this->postJson(route('send.reschedule.request', [$booking->id]), $data)->assertOk()->assertStatus(200);
        Notification::assertSentTo($user, NotifyUserBookingReschedule::class);
        $this->assertDatabaseHas('reschedule_requests', ['booking_id' => $booking->id]);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'reschedule_status' => 1]);
    }

    /** @test */
    public function api_date_time_is_before_today_can_show_an_error_in_send_reschedule_request_to_partner()
    {
        $booking = $this->create_booking(['status'=>Booking::STARTED, 'user_id' => $this->user->id]);
        $booking->each(function ($booking) {
            $this->create_payment(['booking_id' => $booking->id]);
        });
        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$this->partner->id, 'allottee_type'=>get_class($this->partner)]);

        $reason = $this->create_reschedule_reason();

        Notification::fake();
        $data = [
            'reason'    => $reason->id,
            'date_time' => Carbon::now()->subDays(2),
        ];
        $this->postJson(route('send.reschedule.request-to-partner', [$booking->id]), $data)
             ->assertStatus(422)
             ->assertJson(['errors' => [
                 'date_time' => ['The date time must be a date after yesterday.'],
             ]]);
    }

    /** @test */
    public function api_send_reschedule_request_to_partner()
    {
        $space_type = $this->create_space_type();
        $space      = $this->create_space();
        $booking    = $this->create_booking(['status'=>Booking::STARTED, 'user_id' => $this->user->id]);
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id, 'space_id' => $space->id, 'space_type_id' => $space_type->id]);

        $booking->each(function ($booking) {
            $this->create_payment(['booking_id' => $booking->id]);
        });

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$this->partner->id, 'allottee_type'=>get_class($this->partner)]);
        $reason = $this->create_reschedule_reason();

        Notification::fake();
        $data = [
            'reason'    => $reason->id,
            'date_time' => Carbon::now(),
        ];

        $this->postJson(route('send.reschedule.request-to-partner', [$booking->id]), $data)->assertOk()->assertStatus(200);

        Notification::assertSentTo($this->partner, NotifyPartnerBookingReschedule::class);
        $this->assertDatabaseHas('reschedule_requests', ['booking_id' => $booking->id]);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'reschedule_status' => 1]);
    }

    /** @test */
    public function add_booking_reschedule_status_in_booking_resource()
    {
        $space_type = $this->create_space_type();
        $space      = $this->create_space();
        $booking    = $this->create_booking(['status'=>Booking::STARTED, 'user_id' => $this->user->id]);
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id, 'space_id' => $space->id, 'space_type_id' => $space_type->id]);

        $booking->each(function ($booking) {
            $this->create_payment(['booking_id' => $booking->id]);
        });

        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id' => $this->partner->id, 'allottee_type' => get_class($this->partner)]);
        $reason = $this->create_reschedule_reason();

        Notification::fake();
        $data = [
            'reason'    => $reason->id,
            'date_time' => Carbon::now(),
        ];

        $response = $this->postJson(route('send.reschedule.request-to-partner', [$booking->id]), $data)->assertOk()->assertStatus(200);
        $response->assertJson([
            'data' => [
                'reschedule_status' => 1,
            ],
        ]) ;
    }

    /** @test */
    public function add_booking_reschedule_status_in_lead_resource()
    {
        $user       = factory(User::class)->create();
        $booking    = $this->create_booking(['status'=>Booking::STARTED, 'user_id' => $user->id]);
        $space_type = $this->create_space_type();
        $space      = $this->create_space();
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id, 'space_id' => $space->id, 'space_type_id' => $space_type->id]);

        $booking->each(function ($booking) {
            $this->create_payment(['booking_id' => $booking->id]);
        });
        factory(BookingAllottee::class)->create(['booking_id'=>$booking->id, 'allottee_id'=>$this->partner->id, 'allottee_type'=>get_class($this->partner)]);

        $reason = $this->create_reschedule_reason();

        Notification::fake();
        $data = [
            'reason'    => $reason->id,
            'date_time' => Carbon::now(),
        ];
        $response = $this->postJson(route('send.reschedule.request', [$booking->id]), $data)->assertOk()->assertStatus(200)->json();
        Notification::assertSentTo($user, NotifyUserBookingReschedule::class);
        $this->assertDatabaseHas('reschedule_requests', ['booking_id' => $booking->id]);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'reschedule_status' => 1]);
        $this->assertEquals(1, $response['data']['reschedule_status']);
    }
}
