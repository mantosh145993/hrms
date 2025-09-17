@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 rounded-top-4">
            <h3 class="mb-0">📌 Approve or Reject Leave</h3>
        </div>

        <div class="card-body">
            {{-- Success Message --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- If No Leaves --}}
            @if($leaves->isEmpty())
            <div class="alert alert-info text-center fw-bold">
                ℹ️ No leaves applied yet.
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-dark text-white">
                        <tr>
                            <th>👨‍💼 Employee</th>
                            <th>📅 Start Date</th>
                            <th>📅 End Date</th>
                            <th>🌿 Type</th>
                            <th>📝 Reason</th>
                            <th>💰 Paid?</th>
                            <th>✅ Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $leave)
                        <tr>
                            <td class="fw-semibold">{{ $leave->user->name }}</td>
                            <td><span class="badge bg-info  text-white px-3 py-2">{{ $leave->start_date }}</span></td>
                            <td><span class="badge bg-info text-white px-3 py-2">{{ $leave->end_date }}</span></td>
                            <td>
                                @if($leave->type == 8)
                                <span class="badge text-white bg-success px-3 py-2">PL</span>
                                @else
                                <span class="badge bg-secondary text-white px-3 py-2">N/A</span>
                                @endif
                            </td>
                            <td>
                                <input type="text"
                                    name="reason"
                                    id="reason-input-{{ $leave->id }}"
                                    value="{{ $leave->reason }}"
                                    data-id="{{ $leave->id }}">

                                <button type="button"
                                    class="update-reason-btn btn btn-sm btn-primary"
                                    data-id="{{ $leave->id }}">
                                    Update
                                </button>
                            </td>

                            <td>
                                <select name="isPaidLeavestatus" class="form-select form-select-sm status-dropdown "
                                    data-id="{{ $leave->id }}" data-field="is_paid_leave">
                                    <option value="1" {{ $leave->is_paid_leave=='1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $leave->is_paid_leave=='0' ? 'selected' : '' }}>No</option>
                                </select>
                            </td>
                            <td>
                                <select name="status" class="form-select form-select-sm status-dropdown fw-bold"
                                    data-id="{{ $leave->id }}" data-field="status"
                                    style="min-width: 120px;">
                                    <option value="pending" class="text-warning fw-bold"
                                        {{ $leave->status=='pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" class="text-success fw-bold"
                                        {{ $leave->status=='approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" class="text-danger fw-bold"
                                        {{ $leave->status=='rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('change', '.status-dropdown', function() {
        let taskId = $(this).data('id');
        let field = $(this).data('field'); // which field: status or is_paid_leave
        let value = $(this).val();
        $.ajax({
            url: "{{ route('leave.status') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: taskId,
                field: field,
                value: value
            },
            success: function(response) {
                alert(response.message);
            },
            error: function(xhr) {
                alert("Error updating status");
            }
        });
    });
    $(document).on('click', '.update-reason-btn', function() {
        let leaveId = $(this).data('id');
        let reason = $('#reason-input-' + leaveId).val();
        $.ajax({
            url: "{{ route('update.reason') }}", // your route
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: leaveId,
                reason: reason
            },
            success: function(response) {
                alert(response.message); // show success message
            },
            error: function(xhr) {
                alert("Something went wrong!");
            }
        });
    });
</script>