<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{Feedback, Task, Holiday, Leave, LeaveType, User};
use App\Helpers\TimeHelper;
use Illuminate\Support\Facades\Hash;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Calculate duration from DOJ to now
        $doj = $user->doj ? Carbon::parse($user->doj) : null;
        $now = Carbon::now();

        if ($doj) {
            $years = (int) $doj->diffInYears($now);
            $months = (int) ($doj->diffInMonths($now) - ($years * 12));
            $duration = '';
            if ($years > 0) {
                $duration .= $years . ' Year' . ($years > 1 ? 's ' : ' ');
            }
            if ($months > 0) {
                $duration .= $months . ' Month' . ($months > 1 ? 's' : '');
            }
            if ($duration === '') {
                $duration = 'Less than 1 Month';
            }
        } else {
            $duration = 'N/A';
        }
        $today = Carbon::today()->toDateString();
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
            $canCheckInOut = false;
        }
        return view('employee.dashboard', compact('attendance', 'canCheckInOut', 'isSunday', 'isHoliday', 'onLeave','duration'));
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
    //     $user = Auth::user();
    //     $startDate = Carbon::now()->startOfMonth();
    //     $endDate   = Carbon::now()->endOfMonth();
    //     // All days in the current month
    //     $daysInMonth = $startDate->daysUntil($endDate->copy()->addDay());
    //     // Get attendance records for this user
    //     $attendances = Attendance::where('user_id', $user->id)
    //         ->whereBetween('work_date', [$startDate, $endDate])
    //         ->get()
    //         ->keyBy(fn($att) => $att->work_date->toDateString());
    //     // Get holidays
    //     $holidays = Holiday::all();
    //     // Get approved leaves
    //     $leaves = Leave::where('user_id', $user->id)
    //         ->where('status', 'approved')
    //         ->get();
    //     $calendar = [];
    //     foreach ($daysInMonth as $day) {
    //         $dateStr = $day->toDateString();
    //         $attendance = $attendances[$dateStr] ?? null;
    //         $status = 'absent';
    //         if ($day->isSunday()) {
    //             $status = 'sunday';
    //         } elseif ($holidays->where('date', '<=', $dateStr)->where('end_date', '>=', $dateStr)->count()) {
    //             $status = 'holiday';
    //         } elseif ($leave = $leaves->where('start_date', '<=', $dateStr)->where('end_date', '>=', $dateStr)->first()) {
    //             $status = 'on_leave';
    //         } elseif ($attendance) {
    //             $status = $attendance->status ?? 'present';
    //         }
    //         $calendar[] = [
    //             'date'         => $dateStr,
    //             'check_in_at'  => $attendance->check_in_at ?? null,
    //             'check_out_at' => $attendance->check_out_at ?? null,
    //             'worked_time'  => $attendance?->worked_time,
    //             'late_time'    => $attendance?->late_time,
    //             'overtime'     => $attendance?->overtime ?? null,
    //             'status'       => $status,
    //         ];
    //     }
    //     return view('employee.attendance_summary', compact('user', 'calendar'));
    // }

    public function attendance(Request $request)
{
    $user = Auth::user();

    // Get month & year from request (defaults to current)
    $month = $request->input('month', Carbon::now()->month);
    $year  = $request->input('year', Carbon::now()->year);

    // Define start and end of selected month
    $startDate = Carbon::create($year, $month, 1)->startOfMonth();
    $endDate   = Carbon::create($year, $month, 1)->endOfMonth();

    // Get all days in the selected month
    $daysInMonth = $startDate->daysUntil($endDate->copy()->addDay());

    // Fetch attendance for this user and month
    $attendances = Attendance::where('user_id', $user->id)
        ->whereBetween('work_date', [$startDate, $endDate])
        ->get()
        ->keyBy(fn($att) => $att->work_date->toDateString());

    // Fetch holidays & approved leaves
    $holidays = Holiday::all();
    $leaves = Leave::where('user_id', $user->id)
        ->where('status', 'approved')
        ->get();

    // Build the monthly calendar array
    $calendar = [];
    foreach ($daysInMonth as $day) {
        $dateStr = $day->toDateString();
        $attendance = $attendances[$dateStr] ?? null;
        $status = 'absent';

        if ($day->isSunday()) {
            $status = 'sunday';
        } elseif ($holidays->where('date', '<=', $dateStr)->where('end_date', '>=', $dateStr)->count()) {
            $status = 'holiday';
        } elseif ($leaves->where('start_date', '<=', $dateStr)->where('end_date', '>=', $dateStr)->first()) {
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
            'overtime'     => $attendance?->overtime,
            'status'       => $status,
        ];
    }

    return view('employee.attendance_summary', compact('user', 'calendar', 'month', 'year'));
}
    // Show Holiday 
    public function holiday()
    {
        $holidays = Holiday::all();
        return view('employee.holiday', compact('holidays'));
    }
    // Employee Feedback
    public function employeeFeedback()
    {
        $userId = auth()->id(); // get logged-in user ID

        $feedbacks = Feedback::with('employee')
            ->where('employee_id', $userId) // only their feedbacks
            ->get();

        return view('employee.feedback', compact('feedbacks'));
    }
    // Show Profile
    public function profileUpdateShow()
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        return view('Auth.edit', compact('user'));
    }
    // Update Profile 
    public function profileUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required',
            'password' => 'nullable|string|min:8|',
        ]);
        $data = $request->only(['name', 'email']);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}
