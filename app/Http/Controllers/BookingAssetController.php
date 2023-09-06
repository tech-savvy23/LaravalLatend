<?php

namespace App\Http\Controllers;

use App\Models\BookingAsset;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class BookingAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => BookingAsset::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BookingAsset $bookingasset)
    {
        return response(['data' => $bookingasset], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bookingasset = BookingAsset::create($request->all());
        return response(['data' => $bookingasset], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BookingAsset  $bookingasset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingAsset $bookingasset)
    {
        $bookingasset->update($request->all());
        return response(['data' => $bookingasset], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BookingAsset  $bookingasset
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingAsset $bookingasset)
    {
        $bookingasset->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
