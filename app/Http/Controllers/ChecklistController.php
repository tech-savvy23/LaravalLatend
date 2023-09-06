<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\ChecklistResource;
use Symfony\Component\HttpFoundation\Response;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $checklist = Checklist::latest()->paginate(10);
        return ChecklistResource::collection($checklist);
    }

    /**
     * Display all listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $checklist = Checklist::latest()->get();
        return ChecklistResource::collection($checklist);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Checklist $checklist)
    {
        return response(['data' => new ChecklistResource($checklist)], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['title' => 'required|unique:checklists,title']);
        $checklist = Checklist::create($request->all());
        return response(['data' => new ChecklistResource($checklist)], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checklist $checklist)
    {
        $request->validate(['title' => 'required']);
        $checklist->update($request->all());
        return response(['data' => new ChecklistResource($checklist)], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checklist $checklist)
    {
        $checklist->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
