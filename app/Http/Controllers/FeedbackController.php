<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\FeedbackResource;
use App\Models\Booking;
use App\Models\BookingAllottee;
use Symfony\Component\HttpFoundation\Response;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedbacks = Feedback::latest()->ofUser()->paginate(10);
        return FeedbackResource::collection($feedbacks);
    }

    /**
     * Display a listing of the resource for partner.
     *
     * @return \Illuminate\Http\Response
     */
    public function partner()
    {
        $partner_id   = request('partner_id') ?? auth('partner')->id();
        $feedbacks    = Feedback::latest()->forPartner($partner_id)->paginate(10);
        return FeedbackResource::collection($feedbacks);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback $feedback)
    {
        return new FeedbackResource($feedback);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $feedback = Feedback::create($request->all());
        return response(new FeedbackResource($feedback), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feedback $feedback)
    {
        $feedback->update($request->all());
        return response(new FeedbackResource($feedback), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Feedback form for web
     * 
     * @param \App\Models\Booking $booking
     * @param \App\Models\BookingAllottee $bookingAllottee
     * 
     * @return \Illuminate\Http\Response
     */
    public function feedback(Booking $booking, BookingAllottee $bookingAllottee)
    {
        return view('feedback', compact('booking', 'bookingAllottee'));
    }

     /**
     * Add feedback for web
     * 
     * @param \App\Models\Booking $booking
     * @param \App\Models\BookingAllottee $bookingAllottee
     * 
     * @return \Illuminate\Http\Response
     */

    public function add(Request $request)
    {
        $request->validate( [
            'comment' => 'required'
        ]);

        $feedback = new Feedback();
        $feedback->booking_id = $request->booking_id;
        $feedback->user_id = $request->user_id;
        $feedback->partner_id = $request->partner_id;
        $feedback->body = $request->comment;
        $feedback->rating = $request->rating;
        $feedback->save();

        return redirect()->route('successful.message')->with('success','Thank you have for feedback ');
    }
}
