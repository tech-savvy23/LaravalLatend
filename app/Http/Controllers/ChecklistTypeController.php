<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;
use App\Models\ChecklistType;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class ChecklistTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Checklist $checklist)
    {
        $types = $checklist->types;
        return response(['data' => $types], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ChecklistType $checklisttype)
    {
        return response(['data' => $checklisttype], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Checklist $checklist)
    {
        $request->validate(['title' => 'required']);
        $checklisttype = $checklist->types()->create($request->all());
        return response(['data' => $checklisttype], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ChecklistType  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checklist $checklist, ChecklistType $type)
    {
        $request->validate(['title' => 'required']);
        $type->update($request->all());
        return response(['data' => $type], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ChecklistType  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checklist $checklist, ChecklistType $type)
    {
        $type->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
