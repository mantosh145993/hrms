@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">

    <!-- Filter Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-funnel-fill me-2"></i> Attendance Report Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.report') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="fw-semibold">Month</label>
                        <select name="month" class="form-select">
                            @foreach($months as $key => $m)
                                <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="fw-semibold">Year</label>
                        <select name="year" class="form-select">
                            @foreach($years as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="fw-semibold">Employee</label>
                        <select name="employee_id" class="form-select">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ $employeeId == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('attendance.report') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-people-fill me-2 text-primary"></i> Attendance Summary</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Employee</th>
                        <th>Total Days</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Half Day</th>
                        <th>Holiday</th>
                        <th>Sunday</th>
                        <th>Paid Leave</th>
                        <th>Unpaid Leave</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report as $row)
                        <tr>
                            <td class="fw-semibold">{{ $row->user->name ?? 'N/A' }}</td>
                            <td>{{ $row->total_days }}</td>
                            <td class="text-success fw-semibold">{{ $row->present_count }}</td>
                            <td class="text-danger fw-semibold">{{ $row->absent_count }}</td>
                            <td>{{ $row->halfday_count }}</td>
                            <td>{{ $row->holiday_count }}</td>
                            <td>{{ $row->sunday_count }}</td>
                            <td>{{ $row->paid_leave_count }}</td>
                            <td>{{ $row->unpaid_leave_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">
                                <i class="bi bi-exclamation-circle"></i> No records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
