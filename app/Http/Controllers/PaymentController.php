<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingGst;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => Payment::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        return response(['data' => $payment], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $request->validate([
//           'gst_no' => ['required'],
//           'organisation_name' => ['required']
//        ]);
        if (isset($request->date)) {
            $payment = Payment::create($request->except('date'));
            $booking =  Booking::find($request->booking_id);
            $booking->update([
                'status' => Booking::CONTRACTOR_REQUIRED,
                'contractor_time' => $request->date,
                'otp_status' => 0
                ]);
            BookingGst::create([
                'gst_no' => $request->gst_no,
                'organisation_name' => $request->organisation_name,
                'booking_id' => $request->booking_id
            ]);
            return response(['data' => $payment], Response::HTTP_CREATED);

        }
        $payment = Payment::create($request->all());
        BookingGst::create([
            'gst_no' => $request->gst_no,
            'organisation_name' => $request->organisation_name,
            'booking_id' => $request->booking_id
        ]);
        return response(['data' => $payment], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->all());
        return response(['data' => $payment], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function paymentLater(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'transaction_id' => [
                    'required'
                ],
            ]);

            $payment = Payment::find($id);
            $payment->update([
                'transaction_id' => $request->transaction_id,
                'status' => 1
            ]);
            DB::commit();
            return response()->json([
                'payment' => $payment->refresh()
            ]);
        }catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'errors' => [
                    'error'
                ]
            ]);
        }
    }
}
