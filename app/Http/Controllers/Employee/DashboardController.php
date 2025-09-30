<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{Feedback, Task, Holiday, Leave, LeaveType};
use App\Helpers\TimeHelper;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $user = Auth::user();
        // check if today is holiday or Sunday
        $isSunday = Carbon::today()->isSunday();
        $isHoliday = Holiday::whereDate('date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();
        // check if user is on leave
        $onLeave = Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();
        // dd($onLeave);
        $attendance = null;
        $canCheckInOut = true;
        if (!$isSunday && !$isHoliday && !$onLeave) {
            $attendance = Attendance::with('user')
                ->where('user_id', $user->id)
                ->whereDate('work_date', $today)
                ->first();
        } else {
            // user cannot check in/out today
            $canCheckInOut = false;
        }
        return view('employee.dashboard', compact('attendance', 'canCheckInOut', 'isSunday', 'isHoliday', 'onLeave'));
    }
    // Assigned Task
    public function assignedTask()
    {
        $tasks = Task::with('user')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('employee.assigned', compact('tasks'));
    }

    // Update status of Task 
    public function updateStatus(Request $request)
    {
        $task = Task::findOrFail($request->id);
        $task->status = $request->status;
        $task->save();
        return response()->json(['message' => 'Task status updated successfully!']);
    }
    // Employee Attandance 
    // public function attendance()
    // {
    //     // All attendance records (paginated) with user relation
    //     $employee = Attendance::with('user')
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(10);

    //     // --- Total late time for the logged-in user ---
    //     $records = Attendance::where('user_id', Auth::id())
    //         ->whereNotNull('check_in_at')
    //         ->get(['work_date', 'check_in_at']);

    //     $totalSeconds = 0;

    //     foreach ($records as $rec) {
    //         $checkIn = Carbon::parse($rec->check_in_at);

    //         // Anchor shift start (10 AM) to record’s date
    //         $shiftStart = $rec->work_date
    //             ? Carbon::parse($rec->work_date)->setTime(10, 0, 0)
    //             : $checkIn->copy()->setTime(10, 0, 0);

    //         if ($checkIn->gt($shiftStart)) {
    //             $totalSeconds += $shiftStart->diffInSeconds($checkIn);
    //         }
    //     }

    //     $totalLateTime = $totalSeconds > 0
    //         ? TimeHelper::formatSeconds($totalSeconds)
    //         : "0h 0m 0s";

    //     return view('employee.attendance', compact('employee', 'totalLateTime'));
    // }
    public function attendance()
    {
        $user = Auth::user();
        $startDate = Carbon::now()->startOfMonth();
        $endDate   = Carbon::now()->endOfMonth();
        // All days in the current month
        $daysInMonth = $startDate->daysUntil($endDate->copy()->addDay());
        // Get attendance records for this user
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('work_date', [$startDate, $endDate])
            ->get()
            ->keyBy(fn($att) => $att->work_date->toDateString());
        // Get holidays
        $holidays = Holiday::all();
        // Get approved leaves
        $leaves = Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();
        $calendar = [];
        foreach ($daysInMonth as $day) {
            $dateStr = $day->toDateString();
            $attendance = $attendances[$dateStr] ?? null;
            $status = 'absent';
            if ($day->isSunday()) {
                $status = 'sunday';
            } elseif ($holidays->where('date', '<=', $dateStr)->where('end_date', '>=', $dateStr)->count()) {
                $status = 'holiday';
            } elseif ($leave = $leaves->where('start_date', '<=', $dateStr)->where('end_date', '>=', $dateStr)->first()) {
                $status = 'on_leave';
            } elseif ($attendance) {
                $status = $attendance->status ?? 'present';
            }
            $calendar[] = [
                'date'         => $dateStr,
                'check_in_at'  => $attendance->check_in_at ?? null,
                'check_out_at' => $attendance->check_out_at ?? null,
                'worked_time'  => $attendance?->worked_time,
                'late_time'    => $attendance?->late_time,
                'overtime'     => $attendance?->overtime ?? null,
                'status'       => $status,
            ];
        }
        return view('employee.attendance_summary', compact('user', 'calendar'));
    }
    // Show Holiday 
    public function holiday()
    {
        $holidays = Holiday::all();
        return view('employee.holiday', compact('holidays'));
    }

    public function employeeFeedback()
    {
        $userId = auth()->id(); // get logged-in user ID

        $feedbacks = Feedback::with('employee')
            ->where('employee_id', $userId) // only their feedbacks
            ->get();

        return view('employee.feedback', compact('feedbacks'));
    }
}
