@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Update Feedback</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('feedbacks.update', $feedback->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Employee -->
        <div class="mb-3">
            <label for="employee_id" class="form-label fw-bold">Employee <span class="text-danger">*</span></label>
            <select name="employee_id" id="employee_id" class="form-select" required>
                <option value="">-- Select Employee --</option>
                @foreach($employees as $employee)
                <option value="{{ $employee->id }}"
                    {{ old('employee_id', $feedback->employee_id) == $employee->id ? 'selected' : '' }}>
                    {{ $employee->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Title -->
        <div class="mb-3">
            <label for="title" class="form-label fw-bold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title"
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $feedback->title) }}" required>
            @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Message -->
        <div class="mb-3">
            <label for="message" class="form-label fw-bold">Message <span class="text-danger">*</span></label>
            <textarea name="message" id="message" class="form-control" rows="4" required>{{ old('message', $feedback->message) }}</textarea>
        </div>

        <!-- Type -->
        <div class="mb-3">
            <label for="type" class="form-label fw-bold">Feedback Type <span class="text-danger">*</span></label>
            <select name="type" id="type" class="form-select" required>
                <option value="">-- Select Type --</option>
                <option value="positive" {{ old('type', $feedback->type) == 'positive' ? 'selected' : '' }}>Positive</option>
                <option value="negative" {{ old('type', $feedback->type) == 'negative' ? 'selected' : '' }}>Negative</option>
                <option value="neutral" {{ old('type', $feedback->type) == 'neutral' ? 'selected' : '' }}>Neutral</option>
            </select>
        </div>

        <!-- Given By -->
        <input type="hidden" name="given_by" value="{{ Auth::user()->id }}">

        <!-- Submit -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-paper-plane"></i> Update Feedback
            </button>
        </div>
    </form>
</div>
@endsection