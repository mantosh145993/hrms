@extends('layouts.employee')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="row">
        <div class="col-sm-4">
            <h1 class=" fw-bold text-primary"> Apply Leave</h1>
            <form action="{{ route('leaves.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" class="form-control" required readonly>
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control" required>
                        @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control" required>
                        @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Leave Type</label>
                        <select name="type" class="form-control">
                            <option value="">-- Select Type --</option>
                            @foreach($leaveTypes as $leaveType)
                            <option value="{{ $leaveType->id }}" {{ old('leaveType') == $leaveType->id ? 'selected' : '' }}>
                                {{ $leaveType->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" rows="3" class="form-control" required>{{ old('reason') }}</textarea>
                        @error('reason') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
        <div class="col-sm-8 mt-4">
            <h3 class="mb-4 text-center fw-bold text-primary">📝 Applied Leaves</h3>

            @if($leaves->isEmpty())
            <div class="alert alert-info text-center shadow-sm rounded-3">
                No leaves applied yet.
            </div>
            @else
            <div class="card shadow-lg rounded-3 border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-dark text-white">
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Type</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Paid?</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $leave)
                                <tr>
                                    <td class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y (D)') }}
                                    </td>
                                    <td class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y (D)') }}
                                    </td>
                                    <td>
                                        @if($leave->type == 8)
                                        <span class="badge bg-primary text-white px-3 py-2">PL</span>
                                        @else
                                        <span class="badge bg-secondary text-white px-3 py-2">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $leave->reason }}</td>
                                    <td>
                                        @switch($leave->status)
                                        @case('approved')
                                        <span class="badge bg-success text-white px-3 py-2">Approved</span>
                                        @break
                                        @case('pending')
                                        <span class="badge bg-warning text-white text-dark px-3 py-2">Pending</span>
                                        @break
                                        @case('rejected')
                                        <span class="badge bg-danger text-white px-3 py-2">Rejected</span>
                                        @break
                                        @default
                                        <span class="badge bg-light text-white text-dark px-3 py-2">-</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($leave->is_paid_leave)
                                        <span class="badge bg-success text-white px-3 py-2">✅ Yes</span>
                                        @else
                                        <span class="badge bg-danger text-white px-3 py-2">❌ No</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection