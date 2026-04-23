@extends('layouts.patient')

@section('title', 'My Appointments')

@section('content')
<style>
    .page-title {
        font-size: 22px;
        font-weight: 800;
        color: #1a3a5c;
        margin-bottom: 20px;
    }

    .btn-slip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #1565C0;
        color: white;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .btn-slip:hover {
        background: #0d47a1;
        color: white;
    }

    .btn-view-reason {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #e53935;
        color: white;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .btn-view-reason:hover { background: #c62828; }

    .status-action-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        min-width: 200px;
    }

    th { text-align: left; }

    .no-action {
        font-size: 13px;
        color: #aaa;
        font-style: italic;
    }

    /* Status Text Colors */
    .status-pending {
        color: #f57c00;
        font-weight: 700;
        font-size: 13px;
    }

    .status-confirmed {
        color: #1565C0;
        font-weight: 700;
        font-size: 13px;
    }

    .status-done {
        color: #1565C0;
        font-weight: 700;
        font-size: 13px;
    }

    .status-cancelled {
        color: #e53935;
        font-weight: 700;
        font-size: 13px;
    }

    /* Reason Modal */
    .reason-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 999;
        justify-content: center;
        align-items: center;
    }

    .reason-modal-overlay.active { display: flex; }

    .reason-modal-box {
        background: white;
        border-radius: 14px;
        padding: 30px 32px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    }

    .reason-modal-title {
        font-size: 17px;
        font-weight: 800;
        color: #1a3a5c;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .reason-modal-appt {
        font-size: 13px;
        color: #5a7a9c;
        margin-bottom: 16px;
    }

    .reason-modal-body {
        background: #fff5f5;
        border-left: 4px solid #e53935;
        border-radius: 6px;
        padding: 14px 16px;
        font-size: 14px;
        color: #c62828;
        font-style: italic;
        line-height: 1.6;
    }

    .reason-modal-close {
        display: block;
        width: 100%;
        margin-top: 18px;
        padding: 10px;
        background: #f0f4f8;
        color: #5a7a9c;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .reason-modal-close:hover { background: #e0e8f0; }
</style>

<div class="page-title">All your appointment records</div>

@if (session('success'))
    <div style="background: rgba(76,175,80,0.1); border: 1px solid #4CAF50; border-radius: 6px;
                padding: 10px 14px; margin-bottom: 16px; font-size: 13px; color: #2e7d32;">
        {{ session('success') }}
    </div>
@endif

<div class="table-wrapper">
    <table>
        <thead class="table-header-blue">
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Doctor</th>
                <th>Type</th>
                <th>Complaint</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-body-white">
            @forelse ($appointments as $appointment)
            @php $status = strtolower($appointment->status); @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                <td>{{ $appointment->doctor->name }}</td>
                <td>{{ $appointment->type }}</td>
                <td>{{ $appointment->reason ?? '—' }}</td>

                {{-- Status Column --}}
                <td>
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

                {{-- Actions Column --}}
                <td>
                    @if ($status === 'confirmed' || $status === 'done')
                        <a href="{{ route('patient.appointments.slip', $appointment->id) }}"
                           class="btn-slip" target="_blank">
                            <i class</i>View Slip
                        </a>

                    @elseif ($status === 'cancelled' && $appointment->cancel_reason)
                        <button class="btn-view-reason"
                            onclick="openReasonModal(
                                '{{ addslashes($appointment->doctor->name) }}',
                                '{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d,Y')}}',
                                '{{ addslashes($appointment->cancel_reason) }}'
                            )">
                            <i class="bi bi-info-circle"></i> View Reason
                        </button>

                    @else
                        <span class="no-action">No actions available</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#aaa; padding: 60px 0; font-size:14px;">
                    No appointments yet.
                    <a href="{{ route('patient.book') }}" style="color:#2196F3; font-weight:600;">Book one now!</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($appointments->hasPages())
    <div style="margin-top: 20px;">
        {{ $appointments->links() }}
    </div>
@endif

{{-- Cancel Reason Modal --}}
<div class="reason-modal-overlay" id="reasonModal">
    <div class="reason-modal-box">
        <div class="reason-modal-title">
            <i class="bi bi-x-circle-fill" style="color:#e53935;"></i>
            Appointment Cancelled
        </div>
        <div class="reason-modal-appt" id="reasonModalAppt"></div>
        <div class="reason-modal-body" id="reasonModalText"></div>
        <button class="reason-modal-close" onclick="closeReasonModal()">Close</button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openReasonModal(doctor, date, reason) {
        document.getElementById('reasonModalAppt').textContent = doctor + ' · ' + date;
        document.getElementById('reasonModalText').textContent = 'Reason: ' + reason;
        document.getElementById('reasonModal').classList.add('active');
    }

    function closeReasonModal() {
        document.getElementById('reasonModal').classList.remove('active');
    }

    document.getElementById('reasonModal').addEventListener('click', function(e) {
        if (e.target === this) closeReasonModal();
    });
</script>
@endsection