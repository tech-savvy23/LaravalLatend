<?php

namespace Tests\Feature;

use PDF;
use Tests\TestCase;
use App\Models\Partner;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PdfTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
//    public function testExample()
//    {
//        // $this->withoutExceptionHandling();
//        // PDF::fake();
//        // $space = $this->create_space();
//        // $space_type = $this->create_space_type();
//        // $booking         = $this->create_booking();
//
//        // $this->create_booking_space([
//        //     'booking_id' => $booking->id,
//        //     'space_type_id' => $space_type->id,
//        //     'space_id' => $space->id]);
//        // $this->create_booking_service(['booking_id' => $booking->id]);
//        // $checklist       = $this->create_checklist();
//        // $partner         = $this->create_partner(['type'=>Partner::TYPE_AUDITOR]);
//        // $booking->booking_allottee()->create(['allottee_id' => $partner->id, 'allottee_type'=>$partner->type]);
//        // $reports         = $this->create_report([], 10);
//        // $multiple_checklist = $this->createMultipleChecklist(['booking_id' => $booking->id, 'checklist_id' => $checklist->id]);
//        // $reports->each(function ($report) use ($booking,$checklist, $multiple_checklist) {
//        //     $bookingReports = $this->create_bookingreport(['multi_checklist_id' => $multiple_checklist->id, 'booking_id' => $booking->id, 'report_id' => $report->id, 'checklist_id' => $checklist->id]);
//        // });
//
//        // $res = $this->get(route('booking.report.pdf', $booking->id));
//        // PDF::assertFileNameIs(null);
//    }
}
