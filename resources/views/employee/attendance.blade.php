@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Attendance Report</h2>

    {{-- Attendance Table --}}
    <div class="table-responsive">
        <p><strong>Total Late Time: {{ $totalLateTime }}</strong></p>
        <table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Work Date</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Worked Time</th>
            <th>Late Time</th> <!-- 👈 Add this -->
            <th>Overtime</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($employee as $attendance)
            <tr>
                <td>{{ $attendance->user->name }}</td>
                <td>{{ $attendance->work_date }}</td>
                <td>{{ $attendance->check_in_at ? $attendance->check_in_at->format('H:i') : '-' }}</td>
                <td>{{ $attendance->check_out_at ? $attendance->check_out_at->format('H:i') : '-' }}</td>
                <td>{{ $attendance->worked_time }}</td>
                <td>{{ $attendance->late_time }}</td> <!-- 👈 Show per-day late time -->
                <td>{{ $attendance->overtime }}</td>
                <td>{{ $attendance->status }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No attendance records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>


    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $employee->links() }}
    </div>
</div>

@endsection