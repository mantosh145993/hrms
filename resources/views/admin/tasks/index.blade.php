@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Add Feedback</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('feedbacks.store') }}" method="POST">
        @csrf

        <!-- Employee -->
        <div class="mb-3">
            <label for="employee_id" class="form-label">Employee</label>
            <select name="employee_id" id="employee_id" class="form-select" required>
                <option value="">Select Employee</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Title -->
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <!-- Message -->
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" id="message" class="form-control" rows="4" required>{{ old('message') }}</textarea>
        </div>

        <!-- Type -->
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="">Select Type</option>
                <option value="positive" {{ old('type') == 'positive' ? 'selected' : '' }}>Positive</option>
                <option value="negative" {{ old('type') == 'negative' ? 'selected' : '' }}>Negative</option>
                <option value="neutral" {{ old('type') == 'neutral' ? 'selected' : '' }}>Neutral</option>
            </select>
        </div>

        <!-- Given By -->
        <div class="mb-3">
            <label for="given_by" class="form-label">Given By (Manager/User)</label>
            <select name="given_by" id="given_by" class="form-select">
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('given_by') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit Feedback</button>
    </form>
</div>
@endsection
