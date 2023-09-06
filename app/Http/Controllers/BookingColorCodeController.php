<?php

namespace App\Http\Controllers;

use App\BookingColorCode;
use App\Http\Resources\BookingColorCodeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BookingColorCodeController extends Controller
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
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validation($request);
            $bookingColorCode = BookingColorCode::create([
                'booking_report_id' => $request->booking_report_id,
                'booking_device_id' => $request->booking_device_id,
                'color_code_id' => $request->color_code_id,
            ]);
            DB::commit();
            return  response()->json([
                'data' => [
                    'booking_color_code' => new BookingColorCodeResource($bookingColorCode),
                    'message' => 'Successfully created'
                ]
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [
                    'error' => 'Something went wrong'
                ]
            ], 500);
        }
    }

    /**
     * Validations
     * @param Request $request
     */
    public function validation(Request $request)
    {
        $request->validate([
            'booking_report_id' => ['sometimes','required', ],
            'color_code_id' => ['required',],
        ]);
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
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $request->validate([
            'color_code_id' => ['required',],
        ]);
        try {
            $bookingColorCode = BookingColorCode::find($id);
            $bookingColorCode->update([
                'color_code_id' => $request->color_code_id,
            ]);
            DB::commit();
            return  response()->json([
                'date' => [
                    'message' => 'Successfully updated'
                ]
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [
                    'error' => 'Something went wrong'
                ]
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $bookingColorCode = BookingColorCode::find($id);
            $bookingColorCode->delete();
            DB::commit();
            return  response()->json([
                'date' => [
                    'message' => 'Successfully delete'
                ]
            ], Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'errors' => [
                    'error' => 'Something went wrong'
                ]
            ], 500);
        }
    }
}
