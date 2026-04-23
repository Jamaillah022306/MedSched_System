@extends('Layouts.app')

@section('title', 'Admin Reports')

@section('nav-links')
    <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door-fill"></i> Home</a></li>
    <li><a href="{{ route('admin.appointments') }}"><i class="bi bi-calendar-event"></i> Appointments</a></li>
    <li><a href="{{ route('admin.doctors') }}"><i class="bi bi-person-badge"></i> Doctors</a></li>
    <li><a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart-fill"></i> Reports</a></li>
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

    /* ===== PAGINATION FOOTER ===== */
    .pagination-footer {
        margin-top: 18px;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .pagination-info {
        font-size: 13px;
        color: #5a7a9c;
        font-weight: 600;
    }
    .pagination-btns {
        display: flex;
        gap: 4px;
        align-items: center;
    }
    .page-btn {
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        background-color: #D6EAF8;
        color: #1a3a5c;
        border: 1.5px solid #a8c8e8;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.18s, color 0.18s;
        cursor: pointer;
    }
    .page-btn:hover {
        background-color: #aed6f1;
        color: #1a3a5c;
        text-decoration: none;
    }
    .page-btn.active {
        background-color: #1a3a5c;
        color: #fff;
        border-color: #1a3a5c;
    }
    .page-btn.disabled {
        color: #aaa;
        pointer-events: none;
        border-color: #dde8f0;
        background-color: #eef4fa;
        cursor: default;
    }
</style>

<div class="dashboard-title">Reports and Analysis</div>

{{-- STAT CARDS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Total Appointments</div>
        <div class="stat-value">{{ $totalAppointments }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Completed</div>
        <div class="stat-value">{{ $done }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Cancelled</div>
        <div class="stat-value">{{ $cancelled }}</div>
    </div>
</div>

{{-- EXPORT BUTTON --}}
<div style="display: flex; justify-content: flex-end; margin-bottom: 12px;">
    <a href="{{ route('admin.reports.export') }}" style="
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: black;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
    ">
        Export as PDF
    </a>
</div>

{{-- APPOINTMENTS SUMMARY TABLE --}}
<div class="section-title">Appointments Summary</div>
<div class="table-wrapper mb-4">
    <table>
        <thead class="table-header-blue">
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Doctor</th>
                <th>Appointment Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody class="table-body-white">
            @forelse($appointments as $index => $appt)
            <tr>
                <td>{{ $appointments->firstItem() + $index }}</td>
                <td>{{ $appt->patient->name ?? 'N/A' }}</td>
                <td>{{ $appt->doctor->name ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
                <td>
                    @php $status = strtolower($appt->status); @endphp
                    @if($status === 'done' || $status === 'completed')
                        <span class="status-done">Done</span>
                    @elseif($status === 'cancelled')
                        <span class="status-cancelled">Cancelled</span>
                    @elseif($status === 'pending')
                        <span class="status-pending">Pending</span>
                    @elseif($status === 'confirmed')
                        <span class="status-confirmed">Confirmed</span>
                    @else
                        <span>{{ ucfirst($appt->status) }}</span>
                    @endif
                </td>
                <td>{{ $appt->cancel_reason ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#888; padding: 40px;">No appointments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- APPOINTMENTS PAGINATION --}}
<div class="pagination-footer">
    <div class="pagination-info">
        Showing {{ $appointments->firstItem() ?? 0 }}–{{ $appointments->lastItem() ?? 0 }}
        of {{ $appointments->total() }} appointment(s)
    </div>
    <div class="pagination-btns">
        {{-- Prev --}}
        @if ($appointments->onFirstPage())
            <span class="page-btn disabled">‹</span>
        @else
            <a class="page-btn" href="{{ $appointments->previousPageUrl() }}">‹</a>
        @endif

        {{-- Page numbers --}}
        @foreach ($appointments->getUrlRange(1, $appointments->lastPage()) as $page => $url)
            @if ($page == $appointments->currentPage())
                <span class="page-btn active">{{ $page }}</span>
            @else
                <a class="page-btn" href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($appointments->hasMorePages())
            <a class="page-btn" href="{{ $appointments->nextPageUrl() }}">›</a>
        @else
            <span class="page-btn disabled">›</span>
        @endif
    </div>
</div>

{{-- PATIENT LIST TABLE --}}
<div class="section-title">Patient List</div>
<div class="table-wrapper mb-4">
    <table>
        <thead class="table-header-blue">
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th style="text-align:center;">Total Appointments</th>
                <th style="text-align:center;">Completed</th>
                <th style="text-align:center;">Cancelled</th>
            </tr>
        </thead>
        <tbody class="table-body-white">
            @forelse($patients as $index => $patient)
            <tr>
                <td>{{ $patients->firstItem() + $index }}</td>
                <td>{{ $patient->name }}</td>
                <td>{{ $patient->user->email ?? '—' }}</td>
                <td>{{ $patient->phone ?? '—' }}</td>
                <td style="text-align:center;">{{ $patient->total_appointments_count }}</td>
                <td style="text-align:center;">{{ $patient->done_count }}</td>
                <td style="text-align:center;">{{ $patient->cancelled_count }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#888; padding: 40px;">No patients found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- PATIENTS PAGINATION --}}
<div class="pagination-footer">
    <div class="pagination-info">
        Showing {{ $patients->firstItem() ?? 0 }}–{{ $patients->lastItem() ?? 0 }}
        of {{ $patients->total() }} patient(s)
    </div>
    <div class="pagination-btns">
        {{-- Prev --}}
        @if ($patients->onFirstPage())
            <span class="page-btn disabled">‹</span>
        @else
            <a class="page-btn" href="{{ $patients->previousPageUrl() }}">‹</a>
        @endif

        {{-- Page numbers --}}
        @foreach ($patients->getUrlRange(1, $patients->lastPage()) as $page => $url)
            @if ($page == $patients->currentPage())
                <span class="page-btn active">{{ $page }}</span>
            @else
                <a class="page-btn" href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($patients->hasMorePages())
            <a class="page-btn" href="{{ $patients->nextPageUrl() }}">›</a>
        @else
            <span class="page-btn disabled">›</span>
        @endif
    </div>
</div>

@endsection