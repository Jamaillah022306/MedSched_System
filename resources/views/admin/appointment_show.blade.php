@extends('layouts.app')

@section('title', 'Appointment Details')

@section('nav-links')
    <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door-fill"></i> Home</a></li>
    <li><a href="{{ route('admin.appointments') }}"><i class="bi bi-calendar-event"></i> Appointments</a></li>
    <li><a href="{{ route('admin.doctors') }}"><i class="bi bi-person-badge"></i> Doctors</a></li>
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

    .detail-card {
        background: rgba(255,255,255,0.88);
        border-radius: 14px;
        padding: 32px 36px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        max-width: 680px;
    }

    .detail-section-title {
        font-size: 13px;
        font-weight: 800;
        color: #5a7a9c;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e8f4ff;
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
        margin-bottom: 16px;
    }

    .status-confirmed { color: #1565C0; font-weight: 700; }
    .status-pending    { color: #F59E0B; font-weight: 700; }
    .status-done       { color: #1565C0; font-weight: 700; }
    .status-cancelled  { color: #e53935; font-weight: 700; }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title">Appointment Details</div>
    <a href="{{ route('admin.appointments') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="detail-card">

    <!-- Patient Info -->
    <div class="detail-section-title">Patient Information</div>

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
    <div class="detail-section-title section-gap">Appointment Information</div>

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
                <span class="status-confirmed">Confirmed</span>
            @elseif ($status === 'pending')
                <span class="status-pending">Pending</span>
            @elseif ($status === 'done')
                <span class="status-done">Done</span>
            @elseif ($status === 'cancelled')
                <span class="status-cancelled">Cancelled</span>
            @endif
        </div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Reason for Visit</div>
        <div class="detail-value">{{ $appointment->reason ?? '—' }}</div>
    </div>

</div>

@endsection