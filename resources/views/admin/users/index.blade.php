@extends('layouts.admin')

@section('admin-content')
<style>
    /* Card Hover */
    .card-hover {
        transition: all 0.3s ease-in-out;
        border-radius: 10px;
    }
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    /* Gradient Header */
    .gradient-header {
        background: linear-gradient(45deg, #0d6efd, #6610f2);
        color: #fff !important;
    }

    /* Table Hover */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.08);
        transition: background 0.2s ease-in-out;
    }

    /* Action Buttons */
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

<div class="container my-4">
    <div class="card border-0 shadow card-hover">
        <div class="card-header gradient-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-people-fill me-2"></i> All Employees</h4>
            <a href="{{ route('users.create') }}" class="btn btn-light btn-sm btn-animated">
                <i class="bi bi-person-plus me-1"></i> Add Employee
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>👨‍💼 Name</th>
                            <th>📧 Email</th>
                            <th>🎭 Role</th>
                            <th>⚙️ Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td>
                                <span class="badge 
                                    @if($user->role === 'admin') bg-danger text-white
                                    @elseif($user->role === 'manager') bg-primary text-white
                                    @else bg-success text-white @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user) }}" 
                                   class="btn btn-sm btn-warning btn-animated me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" 
                                      method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger btn-animated"
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="bi bi-trash3"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if($users->isEmpty())
                        <tr>
                            <td colspan="5" class="text-muted py-3">
                                <i class="bi bi-emoji-frown"></i> No employees found.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
