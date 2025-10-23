@extends('layouts.employee')
@section('employee-content')

<style>
    /* Clock Animation */
    #clock {
        font-size: 1.8rem;
        font-weight: bold;
        animation: pulse 2s infinite;
        color: #0d6efd;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.05);
            opacity: 0.8;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Card Hover Animation */
    .card-hover {
        transition: all 0.3s ease-in-out;
        border-radius: 15px;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    /* Gradient Headers */
    .gradient-header {
        background: linear-gradient(45deg, #0d6efd, #6610f2);
        color: #fff !important;
    }

    /* Animated Button */
    .btn-animated {
        position: relative;
        overflow: hidden;
        transition: 0.3s;
    }

    .btn-animated::after {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: -100%;
        background: rgba(255, 255, 255, 0.3);
        transition: all 0.5s;
    }

    .btn-animated:hover::after {
        left: 0;
    }
</style>

<div class="container my-4">
    <h4 class="text-center mb-4">🌟 Welcome, {{ Auth::user()->name }}</h4>
    <div class="text-center mb-4">
        <small class="text-muted">🕒 Current Time</small>
        <div id="clock"></div>
    </div>
    <!-- In Out Attendance  -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card card-hover border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-success mb-2">✅ Check In</h6>
                    @if(!$canCheckInOut)
                    @if($onLeave)
                    <p class="text-muted">🌴 On Leave Today</p>
                    @elseif($isHoliday)
                    <p class="text-muted">🎉 Holiday</p>
                    @elseif($isSunday)
                    <p class="text-muted">☀️ Sunday</p>
                    @endif
                    @else
                    @if(!$attendance || !$attendance?->check_in_at)
                    <form action="{{ route('attendance.checkIn') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-animated">Check In</button>
                    </form>
                    @else
                    <p class="text-muted">Checked in at:</p>
                    <div class="fw-bold text-success">{{ $attendance->check_in_at->format('H:i') }}</div>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-hover border-0 shadow">
                <div class="card-body text-center">
                    <h6 class="text-danger mb-2">⏹ Check Out</h6>
                    @if(!$canCheckInOut)
                    @if($onLeave)
                    <p class="text-muted">🌴 On Leave Today</p>
                    @elseif($isHoliday)
                    <p class="text-muted">🎉 Holiday</p>
                    @elseif($isSunday)
                    <p class="text-muted">☀️ Sunday</p>
                    @endif
                    @else
                    @if($attendance && !$attendance->check_out_at)
                    <form action="{{ route('attendance.checkOut') }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="btn btn-danger btn-animated"
                            onclick="return confirm('Is Shift over? Are you sure you want to Check Out?');">
                            Check Out
                        </button>
                    </form>
                    @elseif($attendance && $attendance->check_out_at)
                    <p class="text-muted">Checked out at:</p>
                    <div class="fw-bold text-danger">{{ $attendance->check_out_at->format('H:i') }}</div>
                    @else
                    <p class="text-muted">Not checked in yet</p>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
      <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary text-center">Employee Overview</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Designation</th>
                        <th>DOJ</th>
                        <th>DOC</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">{{ $attendance->user->name ?? 'N/A' }}</td>
                        <td class="fw-semibold">{{ $attendance->user->designation ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-primary text-white">
                                {{ $attendance->user->doj ? \Carbon\Carbon::parse($attendance->user->doj ?? 'N/A')->format('d M, Y') : 'N/A' }}
                            </span>
                        </td>
                        <td>
                            @if($attendance->user->doc)
                                <span class="badge bg-success">
                                    {{ \Carbon\Carbon::parse($attendance->user->doc ?? 'N/A')->format('d M, Y') }}
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info text-white">{{ $duration ?? 'N/A' }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

    </div>
   <!-- End Out  -->
    <div class="card border-0 shadow">
        <div class="card-header gradient-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><i class="bi bi-calendar-check me-2"></i>Today's Attendance</span>
            <span class="badge bg-light text-dark">{{ now()->format('d M Y (D)') }}</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>👨‍💼 Employee</th>
                            <th>DOJ</th>
                            <th>📅 Date</th>
                            <th>⏰ Check In</th>
                            <th>⏳ Check Out</th>
                            <th>💼 Worked</th>
                            <th>⚡ Overtime</th>
                            <th>⏱ Late</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($attendance)
                        <tr>
                            <td class="fw-bold text-dark">{{ Auth::user()->name }}</td>
                            <td> {{ $attendance->user->doj ? \Carbon\Carbon::parse($attendance->user->doj)->format('d-M-Y') : '-' }}</td>
                            <td>{{ $attendance->work_date->format('d M Y') }}</td>
                            <td class="text-success fw-bold">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</td>
                            <td class="text-danger fw-bold">{{ $attendance->check_out_at?->format('H:i') ?? '-' }}</td>
                            <td class="fw-bold text-primary">{{ $attendance->worked_time ?? '0h 0m' }}</td>
                            <td class="fw-bold text-success">{{ $attendance->overtime ?? '0h 0m' }}</td>
                            <td class="fw-bold text-warning">{{ $attendance->late_time ?? '0h 0m' }}</td>
                            <td class="fw-bold text-success">{{$attendance->status }}</td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="7" class="text-muted py-3">😔 No attendance marked today</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        let now = new Date();
        document.getElementById('clock').innerText =
            now.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
<style>
    #clock {
        color: #0d5ef4;
        text-shadow: 1px 1px 2px #000;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .card {
        transition: transform 0.2s;
    }

    .card:hover {
        transform: scale(1.02);
    }
</style>
@endsection