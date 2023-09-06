<?php

namespace App\Models;

use App\BookingColorCode;
use Illuminate\Support\Str;
use App\Helpers\Images\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BookingDevice extends Model
{
    /** @var */

    protected $guarded = [];

    protected $casts = ['value' => 'array'];

    public static function store()
    {
        return self::create(request()->all());
    }

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function type()
    {
        return $this->belongsTo(ChecklistType::class, 'checklist_type_id');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * Upload Image
     */
    public function imageUpload($image)
    {
        $disk     = env('DISK', 'public');
        $filename = Str::random(20) . '.jpg';
        $image    = Upload::resize($image, 400);
        Storage::disk($disk)->put("images/{$filename}", $image);
        return $this->media()->create(['name' => $filename]);
    }

    public function bookingColorCode(){
        return $this->hasOne(BookingColorCode::class, 'booking_device_id', 'id');
    }
}
