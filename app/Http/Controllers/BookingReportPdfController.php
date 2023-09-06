<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Support\Facades\View;

class BookingReportPdfController extends Controller
{
    public function generate(Booking $booking)
    {
        $headerHtml = view()->make('report.header')->with([
            'booking'          => $booking,
            'report_id'        => Booking::REPORT_UNIQUE_ID,
            'user'             => "{$booking->user->first_name} {$booking->user->last_name}",
            'email'            => $booking->user->email,
        ])->render();
        $footerHtml = view()->make('report.footer')->with([
            'booking'=> $booking,
        ])->render();

        $data    = $booking->load(
            'booking_service.service',
            'booking_space.spaceType',
            'user',
            'booking_allottee.partner',
            'booking_multiple_checklist.bookingReports',
            'booking_multiple_checklist.bookingReports.bookingColorCode',
            'booking_multiple_checklist.bookingReports.bookingColorCode.colorCode',
            'booking_multiple_checklist.bookingReports.checklist',
            'booking_multiple_checklist.bookingReports.type',
            'booking_multiple_checklist.bookingReports.messages',
            'booking_multiple_checklist.bookingReports.report',
            'booking_multiple_checklist.bookingReports.selected_option',
            'booking_multiple_checklist.bookingReports.media',
            'booking_devices',
            'booking_devices.bookingColorCode',
            'booking_devices.bookingColorCode.colorCode',
            'booking_devices.checklist',
            'booking_devices.type',
            'booking_devices.media'
        );
        $pdf     = PDF::loadView('report.pdf', [
            'data'             => $data,
            'booking_id'       => Booking::BOOKING_UNIQUE_ID,
            'report_id'        => Booking::REPORT_UNIQUE_ID,
            'partner_type'     => Partner::TYPE_AUDITOR,
            'booking'          => $booking,
            'user'             => "{$booking->user->first_name} {$booking->user->last_name}",
            'email'            => $booking->user->email,
            'booking_products' => $booking->products()->with('city', 'state')->withPivot('id', 'quantity')->get(),
            'gst'              => Product::GST,
            'total_price'      => 0,
        ])->setOption('footer-html', $footerHtml)
        ->setOption('header-html', $headerHtml);
        $report_id = Booking::REPORT_UNIQUE_ID;

        return $pdf->stream("report_id-{$report_id}-{$booking->id}.pdf");
    }

    public function header()
    {
        return view('report.header');
    }
}
