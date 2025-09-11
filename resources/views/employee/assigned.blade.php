@extends('layouts.employee')

@section('content')
<div class="container">
    <h1>Assigned Tasks</h1>
    <!-- <a href="{{ route('tasks.create') }}" class="btn btn-dark mb-3">Add Task</a> -->
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive mt-4">
        <table class="table table-bordered align-middle text-center shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>👨‍💼 Employee</th>
                    <th>📌 Title</th>
                    <th>📝 Description</th>
                    <th>📅 Assigned Date</th>
                    <th>✅ Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td class="fw-semibold">
                        {{ $task->user?->name ?? 'Unassigned' }}
                    </td>
                    <td class="text-primary fw-bold">{{ $task->title }}</td>
                    <td>{{ Str::limit($task->description, 50) }}</td>
                    <td>{{ \Carbon\Carbon::parse($task->created_at)->format('d M Y (h:i A)') }}</td>
                    <td>
                        <select name="status"
                            class="form-select form-select-sm status-dropdown fw-semibold 
                                @if($task->status == 'pending') text-warning 
                                @elseif($task->status == 'in_progress') text-primary 
                                @elseif($task->status == 'completed') text-success 
                                @endif"
                            data-id="{{ $task->id }}">
                            <option value="pending" {{ $task->status=='pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="in_progress" {{ $task->status=='in_progress' ? 'selected' : '' }}>🚧 In Progress</option>
                            <option value="completed" {{ $task->status=='completed' ? 'selected' : '' }}>✅ Completed</option>
                        </select>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="bi bi-clipboard-x fs-3"></i> <br>
                        No tasks found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $tasks->links('pagination::bootstrap-5') }}
        </div>
    </div>


</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('change', '.status-dropdown', function() {
        let taskId = $(this).data('id');
        let status = $(this).val();

        $.ajax({
            url: "{{ route('tasks.updateStatus') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: taskId,
                status: status
            },
            success: function(response) {
                alert(response.message);
            },
            error: function(xhr) {
                alert("Error updating status");
            }
        });
    });
</script>