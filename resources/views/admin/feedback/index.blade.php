@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Employee Feedback</h1>
    <a href="{{ route('feedbacks.create') }}" class="btn btn-primary mb-3">Feedback</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Title</th>
                <th>Message</th>
                <th>Feedbacks</th>
                <th>By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feedbacks as $feedback)
            <tr>
                <td>{{ $feedback->employee?->name ?? 'N/A' }}</td>
                <td>{{ $feedback->title }}</td>
                <td>{{ Str::limit($feedback->message, 120) }}</td>
                <td>
                    @if($feedback->type === 'positive')
                    <span class="text-success">
                        ★★★★☆ {{-- 4/5 stars --}}
                    </span>
                    <small class="ms-1">Positive</small>
                    @elseif($feedback->type === 'negative')
                    <span class="text-danger">
                        ★☆☆☆☆ {{-- 1/5 stars --}}
                    </span>
                    <small class="ms-1">Negative</small>
                    @elseif($feedback->type === 'neutral')
                    <span class="text-warning">
                        ★★★☆☆ {{-- 3/5 stars --}}
                    </span>
                    <small class="ms-1">Neutral</small>
                    @endif
                </td>

                <td>{{ $feedback->givenBy?->name ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('feedbacks.edit', $feedback) }}" class="btn btn-warning btn-sm">Update Feedback</a>
                    <form action="{{ route('feedbacks.destroy', $feedback) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete this feedback?')" class="btn btn-danger btn-sm">Remove</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No feedbacks found</td>
            </tr>
            @endforelse
        </tbody>
    </table>


</div>
@endsection