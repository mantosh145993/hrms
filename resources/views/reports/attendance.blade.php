@extends('layouts.app')
@section('content')
<div class="container">
  <form method="GET" action="{{ route('attendance.report') }}" class="mb-4">
    <div class="row">
        <div class="col-md-3">
            <label>Month</label>
            <select name="month" class="form-control">
                @foreach($months as $key => $m)
                    <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>Year</label>
            <select name="year" class="form-control">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>Employee</label>
            <select name="employee_id" class="form-control">
                <option value="">All Employees</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ $employeeId == $emp->id ? 'selected' : '' }}>
                        {{ $emp->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100" type="submit">Filter</button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>Employee</th>
            <th>Total Days</th>
            <th>Present</th>
            <th>Half Day</th>
            <th>Holiday</th>
            <th>Sunday</th>
            <th>Paid Leave</th>
            <th>Unpaid Leave</th>
            <th>Absent</th>
        </tr>
    </thead>
    <tbody>
        @forelse($report as $row)
            <tr>
                <td>{{ $row->user->name ?? 'N/A' }}</td>
                <td>{{ $row->total_days }}</td>
                <td>{{ $row->present_count }}</td>
                <td>{{ $row->halfday_count }}</td>
                <td>{{ $row->holiday_count }}</td>
                <td>{{ $row->sunday_count }}</td>
                <td>{{ $row->paid_leave_count }}</td>
                <td>{{ $row->unpaid_leave_count }}</td>
                <td>{{ $row->absent_count }}</td>
            </tr>
        @empty
            <tr><td colspan="9" class="text-center">No records found</td></tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection
