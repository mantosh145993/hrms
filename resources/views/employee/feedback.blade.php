@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Feedback Report</h2>
<table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Topic</th>
                <th>Suggation</th>
                <th>Ratting</th>
                <th>Feedback By</th>
                <th>Date</th>
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
                <td>{{ $feedback->created_at }}</td>
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