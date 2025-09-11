@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Export Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">
            📊 Attendance Report 
            <small class="text-muted fs-6">({{ $startDate }} → {{ $endDate }})</small>
        </h3>
        <a href="{{ route('attendance.report.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
            class="btn btn-success shadow-sm px-4">
            ⬇️ Export to Excel
        </a>
    </div>

    {{-- Report Table --}}
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 text-center">
                    <thead class="table-dark text-white">
                        <tr>
                            <th>👨‍💼 Employee</th>
                            <th class="bg-success">✔ Present</th>
                            <th class="bg-warning text-dark">⏳ Half Day</th>
                            <th class="bg-info text-white">🏖 Holiday</th>
                            <th class="bg-secondary">🌞 Sunday</th>
                            <th class="bg-primary">📄 On Leave</th>
                            <th class="bg-teal text-white">💰 Paid Leave</th>
                            <th class="bg-danger">❌ Absent</th>
                            <th class="bg-dark">📅 Total Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report as $row)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $row->user->name }}</td>
                                <td><span class="badge bg-success text-white px-3 py-2">{{ $row->present_count }}</span></td>
                                <td><span class="badge bg-warning text-dark px-3 py-2">{{ $row->halfday_count }}</span></td>
                                <td><span class="badge bg-info text-white px-3 py-2">{{ $row->holiday_count }}</span></td>
                                <td><span class="badge bg-secondary text-white px-3 py-2">{{ $row->sunday_count }}</span></td>
                                <td><span class="badge bg-primary text-dark px-3 py-2">{{ $row->leave_count }}</span></td>
                                <td><span class="badge bg-teal px-3 py-2">{{ $row->unpaid_leave_count }}</span></td>
                                <td><span class="badge bg-danger px-3 text-white py-2">{{ $row->absent_count }}</span></td>
                                <td class="fw-bold text-dark">
                                    {{ $row->present_count + $row->halfday_count + $row->holiday_count + $row->sunday_count + $row->leave_count }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-emoji-frown fs-3"></i><br>
                                    No data available for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
