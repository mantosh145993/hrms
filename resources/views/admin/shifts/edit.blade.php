@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Shift</h1>

    <form action="{{ route('shifts.update', $shift) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Shift Name</label>
            <input type="text" name="name" value="{{ old('name', $shift->name) }}" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Start Time</label>
            <input type="time" name="start_time" value="{{ old('start_time', $shift->start_time) }}" class="form-control" required>
            @error('start_time') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">End Time</label>
            <input type="time" name="end_time" value="{{ old('end_time', $shift->end_time) }}" class="form-control" required>
            @error('end_time') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Grace Minutes</label>
            <input type="number" name="grace_minutes" value="{{ old('grace_minutes', $shift->grace_minutes) }}" class="form-control">
            @error('grace_minutes') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Half Day After Minutes</label>
            <input type="number" name="half_day_after_minutes" value="{{ old('half_day_after_minutes', $shift->half_day_after_minutes) }}" class="form-control">
            @error('half_day_after_minutes') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Workday Minutes</label>
            <input type="number" name="workday_minutes" value="{{ old('workday_minutes', $shift->workday_minutes) }}" class="form-control">
            @error('workday_minutes') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('shifts.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
