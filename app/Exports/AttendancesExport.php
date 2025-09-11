<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class AttendancesExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Attendance::with('user')->orderBy('work_date', 'desc');

        // Apply filters if any
        if (!empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }
        if (!empty($this->filters['from'])) {
            $query->whereDate('work_date', '>=', $this->filters['from']);
        }
        if (!empty($this->filters['to'])) {
            $query->whereDate('work_date', '<=', $this->filters['to']);
        }

        $attendances = $query->get();

        // Group by user
        $users = $attendances->groupBy('user_id');
        $rows = collect();

        foreach ($users as $userId => $userAttendances) {
            $userName = $userAttendances->first()->user->name;

            // Calculate summary
            $totalDays = $userAttendances->count();
            $present = $userAttendances->where('status', 'present')->count();
            $absent = $userAttendances->where('status', 'absent')->count();
            $halfDay = $userAttendances->where('status', 'half_day')->count();
            $onLeavePaid = $userAttendances->where('status', 'on_leave')->where('is_paid_leave', 1)->count();
            $onLeaveUnpaid = $userAttendances->where('status', 'on_leave')->where('is_paid_leave', 0)->count();
            $holiday = $userAttendances->where('status', 'holiday')->count();
            $sunday = $userAttendances->where('status', 'sunday')->count();

            // Add individual attendance rows
            foreach ($userAttendances as $attendance) {
                $rows->push([
                    'User' => $userName,
                    'Date' => $attendance->work_date,
                    'Check In' => $attendance->check_in_at ? $attendance->check_in_at->format('H:i') : '-',
                    'Check Out' => $attendance->check_out_at ? $attendance->check_out_at->format('H:i') : '-',
                    'Worked Hours' => $attendance->worked_minutes ? gmdate('H:i', $attendance->worked_minutes * 60) : '-',
                    'Late Time' => $attendance->late_minutes ? gmdate('H:i', $attendance->late_minutes * 60) : '-',
                    'Overtime' => $attendance->overtime,
                    'Status' => ucfirst(str_replace('_', ' ', $attendance->status)),
                ]);
            }

            // Add summary row for the user
            $rows->push([
                'User' => $userName . ' - Summary',
                'Date' => 'Total Days: ' . $totalDays,
                'Check In' => 'Present: ' . $present,
                'Check Out' => 'Absent: ' . $absent,
                'Worked Hours' => 'Half Day: ' . $halfDay,
                'Late Time' => 'Paid Leave: ' . $onLeavePaid,
                'Overtime' => 'Unpaid Leave: ' . $onLeaveUnpaid,
                'Status' => 'Holiday: ' . $holiday . ' | Sunday: ' . $sunday,
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'User',
            'Date',
            'Check In',
            'Check Out',
            'Worked Hours',
            'Late Time',
            'Overtime',
            'Status',
        ];
    }
}
