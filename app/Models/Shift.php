<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable= [
        'name',
        'start_time',
        'end_time',
        'grace_minutes',
        'half_day_after_minutes',
        'workday_minutes',
    ];
}
