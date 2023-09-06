<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingFileResource;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\BookingFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class BookingFileController extends Controller
{

    /**
     * @param Booking $booking
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Booking $booking, Request $request)
    {
        try {
            $data['booking_id'] = $booking->id;

            $request->validate([
                'pdf' => 'required'
            ]);
            if ($request->has('pdf')) {
                $data['pdf'] = $this->storeFile($request);
            }

            $booking_file = BookingFile::create($data);
            return response([
                'data' => $booking_file,
                'pdf_file' => Storage::disk(env('DISK', 'public'))->url($booking_file->pdf) ], Response::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


    /**
     * @param $request
     * @return string
     */
    public function storeFile($request)
    {
        $filename = 'booking/files/'. Str::random(10).'.pdf';
        $pdf    = $request->pdf;
        Storage::disk(env('DISK', 'public'))->put($filename, file_get_contents($pdf));
        return $filename;
    }

    /**
     * @param Booking $booking
     * @return mixed
     */
    public function index(Booking $booking)
    {
        if($booking->bookingFile()->count() > 0) {
            return response()->json([
                'data' => [
                    'file' => $booking->bookingFile,
                    'pdf_file' => Storage::disk(env('DISK', 'public'))->url($booking->bookingFile->pdf)
                ]
            ], 200);
        }
        return response()->json([
            'data' => [
                'file' => null,
            ]
        ], 200);
    }

    /**
     * @param BookingFile $bookingFile
     * @return mixed
     */
    public function download(BookingFile $bookingFile)
    {
        return Storage::disk(env('DISK', 'public'))->download($bookingFile->pdf);
    }



    /**
     * @param Booking $booking
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */

    public function deleteFile(Booking $booking)
    {
        if ($booking->bookingFile) {
            if (Storage::disk(env('DISK', 'public'))->exists($booking->bookingFile->pdf)) {
                Storage::disk(env('DISK', 'public'))->delete($booking->bookingFile->pdf);
            }
            $booking->bookingFile->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
