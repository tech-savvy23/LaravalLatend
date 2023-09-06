<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\ServiceResource;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::all();
        return ServiceResource::collection($services);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        return response(['data' => $service], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('thumbnail')) {
            $this->storeImage($request);
        }

        $service = Service::create($request->all());
        return response(['data' => $service], Response::HTTP_CREATED);
    }

    public function storeImage($request)
    {
        $filename = 'service/' . Str::random(10) . '.jpg';
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
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        if ($request->has('thumbnail')) {
            Storage::disk(env('DISK'))->delete("images/{$service->thumbnail}");
            $this->storeImage($request);
        }

        $service->update($request->all());
        return response(['data' => $service], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        Storage::disk(env('DISK'))->delete("images/{$service->thumbnail}");

        if ($service->booking_service->count() > 0) {
            return response(null, Response::HTTP_NOT_ACCEPTABLE);
        }
        $service->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
