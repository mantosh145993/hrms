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
                            <th>Days</th>
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

                            <!-- Start Date -->
                            <td>
                                @if($leave->status == 'pending')
                                <input type="date"
                                    name="start_date"
                                    class="form-control form-control-sm leave-date-input"
                                    value="{{ $leave->start_date }}"
                                    data-id="{{ $leave->id }}"
                                    data-field="start_date"
                                    style="min-width: 140px;">
                                @else
                                <span class="badge bg-info text-white px-3 py-2">{{ $leave->start_date }}</span>
                                @endif
                            </td>

                            <!-- End Date -->
                            <td>
                                @if($leave->status == 'pending')
                                <input type="date"
                                    name="end_date"
                                    class="form-control form-control-sm leave-date-input"
                                    value="{{ $leave->end_date }}"
                                    data-id="{{ $leave->id }}"
                                    data-field="end_date"
                                    style="min-width: 140px;">
                                @else
                                <span class="badge bg-info text-white px-3 py-2">{{ $leave->end_date }}</span>
                                @endif
                            </td>
                            <td><span class="badge bg-warning text-dark">{{ $leave->days }} Days</span></td>

                            <!-- Type -->
                            <td>

                                <span class="badge text-white bg-success px-3 py-2">{{$leave->leaveType->name ?? 'N/A' }}</span>

                            </td>

                            <!-- Reason -->
                            <td>
                                <input type="text"
                                    name="reason"
                                    id="reason-input-{{ $leave->id }}"
                                    value="{{ $leave->reason }}"
                                    class="form-control form-control-sm d-inline-block w-auto"
                                    data-id="{{ $leave->id }}"
                                    {{ $leave->status != 'pending' ? 'disabled' : '' }}>
                                @if($leave->status == 'pending')
                                <button type="button"
                                    class="update-reason-btn btn btn-sm btn-primary mt-1"
                                    data-id="{{ $leave->id }}">
                                    Update
                                </button>
                                @endif
                            </td>

                            <!-- Paid Leave -->
                            <td>
                                <select name="isPaidLeavestatus"
                                    class="form-select form-select-sm status-dropdown"
                                    data-id="{{ $leave->id }}"
                                    data-field="is_paid_leave"
                                    {{ $leave->status != 'pending' ? 'disabled' : '' }}>
                                    <option value="1" {{ $leave->is_paid_leave=='1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $leave->is_paid_leave=='0' ? 'selected' : '' }}>No</option>
                                </select>
                            </td>

                            <!-- Status -->
                            <td>
                                <select name="status"
                                    class="form-select form-select-sm status-dropdown fw-bold"
                                    data-id="{{ $leave->id }}"
                                    data-field="status"
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
    $(document).ready(function() {

        // Update Start/End Date
        $('.leave-date-input').on('change', function() {
            let id = $(this).data('id');
            let field = $(this).data('field');
            let value = $(this).val();

            updateLeaveField(id, field, value);
        });

        // Update Reason
        $('.update-reason-btn').on('click', function() {
            let id = $(this).data('id');
            let value = $('#reason-input-' + id).val();

            updateLeaveField(id, 'reason', value);
        });

        // Update Status or Paid Leave
        $('.status-dropdown').on('change', function() {
            let id = $(this).data('id');
            let field = $(this).data('field');
            let value = $(this).val();
            let confirmed = confirm('Are you sure you want to accept this request?');
            if (confirmed) {
                updateLeaveField(id, field, value);
            } else {
                let previousValue = $(this).data('previous');
                $(this).val(previousValue);
            }
        });

        // Store the previous value when dropdown is focused
        $('.status-dropdown').on('focus', function() {
            $(this).data('previous', $(this).val());
        });


        // Common function
        function updateLeaveField(id, field, value) {
            $.ajax({
                url: `leaves/update/${id}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    [field]: value
                },
                success: function(response) {
                    alert(response.message);
                    if (response.reload) location.reload();
                },
                error: function(xhr) {
                    alert('Error updating field');
                }
            });
        }
    });
</script>