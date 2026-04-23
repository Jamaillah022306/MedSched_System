@extends('layouts.app')

@section('title', 'Appointments - All Records')

@section('nav-links')
    <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door-fill"></i> Home</a></li>
    <li><a href="{{ route('admin.appointments') }}"><i class="bi bi-calendar-event"></i> Appointments</a></li>
    <li><a href="{{ route('admin.doctors') }}"><i class="bi bi-person-badge"></i> Doctors</a></li>
@endsection

@section('content')
<style>
    .page-title {
        font-size: 24px;
        font-weight: 800;
        color: #1a3a5c;
        margin-bottom: 20px;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 999;
        justify-content: center;
        align-items: center;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: white;
        border-radius: 14px;
        padding: 32px;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    }
    .modal-title {
        font-size: 18px;
        font-weight: 800;
        color: #1a3a5c;
        margin-bottom: 8px;
    }
    .modal-subtitle {
        font-size: 13px;
        color: #5a7a9c;
        margin-bottom: 18px;
    }
    .modal-textarea {
        width: 100%;
        border: 2px solid #dbe8f5;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 14px;
        color: #1a3a5c;
        resize: vertical;
        min-height: 100px;
        outline: none;
        box-sizing: border-box;
    }
    .modal-textarea:focus { border-color: #4a90c4; }
    .modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 18px;
        justify-content: flex-end;
    }
    .btn-modal-cancel {
        padding: 9px 20px;
        background: #f0f4f8;
        color: #5a7a9c;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        font-size: 14px;
    }
    .btn-modal-confirm {
        padding: 9px 20px;
        background: #e53935;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        font-size: 14px;
    }
    .btn-modal-confirm:hover { background: #c62828; }

    .badge-archived {
        background: #e0e0e0;
        color: #080808;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-action-archive {
        background: transparent;
        color: #5a7a9c;
        border: 2px solid #c5d8ec;
        border-radius: 50%;
        width: 34px;
        height: 34px;
        padding: 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s, color 0.2s;
    }
    .btn-action-archive:hover {
        background: #dbe8f5;
        color: #1a3a5c;
    }

    .status-confirmed { color: #1565C0; font-weight: 700; }
    .status-pending    { color: #F59E0B; font-weight: 700; }
    .status-done       { color: #1565C0; font-weight: 700; }
    .status-cancelled  { color: #e53935; font-weight: 700; }

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

<div class="page-title">Appointments - All Records</div>

<!-- Filter Bar — Search only -->
<div class="filter-bar">
    <input type="text" class="filter-input" placeholder="Search patient..."
        id="searchPatient" value="{{ request('search') }}">
</div>

<!-- Appointments Table -->
<div class="table-wrapper">
    <table>
        <thead class="table-header-blue">
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Time</th>
                <th>Type</th>
                <th>Status</th>
                <th style="text-align:center;">Action</th>
            </tr>
        </thead>
        <tbody class="table-body-white">
            @forelse ($appointments as $appointment)
            <tr>
                <td>{{ $appointment->patient->name }}</td>
                <td>{{ $appointment->doctor->name }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                </td>
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
                    @elseif ($status === 'archived')
                        <span class="badge-archived"><i class="bi bi-archive"></i> Archived</span>
                    @else
                        <span>{{ $appointment->status }}</span>
                    @endif
                </td>
                <td style="text-align:center;">
                    <div style="display:flex; gap:8px; align-items:center; justify-content:center;">
                        @php $status = strtolower($appointment->status); @endphp

                        @if ($status === 'done' || $status === 'confirmed')
                            <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                               class="btn-action-view" title="View">
                                <i class="bi bi-eye" style="font-size:15px;"></i>
                            </a>
                        @else
                            <span style="color:#aaa; font-size:16px; font-weight:600;">—</span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:#888; padding: 40px;">No appointments found</td>
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
        window.location.href = '{{ route('admin.appointments') }}?' + params.toString();
    }

    let searchTimeout;
    document.getElementById('searchPatient').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500);
    });
</script>
@endsection