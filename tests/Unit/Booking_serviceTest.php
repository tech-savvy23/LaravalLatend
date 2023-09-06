<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\Service;
use App\Models\BookingService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Booking_serviceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_booking()
    {
        $booking          = factory(Booking::class)->create();
        $Booking_service  = factory(BookingService::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(Booking::class, $Booking_service->booking);
    }

    /** @test */
    public function it_belongs_to_service()
    {
        $service          = factory(Service::class)->create();
        $Booking_service  = factory(BookingService::class)->create(['service_id' => $service->id]);
        $this->assertInstanceOf(Service::class, $Booking_service->service);
    }
}
