<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\BookingReport;
use App\Models\ReportOptionMessage;
use App\Models\BookingReportMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookingReportMessageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_bookingreport()
    {
        $bookingreport                = factory(BookingReport::class)->create();
        $BookingReportMessage         = factory(BookingReportMessage::class)->create(['booking_report_id' => $bookingreport->id]);
        $this->assertInstanceOf(BookingReport::class, $BookingReportMessage->bookingReport);
    }

    /** @test */
    public function it_belongs_to_report_option_message()
    {
        $reportOptionMessage   = factory(ReportOptionMessage::class)->create();
        $BookingReportMessage  = factory(BookingReportMessage::class)->create(['report_option_message_id' => $reportOptionMessage->id]);
        $this->assertInstanceOf(BookingReport::class, $BookingReportMessage->bookingReport);
    }
}
