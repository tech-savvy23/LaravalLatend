<?php

namespace App\Http\Controllers;

use App\ColorCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\Response;

class ColorCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $colorCodes = ColorCode::all();
        return response()->json(['data' => $colorCodes], 200);
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
            ColorCode::create([
                'name' => $request->name,
                'code' => $request->code,
            ]);
            DB::commit();
            return  response()->json([
                'date' => [
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
            'name' => ['required'],
            'code' => ['required'],
        ]);
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
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->validation($request);
            $colorCode =  ColorCode::find($id);
            $colorCode->update([
                'name' => $request->name,
                'code' => $request->code,
            ]);
            DB::commit();
            return  response()->json([
                'date' => [
                    'message' => 'Successfully update'
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
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $colorCode =  ColorCode::find($id);
            $colorCode->delete();
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
