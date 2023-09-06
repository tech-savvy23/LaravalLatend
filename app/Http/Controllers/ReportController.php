<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Report;
use App\Models\Checklist;
use Illuminate\Http\Request;
use App\Models\ChecklistType;
use Illuminate\Routing\Controller;
use App\Http\Resources\ReportResource;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Checklist $checklist)
    {
        $reports = $checklist->reports()->get();
        return ReportResource::collection($reports);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function byType(Checklist $checklist, ChecklistType $type)
    {
        $reports = $checklist->load('reports.options', 'reports.bookingReport', 'reports.options.messages')->reports->where('checklist_type_id', $type->id);
        return ReportResource::collection($reports);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Checklist $checklist, Report $report)
    {
        return response(['data' => new ReportResource($report)], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Checklist $checklist, Request $request)
    {
        $request->validate(['title' => 'required']);
        $report = $checklist->reports()->create($request->all());
        if (isset($request->options[0]['title'])) {
            $report->storeOptions($request);
        }
        return response(['data' => new ReportResource($report)], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checklist $checklist, Report $report)
    {
        $report->update($request->all());
        $report->updateOptions($request);
        return response(['data' => new ReportResource($report)], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checklist $checklist, Report $report)
    {
        $report->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function imageDelete(Media $media)
    {
        $media->delete();
        return response('Image deleted', Response::HTTP_NO_CONTENT);
    }
}
