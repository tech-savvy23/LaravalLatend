<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\BookingProduct;
use Illuminate\Routing\Controller;
use App\Http\Resources\BookingProductResource;
use Symfony\Component\HttpFoundation\Response;

class BookingProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => BookingProduct::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BookingProduct $bookingproduct)
    {
        return response(['data' => $bookingproduct], 200);
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
            'booking_id' => 'required',
            'product_id' => 'required',
        ]);
        $unique = BookingProduct::where([
            'booking_id' => request('booking_id'),
            'product_id' => request('product_id'),
        ])->exists();
        if ($unique) {
            return response()->json(['error' => 'Already exists'], Response::HTTP_NOT_ACCEPTABLE);
        }
        $booking        = Booking::find(request('booking_id'));
        BookingProduct::create($request->all());
        $product        = $booking->products()->where('products.id', request('product_id'))->withPivot('id', 'quantity','price')->first();
        return new BookingProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BookingProduct  $bookingproduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingProduct $bookingproduct)
    {
        $bookingproduct->update($request->all());
        return response(['data' => $bookingproduct], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BookingProduct  $bookingproduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $bookingproduct)
    {
        $booking = $bookingproduct;
        $booking->products()->detach(request('product_id'));
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function products(Booking $booking)
    {
        $products = $booking->products()->with('city', 'state')->withPivot('id', 'quantity','price')->get();
        return BookingProductResource::collection($products);
    }
}
