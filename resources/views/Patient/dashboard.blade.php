@extends('layouts.patient')

@section('title', 'Patient Dashboard')

@section('content')
<style>
    .dashboard-title {
        font-size: 24px;
        font-weight: 800;
        color: #1a3a5c;
        margin-bottom: 20px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 26px;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 22px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 800;
        color: #1a3a5c;
        margin-bottom: 14px;
    }

    /* ── Queue Status Panel ── */
    .queue-panel {
        background: rgba(255,255,255,0.8);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        min-height: 260px;
    }

    .queue-card {
        background: white;
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 14px;
        border-left: 5px solid #4CAF50;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: box-shadow 0.2s;
        flex-wrap: wrap;
    }
    .queue-card:last-child { margin-bottom: 0; }
    .queue-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,0.13); }
    .queue-card.pending   { border-left-color: #FF9800; }
    .queue-card.confirmed { border-left-color: #4CAF50; }
    .queue-card.cancelled { border-left-color: #f44336; }

    .queue-circle {
        width: 60px; height: 60px; border-radius: 50%;
        background: linear-gradient(135deg, #4CAF50, #81C784);
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(76,175,80,0.3);
    }
    .queue-circle.pending {
        background: linear-gradient(135deg, #FF9800, #FFB74D);
        box-shadow: 0 3px 10px rgba(255,152,0,0.3);
    }
    .queue-circle .q-label  { font-size: 9px; font-weight: 700; color: white; text-transform: uppercase; letter-spacing: 1px; }
    .queue-circle .q-number { font-size: 22px; font-weight: 900; color: white; line-height: 1; }

    .queue-info { flex: 1; min-width: 0; }
    .queue-info .q-doctor { font-size: 14px; font-weight: 800; color: #1a3a5c; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .queue-info .q-meta   { font-size: 12px; color: #5a7a9c; margin-bottom: 6px; }

    .q-status-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700;
    }
    .q-status-pill.confirmed { background: #e8f5e9; color: #2e7d32; }
    .q-status-pill.pending   { background: #fff3e0; color: #e65100; }

    .queue-position {
        text-align: center; padding: 10px 14px;
        background: #f0f7ff; border-radius: 10px; min-width: 80px; flex-shrink: 0;
    }
    .queue-position .pos-label { font-size: 10px; color: #5a7a9c; font-weight: 600; text-transform: uppercase; }
    .queue-position .pos-value { font-size: 20px; font-weight: 900; color: #1565C0; }
    .queue-position .pos-sub   { font-size: 10px; color: #5a7a9c; }

    .queue-empty {
        color: #aaa; text-align: center;
        padding: 40px 0; font-size: 14px;
    }

    /* ── Profile Panel ── */
    .profile-panel {
        background: rgba(255,255,255,0.8);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        min-height: 260px;
    }

    .profile-item { margin-bottom: 14px; }
    .profile-label {
        font-size: 12px; color: #5a7a9c; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .profile-value { font-size: 15px; font-weight: 700; color: #1a3a5c; }

    /* ===== TABLET (max 900px) ===== */
    @media (max-width: 900px) {
        .dashboard-grid { grid-template-columns: 1fr; }
        .stats-grid     { gap: 12px; }
        .dashboard-title { font-size: 20px; }
    }

    /* ===== MOBILE (max 576px) ===== */
    @media (max-width: 576px) {
        .stats-grid      { grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 18px; }
        .dashboard-title { font-size: 18px; margin-bottom: 14px; }
        .section-title   { font-size: 15px; }

        .queue-panel,
        .profile-panel   { padding: 14px; }

        .queue-card      { padding: 12px 14px; gap: 12px; }
        .queue-circle    { width: 50px; height: 50px; }
        .queue-circle .q-number { font-size: 18px; }

        .queue-position  { padding: 8px 10px; min-width: 66px; }
        .queue-position .pos-value { font-size: 17px; }

        .profile-value   { font-size: 14px; }
    }

    /* ===== SMALL MOBILE (max 400px) ===== */
    @media (max-width: 400px) {
        .stats-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
        /* stack queue card elements vertically on very small screens */
        .queue-position { display: none; }
    }
</style>

<div class="dashboard-title">Dashboard</div>

{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Upcoming Appointments</div>
        <div class="stat-value">{{ $upcomingCount }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Total Visits</div>
        <div class="stat-value">{{ $totalVisits }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Last Visits</div>
        <div class="stat-value">{{ $lastVisitCount }}</div>
    </div>
</div>

{{-- Dashboard Grid --}}
<div class="dashboard-grid">

    {{-- My Queue Status --}}
    <div>
        <div class="section-title">My Queue Status</div>
        <div class="queue-panel">
            @php
                $queueAppointments = $upcomingAppointments->whereIn('status', ['pending', 'confirmed'])->values();
            @endphp

            @forelse ($queueAppointments as $appointment)
                @php $status = strtolower($appointment->status); @endphp
                <div class="queue-card {{ $status }}">

                    <div class="queue-circle {{ $status }}">
                        <span class="q-label">Queue</span>
                        <span class="q-number">{{ $appointment->queue_number ?? '—' }}</span>
                    </div>

                    <div class="queue-info">
                        <div class="q-doctor">{{ $appointment->doctor->name }}</div>
                        <div class="q-meta">
                            <i class="bi bi-calendar3"></i>
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                            &nbsp;·&nbsp;
                            <i class="bi bi-clock"></i>
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </div>
                        @if ($status === 'confirmed')
                            <span class="q-status-pill confirmed"><i class="bi bi-check-circle-fill"></i> Confirmed</span>
                        @elseif ($status === 'pending')
                            <span class="q-status-pill pending"><i class="bi bi-hourglass-split"></i> Pending Confirmation</span>
                        @endif
                    </div>

                    <div class="queue-position">
                        <div class="pos-label">Position</div>
                        <div class="pos-value">#{{ $appointment->queue_number ?? '—' }}</div>
                        <div class="pos-sub">in line</div>
                    </div>

                </div>
            @empty
                <div class="queue-empty">
                    <i class="bi bi-calendar-x" style="font-size:38px; color:#c0d8ef; display:block; margin-bottom:10px;"></i>
                    No upcoming appointments
                </div>
            @endforelse
        </div>
    </div>

    {{-- My Profile --}}
    <div>
        <div class="section-title">My Profile</div>
        <div class="profile-panel">
            <div class="profile-item">
                <div class="profile-label">Full Name</div>
                <div class="profile-value">{{ Auth::user()->fullname }}</div>
            </div>
            <div class="profile-item">
                <div class="profile-label">Email</div>
                <div class="profile-value">{{ Auth::user()->email }}</div>
            </div>
            <div class="profile-item">
                <div class="profile-label">Contact</div>
                <div class="profile-value">{{ $patient->phone ?? '—' }}</div>
            </div>
            <div class="profile-item">
                <div class="profile-label">Date of Birth</div>
                <div class="profile-value">
                    {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') : '—' }}
                </div>
            </div>
            <div class="profile-item">
                <div class="profile-label">Gender</div>
                <div class="profile-value">{{ $patient->gender ? ucfirst($patient->gender) : '—' }}</div>
            </div>
            <div class="profile-item">
                <div class="profile-label">Address</div>
                <div class="profile-value">{{ $patient->address ?? '—' }}</div>
            </div>
            <div class="profile-item">
                <div class="profile-label">Member Since</div>
                <div class="profile-value">{{ Auth::user()->created_at->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

</div>
@endsection 