<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Partner;
use App\Models\Product;
use PDF;
use Illuminate\Http\Request;

class BookingInvoiceController extends Controller
{
    /**
     * Generate the invoice of booking
     * 
     */
    public function generate(Booking $booking)
    {
        

        $data    = $booking->load(
            'booking_service.service',
            'booking_space.spaceType',
            'user',
            'booking_allottee.partner',
            'booking_multiple_checklist.bookingReports',
            'booking_multiple_checklist.bookingReports.checklist',
            'booking_multiple_checklist.bookingReports.type',
            'booking_multiple_checklist.bookingReports.messages',
            'booking_multiple_checklist.bookingReports.report',
            'booking_multiple_checklist.bookingReports.selected_option',
            'booking_multiple_checklist.bookingReports.media',
            'booking_devices',
            'booking_devices.checklist',
            'booking_devices.type',
            'booking_devices.media'
        );

        $headerHtml = view()->make('pdf.header')->render();

        $footerHtml = view()->make('pdf.footer')->with([
            'booking'=> $booking,
        ])->render();

        $pdf     = PDF::loadView('pdf.invoice', [
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
            'payment'          => $booking->payment->first(),
            'payment_words'    => $this->getIndianCurrency((float)$booking->payment->first()->amount)
        ])->setOption('footer-html', $footerHtml)->setOption('header-html', $headerHtml);
        $report_id = Booking::REPORT_UNIQUE_ID;

        return $pdf->stream("report_id-{$report_id}-{$booking->id}-invoice.pdf");
        return view('pdf.invoice');
    }

    public function getIndianCurrency(float $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }
}
