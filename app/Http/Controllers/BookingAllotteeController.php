<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingAllottee;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class BookingAllotteeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => BookingAllottee::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BookingAllottee $booking_allottee)
    {
        return response(['data' => $booking_allottee], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $booking_allottee = BookingAllottee::create($request->all());
        return response(['data' => $booking_allottee], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BookingAllottee  $booking_allottee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingAllottee $booking_allottee)
    {
        $booking_allottee->update($request->all());
        return response(['data' => $booking_allottee], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BookingAllottee  $booking_allottee
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingAllottee $booking_allottee)
    {
        $booking_allottee->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
