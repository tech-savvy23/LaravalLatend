<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingService;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class BookingServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => BookingService::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BookingService $booking_service)
    {
        return response(['data' => $booking_service], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $booking_service = BookingService::create($request->all());
        return response(['data' => $booking_service], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BookingService  $booking_service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingService $booking_service)
    {
        $booking_service->update($request->all());
        return response(['data' => $booking_service], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BookingService  $booking_service
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingService $booking_service)
    {
        $booking_service->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
