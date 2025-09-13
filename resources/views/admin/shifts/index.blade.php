@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Shifts</h1>
    <a href="{{ route('shifts.create') }}" class="btn btn-primary mb-3">Add Shift</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Start</th>
                <th>End</th>
                <th>Work Minutes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($shifts as $shift)
                <tr>
                    <td>{{ $shift->name }}</td>
                    <td>{{ $shift->start_time }}</td>
                    <td>{{ $shift->end_time }}</td>
                    <td>{{ $shift->workday_minutes }}</td>
                    <td>
                        <a href="{{ route('shifts.edit', $shift) }}" class="btn btn-sm btn-warning">Update</a>
                        <form action="{{ route('shifts.destroy', $shift) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                                Remove
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No Shifts found</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $shifts->links() }}
</div>
@endsection
