<?php

namespace App\Http\Controllers;

use App\Http\Requests\MetroNonMetroStoreRequest;
use App\Http\Requests\MetroNonMetroUpdateRequest;
use App\Http\Resources\MetroAndNonMetroResource;
use App\Models\MetroAndNonMetro;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class MetroAndNonMetroController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        return response()->json([
            'data' => MetroAndNonMetroResource::collection(MetroAndNonMetro::with('space')->orderBy('type')->get())
        ], 200);
    }

    /**
     * Store
     *
     * @param MetroNonMetroStoreRequest $request
     * @return JsonResponse
     */
    public function store(MetroNonMetroStoreRequest $request)
    {
        DB::beginTransaction();
        try {

            MetroAndNonMetro::store($request);
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
     * Show particular value
     *
     * @param $id
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the metro and non metro
     *
     * @param Request $request
     * @param $id
     */
    public function update(MetroNonMetroUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {

            MetroAndNonMetro::change($request, $id);
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


}
