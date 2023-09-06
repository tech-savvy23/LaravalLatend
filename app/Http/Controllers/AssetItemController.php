<?php

namespace App\Http\Controllers;

use App\Models\AssetItem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class AssetItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => AssetItem::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AssetItem $assetitem)
    {
        return response(['data' => $assetitem], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $assetitem = AssetItem::create($request->all());
        return response(['data' => $assetitem], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AssetItem  $assetitem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssetItem $assetitem)
    {
        $assetitem->update($request->all());
        return response(['data' => $assetitem], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AssetItem  $assetitem
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssetItem $assetitem)
    {
        $assetitem->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
