@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            Attendance Report - {{ $user->name }} 
            <small class="text-muted">({{ now()->format('F Y') }})</small>
        </h2>
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table align-middle text-center mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Worked Time</th>
                    <th>Late Time</th>
                    <th>Overtime</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($calendar as $day)
                    @php
                        $rowClass = match($day['status']) {
                            'present' => 'table-success',
                             'half_day' => 'table-success',
                            'absent' => 'table-danger',
                            'on_leave' => 'table-warning',
                            'holiday' => 'table-info',
                            'sunday' => 'table-secondary',
                            default => ''
                        };
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td class="fw-semibold">{{ \Carbon\Carbon::parse($day['date'])->format('d M Y (D)') }}</td>
                        <td>{{ $day['check_in_at'] ?? '-' }}</td>
                        <td>{{ $day['check_out_at'] ?? '-' }}</td>
                        <td>{{ $day['worked_time'] ?? 'N/A' }}</td>
                        <td>{{ $day['late_time'] ?? '-' }}</td>
                        <td>{{ $day['overtime'] ?? '-' }}</td>
                        <td>
                            @switch($day['status'])
                                @case('present') <span class="badge bg-success text-white px-3 py-2 text-">Present</span> @break
                                 @case('half_day') <span class="badge bg-success text-white px-3 py-2 text-">Half Day</span> @break
                                @case('absent') <span class="badge bg-danger text-white px-3 py-2">Absent</span> @break
                                @case('on_leave') <span class="badge bg-warning text-white text-dark px-3 py-2">On Leave</span> @break
                                @case('holiday') <span class="badge bg-info text-white px-3 py-2">Holiday</span> @break
                                @case('sunday') <span class="badge bg-secondary text-white px-3 py-2">Sunday</span> @break
                                @default <span class="badge bg-light text-dark">-</span>
                            @endswitch
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
