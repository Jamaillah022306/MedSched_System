@extends('layouts.app')

@section('title', 'Archived Appointments')

@section('content')
<style>
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
</style>

<div style="font-size:24px; font-weight:800; color:#1a3a5c; margin-bottom:20px;">
    Archived Appointments
</div>

@if (session('success'))
    <div style="background:#e8f5e9; color:#2e7d32; border-radius:8px; padding:12px 18px; margin-bottom:16px; font-weight:600;">
        {{ session('success') }}
    </div>
@endif

<!-- Filter Bar — Search only -->
<div class="filter-bar">
    <input type="text" class="filter-input" placeholder="Search patient..."
        id="searchPatient" value="{{ request('search') }}">
</div>

<!-- Table -->
<div class="table-wrapper">
    <table>
        <thead class="table-header-blue">
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date & Time</th>
                <th>Type</th>
                <th>Cancel Reason</th>
                <th>Archived At</th>
            </tr>
        </thead>
        <tbody class="table-body-white">
            @forelse ($appointments as $appointment)
            <tr>
                <td>#{{ $appointment->id }}</td>
                <td>{{ $appointment->patient->name }}</td>
                <td>{{ $appointment->doctor->name }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                </td>
                <td>{{ $appointment->type ?? '—' }}</td>
                <td>{{ $appointment->cancel_reason ?? '—' }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->archived_at)->format('M d, Y h:i A') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#888; padding:40px;">
                    No archived appointments found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
<div class="pagination-footer">
    <div class="pagination-info">
        Showing {{ $appointments->firstItem() ?? 0 }}–{{ $appointments->lastItem() ?? 0 }}
        of {{ $appointments->total() }} appointment(s)
    </div>
    <div>
        {{ $appointments->withQueryString()->links() }}
    </div>
</div>

@endsection

@section('scripts')
<script>
    function applyFilters() {
        const search = document.getElementById('searchPatient').value;
        const params = new URLSearchParams();
        if (search) params.set('search', search);
        window.location.href = '{{ route('admin.appointments.archived') }}?' + params.toString();
    }

    let searchTimeout;
    document.getElementById('searchPatient').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500);
    });
</script>
@endsection