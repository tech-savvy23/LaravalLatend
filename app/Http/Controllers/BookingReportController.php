<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\BookingReport;
use Illuminate\Routing\Controller;
use App\Models\BookingReportMessage;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\BookingReportResource;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class BookingReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = BookingReport::with('checklist', 'type', 'report', 'selected_option')->get();
        return BookingReportResource::collection($reports);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reports(Booking $booking)
    {
        $reports = $booking->reports()->with('checklist', 'type', 'report', 'selected_option')->get();
        return BookingReportResource::collection($reports);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        return response(['data' => BookingReportResource::collection($booking->reports)], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bookingreport = BookingReport::create($request->all());
        if ($request->images) {
            $bookingreport->storeImages($request->images);
        }
        return response(['data' => new BookingReportResource($bookingreport)], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BookingReport  $bookingreport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingReport $bookingreport)
    {
        $message = BookingReportMessage::where(['booking_report_id'=>$bookingreport->id, 'booking_id'=>$bookingreport->booking->id])->first();
        if ($message) {
            $message->delete();
        }
        $bookingreport->update($request->all());
        return response(['data' => $bookingreport], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BookingReport  $bookingreport
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingReport $bookingreport)
    {
        $bookingreport->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function imageUpload($id)
    {
        $report = BookingReport::find($id);
        request()->validate([
            'image' => 'required',
        ]);
        try {
            $image = $report->imageUpload(request('image'));
            return response($image, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response( $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function imageDelete($id)
    {
        $image = Media::find($id);
        Storage::disk(env('DISK'))->delete("images/{$image->name}");
        $image->delete();
        return response('deleted', Response::HTTP_NO_CONTENT);
    }
}
