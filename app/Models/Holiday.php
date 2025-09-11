<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'date',
        'title',
        'end_date',
        'is_paid',
        'max_days',
    ];

    protected static function booted()
    {
        static::saving(function ($holiday) {
            $start = Carbon::parse($holiday->date);
            $end   = $holiday->end_date ? Carbon::parse($holiday->end_date) : $start;
            $holiday->max_days = $start->diffInDays($end) + 1;
        });
    }
}
