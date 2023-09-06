<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportOption extends Model
{
    protected $fillable = ['title', 'report_id', 'message', ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function messages()
    {
        return $this->hasMany(ReportOptionMessage::class);
    }
}
