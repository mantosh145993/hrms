@extends('layouts.app')

@section('content')
<style>
    /* Card Styling */
    .form-card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease-in-out;
    }
    .form-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    /* Gradient Header */
    .form-header {
        background: linear-gradient(45deg, #198754, #20c997);
        color: #fff !important;
        padding: 15px;
    }

    /* Input Focus */
    .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 6px rgba(25, 135, 84, 0.5);
    }

    /* Animated Button */
    .btn-animated {
        position: relative;
        overflow: hidden;
    }
    .btn-animated::after {
        content: "";
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: rgba(255,255,255,0.3);
        transition: all 0.5s;
    }
    .btn-animated:hover::after {
        left: 0;
    }
</style>

<div class="container my-5">
    <div class="card shadow-lg border-0 form-card">
        <div class="form-header">
            <h4 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i> Add New Employee</h4>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">👨‍💼 Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter employee name" required>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">📧 Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter employee email" required>
                </div>

                {{-- Role --}}
                <div class="mb-3">
                    <label for="role" class="form-label fw-bold">🎭 Role</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="">-- Select Role --</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="employee">Employee</option>
                    </select>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">🔑 Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter a strong password" required>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-animated">
                        <i class="bi bi-arrow-left-circle me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success btn-animated">
                        <i class="bi bi-save me-1"></i> Save Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
