<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $fillable = ['title', 'slug', 'has_type', ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($category) {
            self::createSlug($category);
        });

        self::updating(function ($category) {
            self::createSlug($category);
        });
    }

    /**
     * @param $category
     */
    public static function createSlug($category)
    {
        $category->slug = Str::slug($category->title);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst(strtolower($value));
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function types()
    {
        return $this->hasMany(ChecklistType::class);
    }
}
