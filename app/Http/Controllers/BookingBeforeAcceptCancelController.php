<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingCancelRequest;
use App\Models\BookingBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class BookingBeforeAcceptCancelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BookingCancelRequest $request)
    {
        DB::beginTransaction();
        try{
            BookingBlock::store($request);
            DB::commit();
            return response()->json(['data' => 'Successfully block the booking'], 201);
        }catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'errors' => [
                    'error' => 'Something went wrong. Please try after some time'
                ]
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
