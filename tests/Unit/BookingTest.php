<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\Models\Area;
use App\Models\City;
use App\Models\State;
use App\Models\Address;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Common\Otp;
use App\Models\BookingAsset;
use App\Models\BookingSpace;
use App\Models\BookingReport;
use App\Models\BookingService;
use App\Models\BookingAllottee;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_address()
    {
        $address  = factory(Address::class)->create();
        $Booking  = factory(Booking::class)->create(['address_id' => $address->id]);
        $this->assertInstanceOf(Address::class, $Booking->address);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user     = factory(User::class)->create();
        $Booking  = factory(Booking::class)->create(['user_id' => $user->id]);
        $this->assertInstanceOf(User::class, $Booking->user);
    }

    /** @test */
    public function it_has_may_users()
    {
        $booking     = factory(Booking::class)->create();
        $payment     = factory(Payment::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(Payment::class, $booking->payment[0]);
    }

    /** @test */
    public function it_has_one_booking_space()
    {
        $booking  = factory(Booking::class)->create();
        $space    = $this->create_space();
        $booking->booking_space()->create([
            'space_id'       => $space->id,
        ]);
        $this->assertDatabaseHas('booking_spaces', ['space_id' => $space->id]);
        $this->assertInstanceOf(BookingSpace::class, $booking->booking_space);
    }

    /** @test */
    public function it_has_one_service()
    {
        $booking         = factory(Booking::class)->create();
        $service         = $this->create_service();
        $booking->booking_service()->create([
            'service_id'       => $service->id,
        ]);
        $this->assertDatabaseHas('booking_services', ['service_id' => $service->id]);
        $this->assertInstanceOf(BookingService::class, $booking->booking_service);
    }

    /** @test */
    public function it_has_many_otp()
    {
        $booking         = factory(Booking::class)->create();
        $otp             = factory(Otp::class)->create(['for_id' => $booking->id, 'for_type'=>get_class($booking)]);
        $this->assertInstanceOf(Otp::class, $booking->otp[0]);
    }

    /** @test */
    public function it_has_one_area()
    {
        $area    =$this->create_area();
        $booking = factory(Booking::class)->create(['area_id' => $area->id]);
        $this->assertInstanceOf(Area::class, $booking->area);
    }

    /** @test */
    public function it_has_many_service()
    {
        $booking          = factory(Booking::class)->create();
        $allottee         = $this->create_partner();
        $booking->booking_allottee()->create([
            'allottee_id'       => $allottee->id,
            'allottee_type'     => get_class($allottee),
        ]);
        $this->assertDatabaseHas('booking_allottees', ['allottee_id' => $allottee->id]);
        $this->assertInstanceOf(BookingAllottee::class, $booking->booking_allottee[0]);
    }

    /** @test */
    public function it_has_many_reports()
    {
        $booking          = factory(Booking::class)->create();
        factory(BookingReport::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(BookingReport::class, $booking->reports[0]);
    }

    /** @test */
    public function it_has_many_assets()
    {
        $booking          = factory(Booking::class)->create();
        factory(BookingAsset::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(BookingAsset::class, $booking->assets);
    }

    /** @test */
    public function it_has_many_products()
    {
        $booking          = factory(Booking::class)->create();
        $product          = factory(Product::class)->create();
        $booking->products()->attach($product->id);
        $this->assertInstanceOf(Product::class, $booking->products[0]);
    }

    /** @test */
    public function it_can_get_state_id_from_state()
    {
        $product          = factory(Product::class)->create();
        $state            = factory(State::class)->create(['name' => 'Uttar Pradesh']);
        $address          = $this->create_address(['state' =>'uttar pradesh']);
        $booking          = factory(Booking::class)->create(['address_id' => $address->id]);
        $this->assertEquals($booking->state_id, $state->id);
    }

    /** @test */
    public function it_can_get_city_id_from_state()
    {
        $product          = factory(Product::class)->create();
        $city             = factory(City::class)->create(['name' => 'noida']);
        $address          = $this->create_address(['city' => 'Noida']);
        $booking          = factory(Booking::class)->create(['address_id' => $address->id]);
        $this->assertEquals($booking->city_id, $city->id);
    }

    /** @test */
    public function get_today_bookings()
    {
        $this->create_booking([], 5);
        $today_bookings = Booking::todayAudits();
        $this->assertEquals(5, count($today_bookings));
    }

    /** @test */
    public function get_today_Audited_bookings()
    {
        $this->create_booking(['status' => 2], 5);
        $today_bookings = Booking::todayAuditedBookings();
        $this->assertEquals(5, count($today_bookings));
    }

    /** @test */
    public function get_today_created_bookings()
    {
        $this->create_booking(['status' => 0], 5);
        $today_bookings = Booking::todayCreatedBookings();
        $this->assertEquals(5, count($today_bookings));
    }
}
