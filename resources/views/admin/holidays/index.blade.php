@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Holidays</h2>
    <a href="{{ route('holidays.create') }}" class="btn btn-primary mb-3">Add Holiday</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>From</th>
                <th>To</th>
                <th>Days</th>
                <th>Title</th>
                <th>Paid?</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($holidays as $holiday)
            <tr>
                <td>{{ $holiday->date }}</td>
                <td>{{ $holiday->end_date }}</td>
                <td>{{ $holiday->max_days }}</td>
                <td>{{ $holiday->title }}</td>
                <td>{{ $holiday->is_paid ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('holidays.edit',$holiday) }}" class="btn btn-sm btn-warning">Update</a>
                    <form action="{{ route('holidays.destroy',$holiday) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this holiday?')">Remove</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
