<?php

namespace App\Http\Controllers;

use App\Models\SpaceType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SpaceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => SpaceType::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SpaceType $space_type)
    {
        return response(['data' => $space_type], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'thumbnail' => 'required',
            'name'      => 'required',
            'value' => 'required'
        ]);
        $this->storeImage($request);
        $space_type           = SpaceType::create($request->all());
        return response(['data' => $space_type], Response::HTTP_CREATED);
    }

    public function storeImage($request)
    {
        $filename = 'space_type/' . Str::random(10) . '.jpg';
        $image    = preg_replace("/data:image\/\w+;base64,/", '', $request->thumbnail);
        $image    = base64_decode($image);
        Storage::disk(env('DISK', 'public'))->put('images/' . $filename, $image);
        unset($request['thumbnail']);
        $request['thumbnail'] = $filename;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SpaceType  $space_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SpaceType $space_type)
    {
        if ($space_type->thumbnail !== $request->thumbnail && $request->thumbnail !== null) {
            Storage::disk(env('DISK'))->delete("images/{$space_type->thumbnail}");
            $this->storeImage($request);
            $space_type->update($request->all());
            return response(['data' => $space_type], Response::HTTP_ACCEPTED);
        }
        $space_type->update(['name' => $request->name, 'value' => $request->value]);
        return response(['data' => $space_type], Response::HTTP_ACCEPTED);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SpaceType  $space_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpaceType $space_type)
    {
        $space_type->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
