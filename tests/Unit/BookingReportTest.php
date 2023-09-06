<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Report;
use App\Models\Booking;
use App\Models\Checklist;
use App\Models\ReportOption;
use App\Models\BookingReport;
use App\Models\ChecklistType;
use App\Models\ReportOptionMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookingReportTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_belongs_to_booking()
    {
        $booking        = factory(Booking::class)->create();
        $BookingReport  = factory(BookingReport::class)->create(['booking_id' => $booking->id]);
        $this->assertInstanceOf(Booking::class, $BookingReport->booking);
    }

    /** @test */
    public function it_belongs_to_report()
    {
        $report         = factory(Report::class)->create();
        $BookingReport  = factory(BookingReport::class)->create(['report_id' => $report->id]);
        $this->assertInstanceOf(Report::class, $BookingReport->report);
    }

    /** @test */
    public function it_belongs_to_checklist()
    {
        $checklist      = factory(Checklist::class)->create();
        $BookingReport  = factory(BookingReport::class)->create(['checklist_id' => $checklist->id]);
        $this->assertInstanceOf(Checklist::class, $BookingReport->checklist);
    }

    /** @test */
    public function it_belongs_to_type()
    {
        $type           = factory(ChecklistType::class)->create();
        $BookingReport  = factory(BookingReport::class)->create(['checklist_type_id' => $type->id]);
        $this->assertInstanceOf(ChecklistType::class, $BookingReport->type);
    }

    /** @test */
    public function it_belongs_to_selected_option()
    {
        $selected_option = factory(ReportOption::class)->create();
        $BookingReport   = factory(BookingReport::class)->create(['selected_option_id' => $selected_option->id]);
        $this->assertInstanceOf(ReportOption::class, $BookingReport->selected_option);
    }

    /** @test */
    public function it_has_many_messages()
    {
        $report        = $this->create_report(['id' => 20]);
        $message       = $this->create_report_message(['report_id' => $report->id]);
        $bookingReport = $this->create_bookingreport(['report_id' => $report->id, 'id' => 22]);
        $messages      = $this->create_bookingreportmessage(['booking_report_id'=> $bookingReport->id, 'report_option_message_id'=> $message->id], 2);
        // dd($bookingReport->messages->toArray());
        $this->assertInstanceOf(ReportOptionMessage::class, $bookingReport->messages[0]);
        $this->assertEquals(2, $bookingReport->messages->count());
    }
}
