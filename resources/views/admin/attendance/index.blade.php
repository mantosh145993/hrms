@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="mb-4 p-3 rounded" style="background: linear-gradient(90deg, #0D5EF4, #4AC0F0); color: white;">
        <h2 class="mb-0">📊 Attendance Report</h2>
        <small>Track attendance of employees with summary and export options</small>
    </div>

    <!-- Export Button -->
    <div class="mb-3 text-end">
        <a id="exportBtn"
            href="{{ route('attendance.report.export', request()->only(['user_id','from','to'])) }}"
            class="btn btn-outline-light fw-semibold"
            style="background-color:#0D5EF4; color:white; border:none;">
            ⬇️ Export to Excel
        </a>
    </div>

    <!-- Filter Card -->
    <div class=" mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label>User</label>
                    <select name="user_id" class="form-control">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>From</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-3">
                    <label>To</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-50 mr-2">Filter</button>
                    <a href="{{ route('attendance.index') }}" class="btn btn-danger w-50">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="table-responsive shadow-sm">
        <table id="attendanceTable" class="table table-bordered align-middle text-center" style="background: linear-gradient(90deg, #0D5EF4, #4AC0F0); color: white;">
            <thead class="table-dark">
                <tr>
                    <th>User</th>
                    <th>Work Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Worked Hours</th>
                    <th>Late Time</th>
                    <th>Overtime</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name ?? 'N/A' }}</td>
                    <td>{{\Carbon\Carbon::parse($attendance->work_date)->format('d M Y (D)') ?? 'N/A' }}</td>
                    <td>{{ $attendance->check_in_at ? $attendance->check_in_at->format('H:i:s') : 'N/A' }}</td>
                    <td>{{ $attendance->check_out_at ? $attendance->check_out_at->format('H:i:s') : 'N/A' }}</td>
                    <td>{{ $attendance->worked_time ?? 'N/A' }}</td>
                    <td>{{ $attendance->late_time ?? 'N/A' }}</td>
                    <td>{{ $attendance->overtime ?? 'N/A' }}</td>
                    <td>
                        @php
                        $statusColors = [
                        'present' => 'success',
                        'absent' => 'danger',
                        'half_day' => 'warning',
                        'on_leave' => 'info',
                        'sunday' => 'secondary',
                        'holiday' => 'primary'
                        ];
                        $color = $statusColors[$attendance->status] ?? 'dark';
                        @endphp
                        <span class="badge bg-{{ $color }} text-white ">{{ ucfirst(str_replace('_',' ',$attendance->status)) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">No attendance records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if ($attendances->hasPages())
<div class="d-flex justify-content-center mt-4">
    <nav>
        <ul class="pagination pagination-sm shadow-sm">
            {{-- Previous Page Link --}}
            @if ($attendances->onFirstPage())
                <li class="page-item disabled"><span class="page-link">«</span></li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $attendances->previousPageUrl() }}" rel="prev">«</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($attendances->links()->elements[0] as $page => $url)
                @if ($page == $attendances->currentPage())
                    <li class="page-item active">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($attendances->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $attendances->nextPageUrl() }}" rel="next">»</a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link">»</span></li>
            @endif
        </ul>
    </nav>
</div>
@endif

    </div>
</div>

<!-- Optional: Add table hover and striped rows -->
<style>
    #attendanceTable tbody tr:hover {
        /* background-color: #f1f7ff; */
        transition: 0.2s;
    }

    #attendanceTable th,
    #attendanceTable td {
        vertical-align: middle;
    }
</style>

@endsection