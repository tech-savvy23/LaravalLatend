<?php

namespace App\Http\Controllers;

use App\Models\BookingSpace;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class BookingSpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => BookingSpace::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BookingSpace $booking_space)
    {
        return response(['data' => $booking_space], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $booking_space = BookingSpace::create($request->all());
        return response(['data' => $booking_space], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BookingSpace  $booking_space
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingSpace $booking_space)
    {
        $booking_space->update($request->all());
        return response(['data' => $booking_space], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BookingSpace  $booking_space
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingSpace $booking_space)
    {
        $booking_space->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
