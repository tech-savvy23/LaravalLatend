<?php

namespace App\Http\Controllers;

use App\Models\RescheduleReason;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RescheduleReasonController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => RescheduleReason::all()], 200);
    }

    /**
     * Store reschedule reason
     * 
     * @param $request Request
     * 
     * @return Json 
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'reason' => 'required'
        ]);

        return response()->json([ 
            'data' => RescheduleReason::store()
        ], 201);
    }

    /**
     * Update reschedule reason
     * 
     * @param $request Request
     * 
     * @return Json 
     */
    public function update($recheduleReasonId, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required'
        ]);

        return response()->json([ 
            'data' => RescheduleReason::updateData($recheduleReasonId)
        ], 201);
    }

      /**
     * Delete reschedule reason
     * 
     * @param $request Request
     * 
     * @return Json 
     */
    public function delete($recheduleReasonId)
    {
        
        return response()->json([ 
            'data' => RescheduleReason::find($recheduleReasonId)->delete()
        ], Response::HTTP_NO_CONTENT);
    }


}
