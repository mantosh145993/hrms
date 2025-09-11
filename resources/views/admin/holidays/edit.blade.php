@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Holiday</h2>
    <form action="{{ route('holidays.update',$holiday) }}" method="POST">
        @csrf @method('PUT')
        
        <div class="form-group">
            <label for="date">From</label>
            <input type="date" name="date" class="form-control" value="{{ $holiday->date }}" required>
        </div>
        
         <div class="form-group">
            <label for="date">To</label>
            <input type="date" name="end_date" class="form-control" value="{{ $holiday->end_date }}" required>
        </div>
        
        <div class="form-group">
            <label for="title">Holiday Title</label>
            <input type="text" name="title" class="form-control" value="{{ $holiday->title }}" required>
        </div>

        <div class="form-group">
            <label for="is_paid">Is Paid?</label>
            <select name="is_paid" class="form-control">
                <option value="1" {{ $holiday->is_paid ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$holiday->is_paid ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('holidays.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
