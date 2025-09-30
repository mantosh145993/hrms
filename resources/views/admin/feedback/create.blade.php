@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Card Wrapper -->
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Submit Employee Feedback</h4>
                </div>
                <div class="card-body p-4">
                    <!-- Success Message -->
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                    @endif
                    <!-- Show Validation Errors -->
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
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
                            <label for="employee_id" class="form-label fw-bold">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-select" required>
                                <option value="">-- Select Employee --</option>
                                @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
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
                                value="{{ old('title') }}" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label for="message" class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" class="form-control" rows="4" required>{{ old('message') }}</textarea>
                        </div>

                        <!-- Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label fw-bold">Feedback Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="">-- Select Type --</option>
                                <option value="positive" {{ old('type') == 'positive' ? 'selected' : '' }}>Positive</option>
                                <option value="negative" {{ old('type') == 'negative' ? 'selected' : '' }}>Negative</option>
                                <option value="neutral" {{ old('type') == 'neutral' ? 'selected' : '' }}>Neutral</option>
                            </select>
                        </div>

                        <!-- Given By -->
                        <div class="mb-3">
                            <label for="given_by" class="form-label fw-bold">Given By {{ Auth::user()->name }}</label>
                            <input type="hidden" name="given_by" value="{{Auth::user()->id}}">
                        </div>

                        <!-- Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Submit Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection