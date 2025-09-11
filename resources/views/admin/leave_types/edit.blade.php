@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Update Leave Type</h1>

    <form action="{{ route('leave-types.update', $leave_type->id) }}" method="POST">
        @csrf  @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{ $leave_type->name }}" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" name="description" value="{{ $leave_type->description }}" class="form-control" required>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Max Day's</label>
            <input type="number" name="max_days" value="{{  $leave_type->max_days }}" class="form-control" required>
            @error('max_days') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

         <div class="mb-3">
            <label for="form-label">Is Paid?</label>
            <select name="paid_status" class="form-control">
                <option value="1" {{ $leave_type->paid_status ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$leave_type->paid_status ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('leave-types.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
