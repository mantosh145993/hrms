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
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Card Hover Animation */
    .card-hover {
        transition: all 0.3s ease-in-out;
        border-radius: 15px;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
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
        top: 0; left: -100%;
        background: rgba(255,255,255,0.3);
        transition: all 0.5s;
    }

    .btn-animated:hover::after {
        left: 0;
    }
</style>

<div class="container my-4">
    <h4 class="text-center mb-4">🌟 Welcome, {{ Auth::user()->name }}</h4>

    {{-- Live Clock --}}
    <div class="text-center mb-4">
        <small class="text-muted">🕒 Current Time</small>
        <div id="clock"></div>
    </div>

    {{-- Attendance Actions --}}
    <div class="row g-4 mb-4">
        {{-- ✅ Check In --}}
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

        {{-- ✅ Check Out --}}
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
                                <button type="submit" class="btn btn-danger btn-animated">Check Out</button>
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

    {{-- Attendance Table --}}
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
                                <td>{{ $attendance->work_date->format('d M Y') }}</td>
                                <td class="text-success fw-bold">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</td>
                                <td class="text-danger fw-bold">{{ $attendance->check_out_at?->format('H:i') ?? '-' }}</td>
                                <td class="fw-bold text-primary">{{ $attendance->worked_time ?? '0h 0m' }}</td>
                                <td class="fw-bold text-success">{{ $attendance->overtime ?? '0h 0m' }}</td>
                                <td class="fw-bold text-warning">{{ $attendance->late_time ?? '0h 0m' }}</td>
                                <td class="fw-bold text-success" >{{$attendance->status }}</td>
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
            now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'});
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>


{{-- Live Clock Script --}}
<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

{{-- Optional: Add some animation using CSS --}}
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