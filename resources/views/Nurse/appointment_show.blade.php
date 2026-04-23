@extends('layouts.app')

@section('title', 'Appointment Details')

@section('nav-links')
    <li><a href="{{ route('nurse.dashboard') }}"><i class="bi bi-house-door-fill"></i> Home</a></li>
    <li><a href="{{ route('nurse.appointments') }}"><i class="bi bi-calendar-event"></i> Appointments</a></li>
@endsection

@section('extra-styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 800;
        color: #1a3a5c;
    }

    .btn-back {
        padding: 10px 22px;
        background: white;
        color: #1a3a5c;
        border: 2px solid #1a3a5c;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #1a3a5c;
        color: white;
    }

    /* Two-column layout */
    .detail-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        align-items: start;
    }

    .detail-card {
        background: rgba(255,255,255,0.88);
        border-radius: 14px;
        padding: 28px 30px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    }

    .card-section-title {
        font-size: 13px;
        font-weight: 800;
        color: #5a7a9c;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e8f4ff;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-row {
        display: flex;
        margin-bottom: 14px;
        gap: 12px;
    }

    .detail-label {
        font-size: 13px;
        color: #5a7a9c;
        font-weight: 600;
        min-width: 140px;
    }

    .detail-value {
        font-size: 14px;
        font-weight: 700;
        color: #1a3a5c;
    }

    .section-gap {
        margin-top: 24px;
        margin-bottom: 8px;
    }

    /* Schedule table on right */
    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .schedule-table th {
        background: #6fa8d6;
        color: #fff;
        font-weight: 700;
        padding: 10px 14px;
        text-align: left;
    }

    .schedule-table td {
        padding: 10px 14px;
        border-bottom: 1px solid #e8f4ff;
        color: #1a3a5c;
        font-weight: 600;
    }

    .schedule-table tr:last-child td {
        border-bottom: none;
    }

    .schedule-table tr:nth-child(even) td {
        background: #f5faff;
    }

    /* Highlight the booked appointment day */
    .schedule-table tr.booked-day td {
        background: #e0f4e8;
        color: #2e7d32;
    }

    .booked-badge {
        display: inline-block;
        background: #4CAF50;
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        margin-left: 6px;
        vertical-align: middle;
    }

    .no-schedule {
        text-align: center;
        color: #aaa;
        font-size: 13px;
        padding: 24px 0;
    }

    .doctor-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e8f4ff;
    }

    .doctor-avatar {
        width: 44px;
        height: 44px;
        background: #ABE0F0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #1a3a5c;
        flex-shrink: 0;
    }

    .doctor-name {
        font-size: 16px;
        font-weight: 800;
        color: #1a3a5c;
    }

    .doctor-spec {
        font-size: 12px;
        color: #5a7a9c;
        font-weight: 600;
    }

    .badge-confirmed { background-color: #4CAF50; color: white; padding: 4px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-block; }
    .badge-pending   { background-color: #FF9800; color: white; padding: 4px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-block; }
    .badge-done      { background-color: #1565C0; color: white; padding: 4px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-block; }
    .badge-cancelled { background-color: #f44336; color: white; padding: 4px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-block; }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title">Appointment Details</div>
    <a href="{{ route('nurse.appointments') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="detail-layout">

    {{-- LEFT: Patient Info + Appointment Info --}}
    <div class="detail-card">

        <!-- Patient Info -->
        <div class="card-section-title">
            <i class="bi bi-person-fill"></i> Patient Information
        </div>

        <div class="detail-row">
            <div class="detail-label">Full Name</div>
            <div class="detail-value">{{ $appointment->patient->user->fullname ?? '—' }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Contact</div>
            <div class="detail-value">{{ $appointment->patient->phone ?? '—' }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Gender</div>
            <div class="detail-value">{{ ucfirst($appointment->patient->gender ?? '—') }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Date of Birth</div>
            <div class="detail-value">
                {{ $appointment->patient->date_of_birth
                    ? \Carbon\Carbon::parse($appointment->patient->date_of_birth)->format('M d, Y')
                    : '—' }}
            </div>
        </div>

        <!-- Appointment Info -->
        <div class="card-section-title section-gap">
            <i class="bi bi-calendar-event-fill"></i> Appointment Information
        </div>

        <div class="detail-row">
            <div class="detail-label">Doctor</div>
            <div class="detail-value">Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Specialization</div>
            <div class="detail-value">{{ $appointment->doctor->specialization }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Date</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Time</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Type</div>
            <div class="detail-value">{{ $appointment->type }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Status</div>
            <div class="detail-value">
                @php $status = strtolower($appointment->status); @endphp
                @if ($status === 'confirmed')
                    <span class="badge-confirmed">Confirmed</span>
                @elseif ($status === 'pending')
                    <span class="badge-pending">Pending</span>
                @elseif ($status === 'completed' || $status === 'done')
                    <span class="badge-done">Completed</span>
                @elseif ($status === 'cancelled')
                    <span class="badge-cancelled">Cancelled</span>
                @endif
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Reason for Visit</div>
            <div class="detail-value">{{ $appointment->reason ?? '—' }}</div>
        </div>

    </div>

    {{-- RIGHT: Doctor Schedule --}}
    <div class="detail-card">

        <div class="card-section-title">
            <i class="bi bi-calendar-week-fill"></i> Doctor's Schedule
        </div>

        {{-- Doctor header --}}
        <div class="doctor-header">
            <div class="doctor-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div>
                <div class="doctor-name">Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</div>
                <div class="doctor-spec">{{ $appointment->doctor->specialization }}</div>
            </div>
        </div>

        @php
            $schedules = $appointment->doctor->schedules ?? collect();
            $bookedDay = strtolower(\Carbon\Carbon::parse($appointment->appointment_date)->format('l'));
        @endphp

        @if ($schedules->isEmpty())
            <div class="no-schedule">No schedule set for this doctor.</div>
        @else
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $sched)
                        @php
                            $isBooked = strtolower($sched->day_of_week) === $bookedDay;
                        @endphp
                        <tr class="{{ $isBooked ? 'booked-day' : '' }}">
                            <td>
                                {{ ucfirst($sched->day_of_week) }}
                                @if ($isBooked)
                                    <span class="booked-badge">Booked</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>

</div>

@endsection