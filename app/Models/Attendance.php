<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\TimeHelper;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'work_date',
        'check_in_at',
        'check_out_at',
        'late_minutes',
        'worked_minutes',
        'status',
        'is_paid_leave',
        'meta'
    ];
    //
    protected $casts = [
        'meta' => 'array',
        'work_date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getWorkedTimeAttribute()
    {
        if (!$this->check_in_at || !$this->check_out_at) return null;
        $workedMinutes = $this->check_in_at->diffInMinutes($this->check_out_at);
        $hours = intdiv($workedMinutes, 60);
        $minutes = $workedMinutes % 60;
        return "{$hours}h {$minutes}m";
    }

    // public function getLateTimeAttribute()
    // {
    //     if (!$this->check_in_at) {
    //         return "0h 0m 0s";
    //     }
    //     $checkIn = Carbon::parse($this->check_in_at);
    //     $shiftStart = $this->work_date
    //         ? Carbon::parse($this->work_date)->setTime(10, 0, 0)
    //         : $checkIn->copy()->setTime(10, 0, 0);
    //     if ($checkIn->lte($shiftStart)) {
    //         return "0h 0m 0s";
    //     }
    //     $lateSeconds = $shiftStart->diffInSeconds($checkIn);
    //     return TimeHelper::formatSeconds($lateSeconds);
    // }

    // public function getOvertimeAttribute()
    // {
    //     if (!$this->check_in_at || !$this->check_out_at) {
    //         return '0h 0m';
    //     }
    //     $checkIn = Carbon::parse($this->check_in_at);
    //     $checkOut = Carbon::parse($this->check_out_at);
    //     $workedMinutes = $checkIn->diffInMinutes($checkOut);
    //     // Standard shift = 8 hours (480 minutes)
    //     $shiftDuration = 480;
    //     if ($workedMinutes > $shiftDuration) {
    //         $overtime = $workedMinutes - $shiftDuration;
    //         return sprintf("%dh %dm", floor($overtime / 60), $overtime % 60);
    //     }
    //     return '0h 0m';
    // }

    public function getLateTimeAttribute()
{
    if (!$this->check_in_at) {
        return "0h 0m 0s";
    }

    $checkIn = Carbon::parse($this->check_in_at);

    // Shift start time (example: 10:00 AM)
    $shiftStart = $this->work_date
        ? Carbon::parse($this->work_date)->setTime(10, 0, 0)
        : $checkIn->copy()->setTime(10, 0, 0);

    if ($checkIn->lte($shiftStart)) {
        return "0h 0m 0s";
    }

    $lateSeconds = $shiftStart->diffInSeconds($checkIn);

    return TimeHelper::formatSeconds($lateSeconds);
}

public function getOvertimeAttribute()
{
    if (!$this->check_in_at || !$this->check_out_at) {
        return '0h 0m';
    }

    $checkIn = Carbon::parse($this->check_in_at);
    $checkOut = Carbon::parse($this->check_out_at);

    // Total worked minutes
    $workedMinutes = $checkIn->diffInMinutes($checkOut);

    // Shift duration in minutes (8 hours = 480)
    $shiftDuration = 480;

    // Late minutes
    $lateMinutes = 0;
    if ($checkIn->gt(Carbon::parse($this->work_date)->setTime(10, 0, 0))) {
        $lateMinutes = Carbon::parse($this->work_date)->setTime(10, 0, 0)->diffInMinutes($checkIn);
    }

    // Deduct late minutes from worked minutes for overtime calculation
    $effectiveWorkedMinutes = $workedMinutes - $lateMinutes;

    if ($effectiveWorkedMinutes > $shiftDuration) {
        $overtime = $effectiveWorkedMinutes - $shiftDuration;
        return sprintf("%dh %dm", floor($overtime / 60), $overtime % 60);
    }

    return '0h 0m';
}

    public static function getTotalLateTime($userId)
    {
        $attendances = self::where('user_id', $userId)->get();
        $totalSeconds = 0;
        $shiftStart = Carbon::createFromTime(10, 0, 0);
        foreach ($attendances as $attendance) {
            if (!$attendance->check_in_at) {
                continue;
            }
            $checkIn = Carbon::parse($attendance->check_in_at);
            if ($checkIn->gt($shiftStart)) {
                $totalSeconds += $shiftStart->diffInSeconds($checkIn);
            }
        }

        return TimeHelper::formatSeconds($totalSeconds);
    }
}
