<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Address;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\BookingAllottee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifyUserBookingConfirm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\NotifyDeclineRescheduleRequest;
use App\Notifications\NotifyRescheduleBookingSuccess;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();
        $this->user = $this->authUser();
    }

    /** @test */
    public function api_can_give_all_booking_for_loggedin_partner()
    {
        $booking = $this->create_booking([], 10);
        $partner = $this->create_partner(['type' => Partner::TYPE_AUDITOR]);
        factory(BookingAllottee::class)->create([
            'allottee_id' => $partner->id,
            'allottee_type' => Partner::TYPE_AUDITOR,
            'booking_id' => $booking[0]->id,]);
        // DB::connection()->enableQueryLog();
        $res = $this->getJson(route('booking.index'))->assertOk();
        // dd($res->json());
        // dd(DB::getQueryLog());
    }

    /** @test */
    public function api_can_give_all_booking_for_admin()
    {
        $this->create_admin();
        $booking = $this->create_booking();

        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        $res = $this->getJson(route('booking.all'))->assertOk()->json();
        $this->assertEquals(1, count($res['data']));
    }

    /** @test */
    public function api_can_give_all_booking_for_admin_for_xls()
    {
        $this->create_admin();
        $booking = $this->create_booking();

        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        $res = $this->getJson(route('booking.all.xlsx'))->assertOk()->json();
        $this->assertEquals(1, count($res['data']));
    }

    /** @test */
    public function api_can_give_single_booking()
    {
        $booking = $this->create_booking();
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);
        $this->getJson(route('booking.show', $booking->id))->assertJsonStructure(['data']);
    }

    /** @test */
    public function api_can_store_new_booking_with_space_service_address()
    {
        Notification::fake();
        $booking = factory(Booking::class)->make(['address_id' => 44]);
        $address = factory(Address::class)->make(['address_id' => 44]);
        $space_type = $this->create_space_type();
        $space = $this->create_space();
        $service = $this->create_service();
        $subservice = $this->create_sub_service();
        $area = $this->create_area();
        // dd(Carbon::now()->format('Y-h-m H:I:s'));
        $res = $this->postJson(route('booking.store'), [
            'space_id' => $space->id,
            'space_type_id' => $space_type->id,
            'service_id' => $service->id,
            'sub_service_id' => $subservice->id,
            'booking_time' => '2019-11-11 10:00:00',
            'area_id' => $area->id,
            'area_number' => 2,
            'address' => [
                'city' => 'andromeda',
                'state' => 'krypton',
                'body' => 'Tooti hui building',
                'landmark' => 'inside kabristaan',
                'latitude' => '127.0.0.1',
                'longitude' => '127.0.0.1',
                'pin' => '8080',
            ],
        ])->assertStatus(201)->json();

        $this->assertDatabaseHas('addresses', ['user_id' => auth()->id()]);
        $this->assertDatabaseHas('bookings', ['area_id' => $area->id]);
        $this->assertDatabaseHas('booking_spaces', ['space_id' => $space->id, 'space_type_id' => $space_type->id]);
        $this->assertDatabaseHas('booking_services', ['service_id' => $service->id, 'sub_service_id' => $subservice->id]);
    }

    /** @test */
    public function api_can_update_booking()
    {
        $booking = $this->create_booking();
        $this->putJson(route('booking.update', $booking->id), ['address_id' => 'UpdatedValue'])
            ->assertStatus(202);
        $this->assertDatabaseHas('Bookings', ['address_id' => 'UpdatedValue']);
    }

    /** @test */
    public function api_can_delete_booking()
    {
        $booking = $this->create_booking();
        $this->deleteJson(route('booking.destroy', $booking->id))->assertStatus(204);
        $this->assertDatabaseMissing('Bookings', ['address_id' => $booking->address_id]);
    }

    /** @test */
    public function api_can_ask_for_contractor_required()
    {
        $booking = $this->create_booking();
        $this->postJson(route('booking.contractor-required', $booking->id))->assertStatus(202);
        $this->assertDatabaseHas('Bookings', ['status' => Booking::CONTRACTOR_REQUIRED]);
    }

    /** @test */
    public function api_booking_time_is_required_update_booking_date()
    {
        $booking = $this->create_booking();
        $data = [
            'booking_time' => '',
        ];
        $this->postJson(route('update.booking.date', [$booking->id]), $data)
            ->assertStatus(422)
            ->assertJson(['errors' => [
                'booking_time' => ['The booking time field is required.'],
            ]]);
    }

    /** @test */
    public function api_booking_date_is_before_today_can_update_booking_date()
    {
        $booking = $this->create_booking();
        $data = [
            'booking_time' => Carbon::now()->subDays(2),
        ];
        $this->postJson(route('update.booking.date', [$booking->id]), $data)
            ->assertStatus(422)
            ->assertJson(['errors' => [
                'booking_time' => ['The booking time must be a date after yesterday.'],
            ]]);
    }

    /** @test */
    public function api_user_can_update_booking_date()
    {
        Notification::fake();

        $partner = factory(Partner::class)->create();
        $booking = $this->create_booking(['status' => Booking::STARTED]);
        factory(BookingAllottee::class)->create(['booking_id' => $booking->id, 'allottee_id' => $partner->id, 'allottee_type' => get_class($partner)]);

        $data = [
            'booking_time' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
        ];
        $this->postJson(route('update.booking.date', [$booking->id]), $data)
            ->assertStatus(200);
        $this->assertDatabaseHas('bookings', ['booking_time' => $data['booking_time']]);
        Notification::assertSentTo($booking->user, NotifyRescheduleBookingSuccess::class);
    }

    /** @test */
    public function api_user_can_update_contractor_date()
    {
        Notification::fake();

        $partner = factory(Partner::class)->create();
        $booking = $this->create_booking(['status' => Booking::CONTRACTOR_ACCEPTED]);
        factory(BookingAllottee::class)->create(['booking_id' => $booking->id, 'allottee_id' => $partner->id, 'allottee_type' => get_class($partner)]);

        $data = [
            'booking_time' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
        ];
        $this->postJson(route('update.booking.date', [$booking->id]), $data)
            ->assertStatus(200);
        $this->assertDatabaseHas('bookings', ['contractor_time' => $data['booking_time']]);
        Notification::assertSentTo($booking->user, NotifyRescheduleBookingSuccess::class);
    }

    /** @test */
    public function api_can_store_new_booking_with_space_service_address_and_send_email()
    {
        Notification::fake();
        $booking = factory(Booking::class)->make(['address_id' => 44]);
        $address = factory(Address::class)->make(['address_id' => 44]);
        $space_type = $this->create_space_type();
        $space = $this->create_space();
        $service = $this->create_service();
        $subservice = $this->create_sub_service();
        $area = $this->create_area();
        // dd(Carbon::now()->format('Y-h-m H:I:s'));
        $res = $this->postJson(route('booking.store'), [
            'space_id' => $space->id,
            'space_type_id' => $space_type->id,
            'service_id' => $service->id,
            'sub_service_id' => $subservice->id,
            'booking_time' => '2019-11-11 10:00:00',
            'area_id' => $area->id,
            'area_number' => 2,
            'address' => [
                'city' => 'andromeda',
                'state' => 'krypton',
                'body' => 'Tooti hui building',
                'landmark' => 'inside kabristaan',
                'latitude' => '127.0.0.1',
                'longitude' => '127.0.0.1',
                'pin' => '8080',
            ],
        ])
            ->assertStatus(201)->json();
        $this->assertDatabaseHas('addresses', ['user_id' => auth()->id()]);
        $this->assertDatabaseHas('bookings', ['area_id' => $area->id]);
        $this->assertDatabaseHas('booking_spaces', ['space_id' => $space->id, 'space_type_id' => $space_type->id]);
        $this->assertDatabaseHas('booking_services', ['service_id' => $service->id, 'sub_service_id' => $subservice->id]);
        Notification::assertSentTo(auth()->user(), NotifyUserBookingConfirm::class);
    }

    /** @test */
    public function api_can_give_booking_statics_for_admin()
    {
        $this->create_admin();
        $booking = $this->create_booking(['status' => Booking::AUDITED]);

        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);

        $res = $this->get(route('booking.statics'))->assertOk();
        $this->assertEquals(1, count($res['today_audits']));
        $this->assertEquals(1, count($res['today_bookings']));
        $this->assertEquals(1, count($res['today_complete_audited']));
    }

    /** @test */
    public function api_approve_reschedule_request_booking_time()
    {
        Notification::fake();
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $booking = $this->create_booking(['status' => Booking::AUDITOR_ACCEPTED, 'reschedule_status' => 1]);
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);
        factory(BookingAllottee::class)->create(['booking_id' => $booking->id, 'allottee_id' => $partner->id, 'allottee_type' => get_class($partner)]);
        $reschedule_request = $this->create_reschedule_request([
            'booking_id' => $booking,
            'date_time' => Carbon::parse($booking->booking_time)->addDay()->format('Y-m-d h:i:s'),
            'allottee_id' => $partner->id,
            'allottee_type' => $partner->type,
            'status' => 1,
        ]);
        $this->postJson(route('approve.reschedule.request', [$booking->id]))
            ->assertStatus(200);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'reschedule_status' => 0, 'booking_time' => $reschedule_request->date_time]);
        $this->assertDatabaseHas('reschedule_requests', ['id' => $reschedule_request->id, 'status' => false]);
        Notification::assertSentTo($booking->user, NotifyRescheduleBookingSuccess::class);
    }

    /** @test */
    public function api_approve_reschedule_request_contractor_time()
    {
        Notification::fake();
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_CONTRACTOR]);
        $booking = $this->create_booking(['status' => Booking::CONTRACTOR_ACCEPTED, 'reschedule_status' => 1]);
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);
        factory(BookingAllottee::class)->create(['booking_id' => $booking->id, 'allottee_id' => $partner->id, 'allottee_type' => get_class($partner)]);
        $reschedule_request = $this->create_reschedule_request([
            'booking_id' => $booking,
            'date_time' => Carbon::parse($booking->booking_time)->addDay()->format('Y-m-d h:i:s'),
            'allottee_id' => $partner->id,
            'allottee_type' => $partner->type,
            'status' => 1,
        ]);
        $this->postJson(route('approve.reschedule.request', [$booking->id]))
            ->assertStatus(200);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'reschedule_status' => 0, 'contractor_time' => $reschedule_request->date_time]);
        $this->assertDatabaseHas('reschedule_requests', ['id' => $reschedule_request->id, 'status' => false]);

        Notification::assertSentTo($booking->user, NotifyRescheduleBookingSuccess::class);
    }

    /** @test */
    public function api_decline_reschedule_request_of_contractor_time()
    {
        Notification::fake();
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_CONTRACTOR]);
        $booking = $this->create_booking(['status' => Booking::CONTRACTOR_ACCEPTED, 'reschedule_status' => 1]);
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);
        factory(BookingAllottee::class)->create(['booking_id' => $booking->id, 'allottee_id' => $partner->id, 'allottee_type' => $partner->type]);
        $reschedule_request = $this->create_reschedule_request([
            'booking_id' => $booking,
            'date_time' => Carbon::parse($booking->booking_time)->addDay()->format('Y-m-d h:i:s'),
            'allottee_id' => $partner->id,
            'allottee_type' => $partner->type,
            'status' => 1,
        ]);
        $this->postJson(route('decline.reschedule.request', [$booking->id]))
            ->assertStatus(200);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'reschedule_status' => false, 'contractor_time' => $booking->contractor_time]);
        $this->assertDatabaseHas('reschedule_requests', ['id' => $reschedule_request->id, 'status' => false]);

        Notification::assertSentTo($partner, NotifyDeclineRescheduleRequest::class);
    }

    /** @test */
    public function api_decline_reschedule_request_of_booking_time()
    {
        Notification::fake();
        $partner = factory(Partner::class)->create(['type' => Partner::TYPE_AUDITOR]);
        $booking = $this->create_booking(['status' => Booking::AUDITOR_ACCEPTED, 'reschedule_status' => 1]);
        $this->create_booking_service(['booking_id' => $booking->id]);
        $this->create_booking_space(['booking_id' => $booking->id]);
        factory(BookingAllottee::class)->create(['booking_id' => $booking->id, 'allottee_id' => $partner->id, 'allottee_type' => $partner->type]);
        $reschedule_request = $this->create_reschedule_request([
            'booking_id' => $booking,
            'date_time' => Carbon::parse($booking->booking_time)->addDay()->format('Y-m-d h:i:s'),
            'allottee_id' => $partner->id,
            'allottee_type' => $partner->type,
            'status' => 1,
        ]);
        $this->postJson(route('decline.reschedule.request', [$booking->id]))
            ->assertStatus(200);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'reschedule_status' => false, 'booking_time' => $booking->booking_time]);
        $this->assertDatabaseHas('reschedule_requests', ['id' => $reschedule_request->id, 'status' => false]);

        Notification::assertSentTo($partner, NotifyDeclineRescheduleRequest::class);
    }

    /** @test */

    public function booking_with_area_home_space_formula()
    {
        Notification::fake();
        $booking = factory(Booking::class)->make(['address_id' => 44]);
        $address = factory(Address::class)->make(['address_id' => 44]);
        $space_type = $this->create_space_type(['name' => 'Small shop', 'value' => 0.3]);
        $space = $this->create_space(['name'=> 'HomeSpace']);
        $service = $this->create_service();
        $subservice = $this->create_sub_service();
        $area = $this->create_area();
        $res = $this->postJson(route('booking.store'), [
            'space_id' => $space->id,
            'space_type_id' => $space_type->id,
            'service_id' => $service->id,
            'sub_service_id' => $subservice->id,
            'booking_time' => '2019-11-11 10:00:00',
            'area_id' => $area->id,
            'area_number' => 2,
            'address' => [
                'city' => 'New Delhi',
                'state' => 'krypton',
                'body' => 'Tooti hui building',
                'landmark' => 'inside kabristaan',
                'latitude' => '127.0.0.1',
                'longitude' => '127.0.0.1',
                'pin' => '8080',
            ],
        ])->assertStatus(201)->json();

        $this->assertDatabaseHas('addresses', ['user_id' => auth()->id()]);
        $this->assertDatabaseHas('bookings', ['area_id' => $area->id]);
        $this->assertDatabaseHas('booking_spaces', ['space_id' => $space->id, 'space_type_id' => $space_type->id]);
        $this->assertDatabaseHas('booking_services', ['service_id' => $service->id, 'sub_service_id' => $subservice->id]);
    }

    /** @test */
    public function booking_with_area_workspace_space_formula()
    {
        Notification::fake();
        $booking = factory(Booking::class)->make(['address_id' => 44]);
        $address = factory(Address::class)->make(['address_id' => 44]);
        $space_type = $this->create_space_type(['name' => 'Small shop', 'value' => 0.3]);
        $space = $this->create_space(['name'=> 'WorkSpace']);
        $service = $this->create_service();
        $subservice = $this->create_sub_service();
        $area = $this->create_area();
        $res = $this->postJson(route('booking.store'), [
            'space_id' => $space->id,
            'space_type_id' => $space_type->id,
            'service_id' => $service->id,
            'sub_service_id' => $subservice->id,
            'booking_time' => '2019-11-11 10:00:00',
            'area_id' => $area->id,
            'area_number' => 7000,
            'address' => [
                'city' => 'New Delhi',
                'state' => 'krypton',
                'body' => 'Tooti hui building',
                'landmark' => 'inside kabristaan',
                'latitude' => '127.0.0.1',
                'longitude' => '127.0.0.1',
                'pin' => '8080',
            ],
        ])->assertStatus(201)->json();

        $this->assertDatabaseHas('addresses', ['user_id' => auth()->id()]);
        $this->assertDatabaseHas('bookings', ['area_id' => $area->id]);
        $this->assertDatabaseHas('booking_spaces', ['space_id' => $space->id, 'space_type_id' => $space_type->id]);
        $this->assertDatabaseHas('booking_services', ['service_id' => $service->id, 'sub_service_id' => $subservice->id]);
    }

}
