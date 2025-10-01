<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class AttendanceReportExport implements FromView
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function view(): View
    {
        $report = Attendance::select(
                'user_id',
                DB::raw("SUM(status = 'present') as present_count"),
                DB::raw("SUM(status = 'half_day') as halfday_count"),
                DB::raw("SUM(status = 'holiday') as holiday_count"),
                DB::raw("SUM(status = 'sunday') as sunday_count"),
                DB::raw("SUM(status = 'on_leave') as leave_count"),
                DB::raw("SUM(CASE WHEN status = 'on_leave' AND is_paid_leave = 1 THEN 1 ELSE 0 END) as paid_leave_count"),
                DB::raw("SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count")
            )
            ->whereBetween('work_date', [$this->startDate, $this->endDate])
            ->whereHas('user', fn($q) => $q->where('role', 'employee'))
            ->groupBy('user_id')
            ->with('user:id,name')
            ->get();

        return view('exports.attendance_report', [
            'report'    => $report,
            'startDate' => $this->startDate,
            'endDate'   => $this->endDate,
        ]);
    }
}
