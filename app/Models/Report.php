<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['title', 'checklist_id', 'checklist_type_id', 'parent_id', 'parent_option_id'];

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function checklist_type()
    {
        return $this->belongsTo(ChecklistType::class);
    }

    public function options()
    {
        return $this->hasMany(ReportOption::class);
    }

    public function bookingReport()
    {
        return $this->hasMany(BookingReport::class);
    }

    public function images()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function storeOptions($request)
    {
        foreach ($request->options as $request) {
            $option = $this->options()->create(['title'=>$request['title']]);
            if (isset($request['messages'])) {
                foreach ($request['messages'] as $message) {
                    $option->messages()->create([
                        'message' => $message['value'], 'report_id' => $this->id,
                    ]);
                }
            }
        }
    }

    public function updateOptions($request)
    {
        foreach ($request->options as $option) {
            if (isset($option['id'])) {
                $item = $this->options()
                ->where(['id' => $option['id']])->first();
                $item->update(['title' => $option['title']]);
                if (isset($option['messages'])) {
                    foreach ($option['messages'] as $message) {
                        if (isset($message['id'])) {
                            $msg = ReportOptionMessage::find($message['id']);
                            $msg->update(['message' => $message['value']]);
                        } else {
                            $item->messages()->create([
                                'message' => $message['value'], 'report_id' => $this->id,
                            ]);
                        }
                    }
                }
            } else {
                $savedOption = $this->options()->create(['title'=>$option['title']]);
                if (isset($option['messages'])) {
                    foreach ($option['messages'] as $message) {
                        $savedOption->messages()->create([
                            'message' => $message['value'], 'report_id' => $this->id,
                        ]);
                    }
                }
            }
        }
    }
}
