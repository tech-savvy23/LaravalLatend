<?php

namespace App\Models;

use App\BookingColorCode;
use Illuminate\Support\Str;
use App\Helpers\Images\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BookingReport extends Model
{
    protected $fillable = ['booking_id', 'checklist_id', 'checklist_type_id', 'report_id', 'selected_option_id', 'observation', 'result',  'multi_checklist_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function type()
    {
        return $this->belongsTo(ChecklistType::class, 'checklist_type_id');
    }

    public function selected_option()
    {
        return $this->belongsTo(ReportOption::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function messages()
    {
        return $this->hasManyThrough(ReportOptionMessage::class, BookingReportMessage::class, 'booking_report_id', 'id', 'id', 'report_option_message_id');
    }

    public function storeImages($images)
    {
        $disk  = env('DISK', 'public');
        foreach ($images as $image) {
            $filename = Str::random() . '.jpg';
            Storage::disk($disk)->put($filename, $image);
            $this->media()->create(['name' => $filename]);
        }
    }

    public function imageUpload($image)
    {
        $disk     = env('DISK', 'public');
        $filename = Str::random(20) . '.jpg';
        $image    = Upload::resize($image, 400);
        Storage::disk($disk)->put("images/{$filename}", $image);
        return $this->media()->create(['name' => $filename]);
    }

    public function bookingColorCode(){
        return $this->hasOne(BookingColorCode::class, 'booking_report_id', 'id');
    }
}
