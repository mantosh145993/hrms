<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
   use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'max_days',
        'paid_status'
    ];

    public function Leave(){
        return $this->hasMany(Leave::class,'type,id');
    }
}
