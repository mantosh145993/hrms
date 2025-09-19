@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold text-primary">🌟Holidays 🌟</h2>

    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-success text-dark">
                        <tr>
                            <th>Date</th>
                            <th>Day's</th>
                            <th>Title</th>
                            <th>Paid?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($holidays as $holiday)
                        <tr>
                            <td class="fw-semibold">{{ \Carbon\Carbon::parse($holiday->date)->format('d M Y (D)') }}</td>
                            <td>{{$holiday->max_days }}</td>
                            <td class="text-capitalize">{{ $holiday->title }}</td>
                            <td>
                                @if($holiday->is_paid)
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
</div>
@endsection
