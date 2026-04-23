@extends('layouts.app')

@section('title', 'Nurse Dashboard')

@section('nav-links')
    <li><a href="{{ route('nurse.dashboard') }}"><i class="bi bi-house-door-fill"></i> Home</a></li>
    <li><a href="{{ route('nurse.appointments') }}"><i class="bi bi-calendar-event"></i> Appointments</a></li>
@endsection

@section('content')
<style>
    .dashboard-title {
        font-size: 26px;
        font-weight: 800;
        color: #1a3a5c;
        margin-bottom: 22px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 800;
        color: #1a3a5c;
        margin-bottom: 14px;
    }

    /* ===== STATUS TEXT COLORS ===== */
    .status-pending  { color: #e67e00; font-weight: 700; }
    .status-done     { color: #1a6fcc; font-weight: 700; }
    .status-cancelled{ color: #e53935; font-weight: 700; }
    .status-confirmed{ color: #2e7d32; font-weight: 700; }

    .btn-nurse-done {
        background: #1565C0;
        color: white;
        border: none;
        padding: 8px 18px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-nurse-done:hover { background: #0d47a1; }

    .btn-nurse-confirm {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 8px 18px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-nurse-confirm:hover { background: #388e3c; }
</style>

<div class="dashboard-title">Dashboard</div>

<!-- Stat Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Appointments Today</div>
        <div class="stat-value">{{ $appointmentsToday }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Pending</div>
        <div class="stat-value">{{ $pendingCount }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Total Patients</div>
        <div class="stat-value">{{ $totalPatients }}</div>
    </div>
</div>

<!-- Today's Appointments -->
<div class="section-title">Today's Appointments</div>

<div class="table-wrapper">
    <table>
        <thead class="table-header-blue">
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Time</th>
                <th>Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-body-white">
            @forelse ($todayAppointments as $appointment)
            <tr>
                <td>{{ $appointment->patient->user->fullname ?? '—' }}</td>
                <td>{{ $appointment->doctor->name }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                <td>{{ $appointment->type }}</td>
                <td>
                    @php $status = strtolower($appointment->status); @endphp
                    @if ($status === 'confirmed')
                        <span class="status-confirmed">Confirmed</span>
                    @elseif ($status === 'pending')
                        <span class="status-pending">Pending</span>
                    @elseif ($status === 'done')
                        <span class="status-done">Done</span>
                    @elseif ($status === 'cancelled')
                        <span class="status-cancelled">Cancelled</span>
                    @else
                        <span>{{ ucfirst($appointment->status) }}</span>
                    @endif
                </td>
                <td>
                    @if ($status === 'confirmed')
                        <form action="{{ route('nurse.appointments.done', $appointment->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-nurse-done">Done</button>
                        </form>
                    @elseif ($status === 'pending')
                        <form action="{{ route('nurse.appointments.confirm', $appointment->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-nurse-confirm">Confirm</button>
                        </form>
                    @else
                        <span style="color:#aaa; font-size:13px;">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:#888; padding: 40px;">No appointments today</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection