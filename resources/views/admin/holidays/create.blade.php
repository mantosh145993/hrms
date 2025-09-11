@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Holiday</h2>
    <form action="{{ route('holidays.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="date">From</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="date">To</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="title">Holiday Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter holiday name" required>
        </div>

        <div class="form-group">
            <label for="is_paid">Is Paid?</label>
            <select name="is_paid" class="form-control">
                <option value="1">Yes</option>
                <option value="0" selected>No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('holidays.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
