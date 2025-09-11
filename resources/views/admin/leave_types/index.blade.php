@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Leave Type</h1>
    <a href="{{ route('leave-types.create') }}" class="btn btn-dark mb-3">Add Leave Type</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Max Day</th>
                <th>Is Paid</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leave_types as $leave_type)
                <tr>
                    <td>{{ $leave_type->name }}</td>
                    <td>{{ $leave_type->description }}</td>
                    <td>{{ $leave_type->max_days }}</td>
                    <td>{{ $leave_type->paid_status == '1' ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('leave-types.edit', $leave_type) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('leave-types.destroy', $leave_type) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No Leave Type found</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $leave_types->links() }}
</div>
@endsection
