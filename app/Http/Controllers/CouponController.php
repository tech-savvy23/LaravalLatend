<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\CouponResource;
use Symfony\Component\HttpFoundation\Response;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::latest()->active()->paginate(50);
        return CouponResource::collection(($coupons));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $coupons = Coupon::latest()->paginate(50);
        return CouponResource::collection(($coupons));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        return response(new CouponResource($coupon), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $coupon = Coupon::create($request->all());
        return response(new CouponResource($coupon), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update($request->all());
        return response(new CouponResource($coupon), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function verify(Request $request)
    {
        $coupon = Coupon::where('name', $request->coupon)->first();
        if ($coupon) {
            if (!$coupon->active) {
                return response(['message'=>'This coupon is not active now.'], Response::HTTP_NOT_ACCEPTABLE);
            }
            return new CouponResource($coupon);
        }

        return response(['errors'=> ['error'=>'Coupon is invalid']], Response::HTTP_NOT_FOUND);
    }

    public function active(Coupon $coupon)
    {
        $coupon->update(['active' => true]);
        return response('Done', Response::HTTP_ACCEPTED);
    }

    public function inactive(Coupon $coupon)
    {
        $coupon->update(['active' => false]);
        return response('Done', Response::HTTP_NO_CONTENT);
    }
}
