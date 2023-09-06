<?php

namespace App\Http\Controllers;

use App\Models\SubService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class SubServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => SubService::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SubService $sub_service)
    {
        return response(['data' => $sub_service], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sub_service = SubService::create($request->all());
        return response(['data' => $sub_service], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubService  $sub_service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubService $sub_service)
    {
        $sub_service->update($request->all());
        return response(['data' => $sub_service], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SubService  $sub_service
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubService $sub_service)
    {
        $sub_service->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
