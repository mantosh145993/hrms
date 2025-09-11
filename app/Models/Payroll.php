<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'user_id',
        'month',
        'working_days',
        'present_days',
        'late_minutes_total',
        'overtime_minutes_total',
        'paid_days',
        'gross_pay',
        'deductions',
        'net_pay'
    ];
}
