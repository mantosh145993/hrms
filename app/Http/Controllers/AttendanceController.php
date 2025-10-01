<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Attendance, Holiday, Payroll, Shift, User};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\AttendanceReportExport;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function employeeDashboard()
    {
        $user = auth()->user();
        $today = now()->timezone('Asia/Kolkata')->toDateString();
        $todayRow = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'work_date' => $today],
            ['status' => 'present']
        );
        $month = now()->format('Y-m');
        $summary = Attendance::where('user_id', $user->id)
            ->whereBetween('work_date', [Carbon::parse($month . '-01'), Carbon::parse($month . '-01')->endOfMonth()])
            ->selectRaw('COUNT(*) as days, SUM(late_minutes) as late_total, SUM(worked_minutes) as worked_total')
            ->first();
        return view('Employee/Dashboard', compact('todayRow', 'summary'));
    }
    public function checkIn(Request $request)
    {
        $user = auth()->user();
        $tzNow = now()->timezone('Asia/Kolkata');
        $workDate = $tzNow->toDateString();

        $attendance = Attendance::firstOrNew([
            'user_id' => $user->id,
            'work_date' => $workDate,
        ]);

        if ($attendance->check_in_at) {
            return back()->with('info', 'Already checked in.');
        }

        // Update status from absent → present
        $attendance->status = 'present';
        $attendance->check_in_at = $tzNow;

        // shift details
        $shift = $user->shift ?: Shift::first();
        $shiftStart = Carbon::createFromFormat('Y-m-d H:i:s', $workDate . ' 09:30:00');
        $grace = $shift->grace_minutes ?? 0;

        $late = $tzNow->greaterThan($shiftStart->copy()->addMinutes($grace))
            ? $shiftStart->diffInMinutes($tzNow)
            : 0;

        $attendance->late_minutes = $late;
        $attendance->meta = [
            'check_in_ip' => $request->ip(),
            'ua' => $request->userAgent(),
            'status' => 'present',
        ];
        $attendance->save();
        return back()->with('success', 'Checked in successfully.');
    }
    public function checkOut(Request $request)
    {
        $user = auth()->user(); // ⚡ fix: use object, not name
        $tzNow = now()->timezone('Asia/Kolkata');
        $workDate = $tzNow->toDateString();
        $attendance = Attendance::where('user_id', $user->id)
            ->where('work_date', $workDate)
            ->first();
        if (!$attendance || !$attendance->check_in_at) {
            return back()->with('error', 'Please check in first.');
        }
        if ($attendance->check_out_at) {
            return back()->with('info', 'Already checked out.');
        }
        $attendance->check_out_at = $tzNow;
        // calculate worked minutes
        $attendance->worked_minutes = Carbon::parse($attendance->check_in_at)->diffInMinutes($tzNow);
        // calculate late minutes (if checked in after shift start)
        $shiftStart = $attendance->work_date
            ? Carbon::parse($attendance->work_date)->setTime(10, 0, 0) // assume 10 AM shift
            : Carbon::parse($attendance->check_in_at)->copy()->setTime(10, 0, 0);
        $checkIn = Carbon::parse($attendance->check_in_at);
        $attendance->late_minutes = $checkIn->gt($shiftStart)
            ? $shiftStart->diffInMinutes($checkIn)
            : 0;
        // infer half-day if needed
        $shift = $user->shift ?: Shift::first();
        if ($attendance->worked_minutes < ($shift->half_day_after_minutes ?? 240)) {
            $attendance->status = 'half_day';
        }
        $attendance->meta = array_merge($attendance->meta ?? [], [
            'check_out_ip' => $request->ip(),
        ]);
        $attendance->save();
        return back()->with('success', 'Checked out successfully.');
    }
    public function dayView(string $date)
    {
        $row = Attendance::where('user_id', auth::user()->id)->where('work_date', $date)->first();
        return response()->json($row);
    }
    public function monthView(string $ym)
    {
        [$y, $m] = explode('-', $ym);
        $data = Attendance::where('user_id', auth::user()->id)
            ->whereYear('work_date', $y)
            ->whereMonth('work_date', $m)
            ->orderBy('work_date')
            ->get();
        $stats = [
            'days' => $data->count(),
            'total_late_minutes' => $data->sum('late_minutes'),
            'avg_late_minutes' => round($data->avg('late_minutes'), 1),
            'total_worked_hours' => round($data->sum('worked_minutes') / 60, 1),
            'late_days' => $data->where('late_minutes', '>', 0)->count(),
        ];
        return response()->json(['data' => $data, 'stats' => $stats]);
    }
    public function attendanceReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->endOfMonth()->toDateString());
        // Total calendar days in range
        $totalDays = \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1;
        // $report = Attendance::select(
        //     'user_id',
        //     DB::raw("SUM(status = 'present') as present_count"),
        //     DB::raw("SUM(status = 'half_day') as halfday_count"),
        //     DB::raw("SUM(status = 'holiday') as holiday_count"),
        //     DB::raw("SUM(status = 'sunday') as sunday_count"),
        //     DB::raw("SUM(status = 'on_leave' AND is_paid_leave = 1) as paid_leave_count"),
        //     DB::raw("SUM(status = 'on_leave' AND is_paid_leave = 0) as unpaid_leave_count")
        // )
        //     ->whereBetween('work_date', [$startDate, $endDate])
        //      ->whereHas('user', function ($query) {
        //         $query->where('role', 'employee');
        //     })
        //     ->groupBy('user_id')
        //     ->with('user:id,name')
        //     ->get();
        $report = Attendance::select(
            'user_id',
            DB::raw("SUM(status = 'present') as present_count"),
            DB::raw("SUM(status = 'half_day') as halfday_count"),
            DB::raw("SUM(status = 'holiday') as holiday_count"),
            DB::raw("SUM(status = 'sunday') as sunday_count"),
            DB::raw("SUM(status = 'on_leave' AND is_paid_leave = 1) as paid_leave_count"),
            DB::raw("SUM(status = 'on_leave' AND is_paid_leave = 0) as unpaid_leave_count")
        )
        ->whereBetween('work_date', [$startDate, $endDate])
        ->whereHas('user', fn($q) => $q->where('role', 'employee'))
        ->groupBy('user_id')
        ->with('user:id,name')
        ->get();

        foreach ($report as $row) {
            $row->total_days = $totalDays;
            $row->absent_count = $totalDays - (
                $row->present_count +
                $row->halfday_count +
                $row->holiday_count +
                $row->sunday_count +
                $row->paid_leave_count +
                $row->unpaid_leave_count
            );
        }
        return view('reports.attendance', compact('report', 'startDate', 'endDate'));
    }
    public function exportAttendanceReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
        return Excel::download(new AttendanceReportExport($startDate, $endDate), 'attendance_report.xlsx');
    }
}
