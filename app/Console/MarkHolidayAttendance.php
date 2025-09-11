<?php

namespace App\Console;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MarkHolidayAttendance
{
    public function __invoke()
    {
        $today = Carbon::today()->toDateString();
        $isSunday = Carbon::today()->isSunday();
        $isHoliday = Holiday::whereDate('date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();

        foreach (User::whereIn('role', ['employee', 'manager', 'admin'])->get() as $user) {

            // check if user is on leave today
            $onLeave = Leave::where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->first();

            // default absent
            $status = 'absent';
            $isPaidLeave = false;

            if ($onLeave) {
                $status = 'on_leave';
                $isPaidLeave = $onLeave->is_paid_leave;
            } elseif ($isSunday) {
                $status = 'sunday';
            } elseif ($isHoliday) {
                $status = 'holiday';
            } else {
                // check if attendance was already marked manually (check-in/out)
                $existing = Attendance::where('user_id', $user->id)
                    ->whereDate('work_date', $today)
                    ->first();

                if ($existing && $existing->check_in_at) {
                    $status = $existing->status ?? 'present';
                    $isPaidLeave = $existing->is_paid_leave;
                }
            }

            Attendance::updateOrCreate(
                [
                    'user_id'   => $user->id,
                    'work_date' => $today,
                ],
                [
                    'status'         => $status,
                    'is_paid_leave'  => $isPaidLeave,
                    'worked_minutes' => 0,
                    'late_minutes'   => 0,
                ]
            );
        }

        Log::info("✅ Auto attendance marked (including absents) for $today");
    }
}
