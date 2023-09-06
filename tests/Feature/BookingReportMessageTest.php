<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BookingReportMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookingReportMessageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function api_can_store_new_bookingreportmessage()
    {
        $bookingreportmessage = factory(BookingReportMessage::class)->make(['booking_id'=>1]);
        $this->postJson(route('bookingreportmessage.store'), $bookingreportmessage->toArray())
        ->assertStatus(201);
        $this->assertDatabaseHas('booking_report_messages', ['booking_id'=>1]);
    }

    /** @test */
    public function api_can_delete_bookingreportmessage()
    {
        $report                = $this->create_report(['id' => 20]);
        $message               = $this->create_report_message(['report_id' => $report->id]);
        $booking               = $this->create_booking();
        $bookingReport         = $this->create_bookingreport(['report_id' => $report->id, 'id' => 22]);
        $bookingreportmessage  = $this->create_bookingreportmessage([
            'booking_id'               => $booking->id,
            'booking_report_id'        => $bookingReport->id,
            'report_option_message_id' => $message->id,
        ]);

        $this->deleteJson(route('bookingreportmessage.destroy', [
            'booking_id'               => $booking->id,
            'booking_report_id'        => $bookingReport->id,
            'report_option_message_id' => $message->id,
        ]))->assertStatus(204);
        $this->assertDatabaseMissing('booking_report_messages', ['booking_id'=>$bookingreportmessage->booking_id]);
    }
}
