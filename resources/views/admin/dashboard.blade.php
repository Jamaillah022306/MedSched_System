@extends('layouts.app')

@section('title', 'Admin Dashboard')

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
        margin-bottom: 28px;
    }

    .section-title {
        font-size: 18px;
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
        margin-bottom: 16px;
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

    @media (max-width: 900px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .dashboard-title { font-size: 20px; }
    }

    @media (max-width: 576px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 20px; }
        .dashboard-title { font-size: 18px; margin-bottom: 14px; }
        .section-title { font-size: 15px; }
    }

    @media (max-width: 360px) {
        .stats-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
    }
</style>

<div class="dashboard-title">Dashboard</div>

{{-- Stat Cards --}}
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

{{-- Recent Appointments --}}
<div class="section-title">Recent Appointments</div>

<!-- Search Bar -->
<div class="filter-bar">
    <input type="text" class="filter-input" placeholder="Search patient..."
        id="searchPatient" value="{{ request('search') }}">
</div>

<div class="table-wrapper">
    <table>
        <thead class="table-header-blue">
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Time</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody class="table-body-white">
            @forelse ($recentAppointments as $appointment)
            <tr>
                <td>{{ $appointment->patient->name }}</td>
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
                        <span>{{ $appointment->status }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; color:#888; padding:40px;">No recent appointments</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
<div class="pagination-footer">
    <div class="pagination-info">
        Showing {{ $recentAppointments->firstItem() ?? 0 }}–{{ $recentAppointments->lastItem() ?? 0 }}
        of {{ $recentAppointments->total() }} appointment(s)
    </div>
    <div>
        {{ $recentAppointments->withQueryString()->links() }}
    </div>
</div>

@endsection

@section('scripts')
<script>
    function applyFilters() {
        const search = document.getElementById('searchPatient').value;
        const params = new URLSearchParams();
        if (search) params.set('search', search);
        window.location.href = '{{ route('admin.dashboard') }}?' + params.toString();
    }

    let searchTimeout;
    document.getElementById('searchPatient').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500);
    });
</script>
@endsection