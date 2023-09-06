<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Booking;
use App\Models\Partner;
use App\Notifications\NotifyPartnerBookingAccepted;
use App\Notifications\NotifyUserBookingAccepted;
use App\Notifications\NotifyUserBookingConfirm;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function setup():void
    {
        parent::setup();
        $this->user = $this->authUser();
        $this->partner = $this->create_partner(['latitude'=>'19.0760', 'longitude'=>'72.8777', 'type' => Partner::TYPE_AUDITOR, 'active' => true]);
        $this->actingAs($this->partner, 'partner');
    }
    /** @test */
    public function api_get_all_notification_of_single_user()
    {
        $this->create_new_booking();
        $this->getJson(route('user.notification'))->assertOk();
    }

    /** @test */
    public function api_get_all_notification_of_single_partner()
    {
        $this->partner_accept_new_leads_and_customer_partner_get_notified();
        $this->getJson(route('user.notification'))->assertOk();
    }

    private function create_new_booking()
    {
        Notification::fake();
        $booking    = factory(Booking::class)->make(['address_id' => 44]);
        $address    = factory(Address::class)->make(['address_id' => 44]);
        $space_type = $this->create_space_type();
        $space      = $this->create_space();
        $service    = $this->create_service();
        $subservice = $this->create_sub_service();
        $area       = $this->create_area();
        // dd(Carbon::now()->format('Y-h-m H:I:s'));
        $res     = $this->postJson(route('booking.store'), [
            'space_id'        => $space->id,
            'space_type_id'   => $space_type->id,
            'service_id'      => $service->id,
            'sub_service_id'  => $subservice->id,
            'booking_time'    => '2019-11-11 10:00:00',
            'contractor_time'    => '2019-11-11 10:00:00',
            'area_id'         => $area->id,
            'area_number'     => 2,
            'address'         => [
                'city'      => 'andromeda',
                'state'     => 'krypton',
                'body'      => 'Tooti hui building',
                'landmark'  => 'inside kabristaan',
                'latitude'  => '127.0.0.1',
                'longitude' => '127.0.0.1',
                'pin'       => '8080',
            ],
        ])
            ->assertStatus(201)->json();
        $this->assertDatabaseHas('addresses', ['user_id' => auth()->id()]);
        $this->assertDatabaseHas('bookings', ['area_id' => $area->id]);
        $this->assertDatabaseHas('booking_spaces', ['space_id' => $space->id, 'space_type_id' => $space_type->id]);
        $this->assertDatabaseHas('booking_services', ['service_id' => $service->id, 'sub_service_id' => $subservice->id]);
        Notification::assertSentTo(auth()->user(), NotifyUserBookingConfirm::class);

    }

    private function partner_accept_new_leads_and_customer_partner_get_notified()
    {
        Notification::fake();
        $user    = factory(User::class)->create();
        $booking = $this->create_booking(['status'=>0, 'user_id' => $user->id], 2);
        $res     = $this->postJson(route('partner.lead.accept', $booking[0]->id));
        $this->assertDatabaseHas('bookings', ['status' => 1, 'id' => $booking[0]->id]);
        $this->assertDatabaseHas('booking_allottees', ['allottee_id' => $this->partner->id]);
        $this->assertDatabaseHas('otps', ['model_id' => $user->id]);
        // $this->assertDatabaseHas('notifications', ['notifiable_id' => $user->id]);
        Notification::assertSentTo($user, NotifyUserBookingAccepted::class);
        Notification::assertSentTo($this->partner, NotifyPartnerBookingAccepted::class);
    }
}
