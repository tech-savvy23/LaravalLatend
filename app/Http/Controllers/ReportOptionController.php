<?php

namespace App\Http\Controllers;

use App\Models\ReportOption;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\ReportOptionMessage;
use Symfony\Component\HttpFoundation\Response;

class ReportOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => ReportOption::all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ReportOption $reportoption)
    {
        return response(['data' => $reportoption], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reportoption = ReportOption::create($request->all());
        return response(['data' => $reportoption], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ReportOption  $reportoption
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReportOption $reportoption)
    {
        $reportoption->update($request->all());
        return response(['data' => $reportoption], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReportOption  $reportoption
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportOption $reportoption)
    {
        $reportoption->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReportOption  $reportoption
     * @return \Illuminate\Http\Response
     */
    public function deleteMsg($message_id)
    {
        $msg   = ReportOptionMessage::find($message_id);
        $msg->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
