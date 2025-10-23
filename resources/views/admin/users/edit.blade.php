@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg border-0 rounded-4 animate__animated animate__fadeIn">
        <div class="card-header bg-gradient text-white text-center py-3 rounded-top-4" 
             style="background: linear-gradient(90deg, #667eea, #764ba2);">
            <h3 class="mb-0">✏️ Edit Employee</h3>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="name" class="fw-bold">👤 Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" 
                           class="form-control form-control-lg rounded-pill shadow-sm" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="fw-bold">📧 Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" 
                           class="form-control form-control-lg rounded-pill shadow-sm" required>
                </div>

                <div class="form-group mb-3">
                    <label for="role" class="fw-bold">🎭 Role</label>
                    <select name="role" class="form-control form-control-lg rounded-pill shadow-sm" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="designation" class="fw-bold">👤 Designation</label>
                    <input type="text" name="designation" value="{{ $user->designation }}" 
                           class="form-control form-control-lg rounded-pill shadow-sm">
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="fw-bold">🔑 Password <small>(Leave blank if not changing)</small></label>
                    <input type="password" name="password" 
                           class="form-control form-control-lg rounded-pill shadow-sm">
                </div>

                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" name="dob" value="{{$user->dob}}" id="dob" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="doc" class="form-label">Date of Confirmation</label>
                    <input type="date" name="doc" value="{{$user->doc}}" id="doc" class="form-control">
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" 
                            class="btn btn-lg px-4 rounded-pill shadow-sm text-white" 
                            style="background: linear-gradient(90deg, #667eea, #764ba2);">
                        💾 Update
                    </button>
                    <a href="{{ route('users.index') }}" 
                       class="btn btn-lg btn-outline-secondary rounded-pill shadow-sm px-4">
                        ❌ Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Optional: Animate.css for smooth entry --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection
