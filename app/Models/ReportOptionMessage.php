<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportOptionMessage extends Model
{
    protected $fillable = ['report_id', 'report_option_id', 'message'];
}
