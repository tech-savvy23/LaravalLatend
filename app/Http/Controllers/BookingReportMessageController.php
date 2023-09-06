<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\BookingReportMessage;
use Symfony\Component\HttpFoundation\Response;

class BookingReportMessageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bookingReportMessage = BookingReportMessage::create($request->all());
        return response(['data' => $bookingReportMessage], Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BookingReportMessage  $bookingReportMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        BookingReportMessage::where([
            'booking_id'               => $request->booking_id,
            'booking_report_id'        => $request->booking_report_id,
            'report_option_message_id' => $request->report_option_message_id,
        ])->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
