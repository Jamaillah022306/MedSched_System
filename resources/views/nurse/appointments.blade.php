@extends('layouts.app')

@section('title', 'Appointments - All Records')

@section('nav-links')
    <li><a href="{{ route('nurse.dashboard') }}"><i class="bi bi-house-door-fill"></i> Home</a></li>
    <li><a href="{{ route('nurse.appointments') }}"><i class="bi bi-calendar-event"></i> Appointments</a></li>
@endsection

@section('content')
<style>
    .page-title {
        font-size: 24px;
        font-weight: 800;
        color: #1a3a5c;
        margin-bottom: 20px;
    }

    /* ===== STATUS TEXT COLORS ===== */
    .status-pending  { color: #e67e00; font-weight: 700; }
    .status-done     { color: #1a6fcc; font-weight: 700; }
    .status-cancelled{ color: #e53935; font-weight: 700; }
    .status-confirmed{ color: #2e7d32; font-weight: 700; }

    /* Cancel Modal */
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

    .btn-action-archive {
        background: none;
        border: 2px solid #333;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #333;
        transition: all 0.2s;
    }
    .btn-action-archive:hover { background: #6c757d; border-color: #6c757d; color: white; }

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

@if (session('success'))
    <div style="background:#e8f5e9; color:#2e7d32; border-radius:8px; padding:12px 18px; margin-bottom:16px; font-weight:600;">
        {{ session('success') }}
    </div>
@endif

<!-- Filter Bar -->
<div class="filter-bar">
    <input type="text" class="filter-input" placeholder="Search patient..."
        id="searchPatient" value="{{ request('search') }}">
</div>

<!-- Appointments Table -->
<div class="table-wrapper">
    <table>
        <thead class="table-header-blue">
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Time</th>
                <th>Type</th>
                <th>Status</th>
                <th>Action</th>
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
                    <div style="display:flex; gap:8px; align-items:center;">
                        @php $status = strtolower($appointment->status); @endphp

                        @if ($status === 'done')
                            <a href="{{ route('nurse.appointments.show', $appointment->id) }}"
                               class="btn-action-view" title="View">
                                <i class="bi bi-eye" style="font-size:15px;"></i>
                            </a>

                        @elseif ($status === 'cancelled')
                            @if (!$appointment->is_archived)
                                <form action="{{ route('nurse.appointments.archive', $appointment->id) }}"
                                      method="POST" onsubmit="return confirm('Archive this appointment?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-action-archive" title="Archive">
                                        <i class="bi bi-archive" style="font-size:14px;"></i>
                                    </button>
                                </form>
                            @else
                                <span style="color:#aaa; font-size:12px; font-weight:600;">Archived</span>
                            @endif

                        @elseif ($status === 'confirmed')
                            <form action="{{ route('nurse.appointments.done', $appointment->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-done" title="Mark as Done">Done</button>
                            </form>
                            <a href="{{ route('nurse.appointments.show', $appointment->id) }}"
                               class="btn-action-view" title="View">
                                <i class="bi bi-eye" style="font-size:15px;"></i>
                            </a>
                            <button type="button" class="btn-action-cancel" title="Cancel"
                                onclick="openCancelModal({{ $appointment->id }})">
                                <i class="bi bi-x-lg" style="font-size:14px;"></i>
                            </button>

                        @else
                            <form action="{{ route('nurse.appointments.confirm', $appointment->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action-confirm" title="Confirm">
                                    <i class="bi bi-check-lg" style="font-size:15px;"></i>
                                </button>
                            </form>
                            <button type="button" class="btn-action-cancel" title="Cancel"
                                onclick="openCancelModal({{ $appointment->id }})">
                                <i class="bi bi-x-lg" style="font-size:14px;"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#888; padding: 40px;">No appointments found</td>
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

<!-- Cancel Reason Modal -->
<div class="modal-overlay" id="cancelModal">
    <div class="modal-box">
        <div class="modal-title">Cancel Appointment</div>
        <div class="modal-subtitle">Please provide a reason for cancelling this appointment. The patient will be notified.</div>
        <form id="cancelForm" method="POST">
            @csrf
            @method('PATCH')
            <textarea class="modal-textarea" name="cancel_reason"
                placeholder="e.g. Doctor is unavailable on this date..." required></textarea>
            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeCancelModal()">Go Back</button>
                <button type="submit" class="btn-modal-confirm">Confirm Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openCancelModal(appointmentId) {
        const form = document.getElementById('cancelForm');
        form.action = '/nurse/appointments/' + appointmentId + '/cancel';
        document.getElementById('cancelModal').classList.add('active');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.remove('active');
        document.querySelector('#cancelForm textarea').value = '';
    }

    document.getElementById('cancelModal').addEventListener('click', function(e) {
        if (e.target === this) closeCancelModal();
    });

    function applyFilters() {
        const search = document.getElementById('searchPatient').value;
        const params = new URLSearchParams();
        if (search) params.set('search', search);
        window.location.href = '{{ route('nurse.appointments') }}?' + params.toString();
    }

    let searchTimeout;
    document.getElementById('searchPatient').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500);
    });
</script>
@endsection