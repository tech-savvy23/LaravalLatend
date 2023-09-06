<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingCancel;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class BookingCancelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => BookingCancel::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BookingCancel $booking_cancel)
    {
        return response(['data' => $booking_cancel], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $booking_cancel = BookingCancel::create($request->all());
        return response(['data' => $booking_cancel], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BookingCancel  $booking_cancel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingCancel $booking_cancel)
    {
        $booking_cancel->update($request->all());
        return response(['data' => $booking_cancel], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BookingCancel  $booking_cancel
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingCancel $booking_cancel)
    {
        $booking_cancel->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
