<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PartnerPrice;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\PartnerPriceResource;
use Symfony\Component\HttpFoundation\Response;

class PartnerPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $price = PartnerPrice::with('state', 'city')->latest()->paginate(50);
        return PartnerPriceResource::collection($price);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PartnerPrice $partnerprice)
    {
        return new PartnerPriceResource($partnerprice);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'state_id' => 'required',
            'price'    => 'required',
            'type'     => 'required',
        ]);
        $partnerprice = PartnerPrice::create($request->all());
        return response(new PartnerPriceResource($partnerprice), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PartnerPrice  $partnerprice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PartnerPrice $partnerprice)
    {
        $partnerprice->update($request->all());
        return response(new PartnerPriceResource($partnerprice), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PartnerPrice  $partnerprice
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartnerPrice $partnerprice)
    {
        $partnerprice->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function partnerPrice(Booking $booking,$type)
    {
        return response()->json([
            'data' =>  $booking->partnerprice($type)
        ],200);
    }
}
