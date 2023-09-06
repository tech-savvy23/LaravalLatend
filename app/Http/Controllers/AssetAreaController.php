<?php

namespace App\Http\Controllers;

use App\Models\AssetArea;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class AssetAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => AssetArea::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AssetArea $assetarea)
    {
        return response(['data' => $assetarea], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $assetarea = AssetArea::create($request->all());
        return response(['data' => $assetarea], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AssetArea  $assetarea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssetArea $assetarea)
    {
        $assetarea->update($request->all());
        return response(['data' => $assetarea], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AssetArea  $assetarea
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssetArea $assetarea)
    {
        $assetarea->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
