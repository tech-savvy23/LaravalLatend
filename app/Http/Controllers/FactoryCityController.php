<?php

namespace App\Http\Controllers;

use App\Http\Requests\FactoryCityStoreRequest;
use App\Http\Requests\FactoryCityUpdateRequest;
use App\Http\Resources\FactoryCityResource;
use App\Models\FactoryCity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class FactoryCityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json([
            'data' => FactoryCityResource::collection(FactoryCity::all())
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(FactoryCityStoreRequest $request)
    {
        DB::beginTransaction();
        try {

            FactoryCity::store($request);
            DB::commit();

            return response()->json([
                'data' => 'Successfully Stored'
            ], Response::HTTP_CREATED);

        } catch (\Exception $exception) {
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FactoryCityUpdateRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(FactoryCityUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {

            FactoryCity::change($request, $id);
            DB::commit();

            return response()->json([
                'data' => 'Successfully Updated'
            ], Response::HTTP_OK);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'errors' => [
                    'error' => 'Something went wrong. Please try after some time'
                ]
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
