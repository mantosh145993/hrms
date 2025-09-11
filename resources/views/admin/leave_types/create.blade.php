@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Leave Type</h1>

    <form action="{{ route('leave-types.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" name="description" value="{{ old('description') }}" class="form-control" required>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Max Day's</label>
            <input type="number" name="max_days" value="{{ old('max_days') }}" class="form-control" required>
            @error('max_days') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('leave-types.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
