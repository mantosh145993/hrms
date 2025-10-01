<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Attendance, Holiday, Payroll, Shift, User};
use Carbon\Carbon;
use App\Exports\AttendancesExport;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function dashboard()
    {
        $today = now('Asia/Kolkata')->toDateString();
        $present = Attendance::where('work_date', $today)->whereNotNull('check_in_at')->count();
        $absent  = Attendance::where('work_date', $today)->whereNull('check_in_at')->count();
        $lateTop = Attendance::where('work_date', $today)->orderByDesc('late_minutes')->take(10)->get(['user_id', 'late_minutes']);
        return view('admin.dashboard', compact('present', 'absent', 'lateTop'));
    }

    // public function index(Request $request)
    // {
    //     $query = Attendance::with('user')->orderByDesc('work_date');

    //     if ($request->filled('user_id')) $query->where('user_id', $request->user_id);
    //     if ($request->filled('from') && $request->filled('to')) {
    //         $query->whereBetween('work_date', [$request->from, $request->to]);
    //     }

    //     // For DataTables server-side:
    //     if ($request->wantsJson()) return datatables()->eloquent($query)->toJson();

    //     return view('admin.attendance.index');
    // }

    public function index(Request $request)
    {
        $users = User::orderBy('name')->get();
        $attendances = Attendance::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'employee');
            })
            ->orderBy('work_date', 'desc');

        if ($request->filled('user_id')) {
            $attendances->where('user_id', $request->user_id);
        }
        if ($request->filled('from')) {
            $attendances->whereDate('work_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $attendances->whereDate('work_date', '<=', $request->to);
        }
        $attendances = $attendances->paginate(25)->withQueryString();
        return view('admin.attendance.index', compact('attendances', 'users'));
    }

    public function export(Request $request)
    {
        $filters = $request->only(['user_id', 'from', 'to']);
        $fileName = 'Attendance_Report_' . date('Y_m_d_H_i_s') . '.xlsx';
        return Excel::download(new AttendancesExport($filters), $fileName);
    }

    public function getData(Request $request)
    {
        $query = Attendance::with('user');

        // Filter by employee
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        return datatables()->of($query)->make(true);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'check_in_at'  => 'nullable|date',
            'check_out_at' => 'nullable|date|after:check_in_at',
            'status'       => 'required|in:present,absent,half_day,on_leave',
        ]);

        $attendance->fill($validated);

        if ($attendance->check_in_at && $attendance->check_out_at) {
            $attendance->worked_minutes = Carbon::parse($attendance->check_in_at)->diffInMinutes($attendance->check_out_at);
        }
        // recompute late based on user's shift:
        $shift = $attendance->user->shift;
        if ($attendance->check_in_at && $shift) {
            $start = Carbon::parse($attendance->work_date . ' ' . $shift->start_time, 'Asia/Kolkata')->addMinutes($shift->grace_minutes);
            $attendance->late_minutes = Carbon::parse($attendance->check_in_at)->greaterThan($start)
                ? Carbon::parse($attendance->check_in_at)->diffInMinutes($start)
                : 0;
        }

        $attendance->save();

        return back()->with('success', 'Attendance updated.');
    }

    public function recomputeMonth(string $ym)
    {
        [$y, $m] = explode('-', $ym);
        $rows = Attendance::with('user.shift')
            ->whereYear('work_date', $y)->whereMonth('work_date', $m)->get();

        foreach ($rows as $a) {
            if ($a->check_in_at && $a->check_out_at && $a->user->shift) {
                $start = Carbon::parse($a->work_date . ' ' . $a->user->shift->start_time, 'Asia/Kolkata')
                    ->addMinutes($a->user->shift->grace_minutes);
                $a->late_minutes = Carbon::parse($a->check_in_at)->greaterThan($start)
                    ? Carbon::parse($a->check_in_at)->diffInMinutes($start)
                    : 0;
                $a->worked_minutes = Carbon::parse($a->check_in_at)->diffInMinutes($a->check_out_at);
                $a->save();
            }
        }

        return back()->with('success', 'Recomputed.');
    }
}
