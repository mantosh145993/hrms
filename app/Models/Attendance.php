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
        if (!$this->check_in_at || !$this->check_out_at) {
            return '0h 0m';
        }

        $checkIn = Carbon::parse($this->check_in_at);
        $checkOut = Carbon::parse($this->check_out_at);

        // Total worked minutes
        $workedMinutes = $checkIn->diffInMinutes($checkOut);

        // Deduct only break (30 min)
        $breakMinutes = 30;
        $netWorkedMinutes = max(0, $workedMinutes - $breakMinutes);

        return sprintf("%dh %dm", floor($netWorkedMinutes / 60), $netWorkedMinutes % 60);
    }

    public function getLateTimeAttribute()
    {
        if (!$this->check_in_at) {
            return "0h 0m 0s";
        }

        $checkIn = Carbon::parse($this->check_in_at);
        $shiftStart = $this->work_date
            ? Carbon::parse($this->work_date)->setTime(10, 0, 0)
            : $checkIn->copy()->setTime(10, 0, 0);

        if ($checkIn->lte($shiftStart)) {
            return "0h 0m 0s";
        }

        $lateSeconds = $shiftStart->diffInSeconds($checkIn);
        return sprintf(
            "%dh %dm %ds",
            floor($lateSeconds / 3600),
            floor(($lateSeconds % 3600) / 60),
            $lateSeconds % 60
        );
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
        // Deduct break
        $breakMinutes = 30;
        $netWorkedMinutes = max(0, $workedMinutes - $breakMinutes);
        // Standard shift = 8 hours work (480 min)
        $shiftDuration = 480;
        if ($netWorkedMinutes > $shiftDuration) {
            $overtime = $netWorkedMinutes - $shiftDuration;
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
