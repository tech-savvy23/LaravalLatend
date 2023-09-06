<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class ChecklistType extends Model
{
    protected $fillable = ['title', 'slug', 'checklist_id', ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($checklistType) {
            self::createSlug($checklistType);
            $checklistType->checklist->update(['has_type'=>true]);
        });

        self::deleting(function ($checklistType) {
            self::createSlug($checklistType);
            $checklistType->checklist->update(['has_type'=>false]);
        });

        self::updating(function ($checklistType) {
            self::createSlug($checklistType);
        });
    }

    /**
     * @param $checklistType
     */
    public static function createSlug($checklistType)
    {
        $checklistType->slug = Str::slug($checklistType->title);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }
}
