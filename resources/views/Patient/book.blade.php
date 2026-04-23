@extends('layouts.patient')

@section('title', 'Book Appointment')

@section('head')
<style>
    .book-page {
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        gap: 32px;
        align-items: start;
    }

    .book-title { font-size: 26px; font-weight: 900; color: #1a3a5c; margin-bottom: 4px; }
    .book-subtitle { font-size: 13px; color: #5a7a9c; margin-bottom: 28px; }

    .book-label {
        font-size: 12px;
        font-weight: 800;
        color: #1a3a5c;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
        display: block;
    }

    .book-select {
        width: 100%;
        padding: 13px 40px 13px 16px;
        border: 1.5px solid #a8cfe8;
        border-radius: 8px;
        font-size: 15px;
        background: rgba(255,255,255,0.9);
        color: #1a3a5c;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 14 14'%3E%3Cpath fill='%23333' d='M7 9L2 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        cursor: pointer;
        margin-bottom: 22px;
        font-weight: 600;
    }

    .book-select:focus { outline: none; border-color: #2196F3; box-shadow: 0 0 0 3px rgba(33,150,243,0.15); }

    .book-date {
        width: 100%;
        padding: 13px 16px;
        border: 1.5px solid #a8cfe8;
        border-radius: 8px;
        font-size: 15px;
        background: rgba(255,255,255,0.9);
        color: #1a3a5c;
        margin-bottom: 22px;
        font-weight: 600;
    }

    .book-date:focus { outline: none; border-color: #2196F3; box-shadow: 0 0 0 3px rgba(33,150,243,0.15); }

    .book-textarea {
        width: 100%;
        padding: 13px 16px;
        border: 1.5px solid #a8cfe8;
        border-radius: 8px;
        font-size: 14px;
        background: rgba(255,255,255,0.9);
        color: #1a3a5c;
        min-height: 140px;
        resize: vertical;
        font-family: inherit;
        margin-bottom: 10px;
    }

    .book-textarea:focus { outline: none; border-color: #2196F3; box-shadow: 0 0 0 3px rgba(33,150,243,0.15); }

    .timeslots-panel {
        background: rgba(255,255,255,0.85);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        min-height: 300px;
    }

    .timeslots-title {
        font-size: 12px;
        font-weight: 800;
        color: #1a3a5c;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
        display: block;
    }

    .timeslots-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }

    .timeslot-btn {
        padding: 10px 8px;
        border: 2px solid #a8cfe8;
        border-radius: 8px;
        background: white;
        color: #1a3a5c;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
    }

    .timeslot-btn:hover { border-color: #2196F3; background: rgba(33,150,243,0.08); color: #2196F3; }
    .timeslot-btn.selected { border-color: #2196F3; background: #2196F3; color: white; }
    .timeslot-btn.booked { background: #f5f5f5; color: #bbb; border-color: #e0e0e0; cursor: not-allowed; text-decoration: line-through; }

    .details-section { margin-top: 0; }

    .details-title {
        font-size: 12px;
        font-weight: 800;
        color: #1a3a5c;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 14px;
    }

    .btn-confirm-booking {
        display: block;
        margin-left: auto;
        padding: 14px 32px;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 24px;
        transition: all 0.2s;
        box-shadow: 0 3px 10px rgba(76,175,80,0.35);
    }

    .btn-confirm-booking:hover { background: linear-gradient(135deg, #45a049, #388e3c); transform: translateY(-1px); }

    .alert-error { background: rgba(244,67,54,0.1); border: 1px solid #f44336; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; font-size: 13px; color: #c62828; }
    .alert-success { background: rgba(76,175,80,0.1); border: 1px solid #4CAF50; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; font-size: 13px; color: #2e7d32; }
    .no-slots-msg { color: #aaa; font-size: 14px; text-align: center; padding: 30px 0; }

    /* ── CONFIRMATION MODAL ── */
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
        border-radius: 16px;
        padding: 32px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    }

    .modal-icon {
        width: 56px;
        height: 56px;
        background: rgba(33,150,243,0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .modal-icon svg { width: 28px; height: 28px; color: #2196F3; }

    .modal-title {
        font-size: 18px;
        font-weight: 800;
        color: #1a3a5c;
        text-align: center;
        margin-bottom: 6px;
    }

    .modal-subtitle {
        font-size: 13px;
        color: #5a7a9c;
        text-align: center;
        margin-bottom: 22px;
    }

    .modal-details {
        background: #f4f8fd;
        border-radius: 10px;
        padding: 16px 18px;
        margin-bottom: 22px;
    }

    .modal-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 13px;
        border-bottom: 1px solid #e0ecf8;
    }

    .modal-detail-row:last-child { border-bottom: none; }

    .modal-detail-label {
        color: #5a7a9c;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }

    .modal-detail-value {
        color: #1a3a5c;
        font-weight: 700;
        font-size: 13px;
        text-align: right;
        max-width: 60%;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
    }

    .btn-modal-back {
        flex: 1;
        padding: 12px;
        background: #f0f4f8;
        color: #5a7a9c;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-modal-back:hover { background: #e0e8f0; }

    .btn-modal-confirm {
        flex: 1;
        padding: 12px;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        font-size: 14px;
        box-shadow: 0 3px 10px rgba(76,175,80,0.35);
    }

    .btn-modal-confirm:hover { background: linear-gradient(135deg, #45a049, #388e3c); }
</style>
@endsection

@section('content')

@if ($errors->any())
    <div class="alert-error">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('patient.book.store') }}" method="POST" id="bookingForm">
    @csrf
    <input type="hidden" name="appointment_time" id="selectedTime">

    <div class="book-title">Book an appointment</div>
    <div class="book-subtitle">Fill in the details to schedule your visit</div>

    <div class="book-page">

        <!-- LEFT: Form inputs -->
        <div>
            <label class="book-label">Choose Doctor</label>
            <select name="doctor_id" class="book-select" id="doctorSelect" required>
                <option value="">CHOOSE DOCTOR</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}"
                        data-days="{{ $doctor->schedules->where('is_available', true)->pluck('day_of_week')->implode(',') }}"
                        data-name="Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}"
                        {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                        Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                    </option>
                @endforeach
            </select>

            <label class="book-label">Choose Date</label>
            <input
                type="date"
                name="appointment_date"
                id="dateInput"
                class="book-date"
                value="{{ old('appointment_date') }}"
                min="{{ date('Y-m-d') }}"
                required
            >

            <!-- Appointment Details -->
            <div class="details-section">
                <div class="details-title">Appointment Details</div>

                <label class="book-label" style="text-transform:none; font-size:13px; letter-spacing:0;">
                    Type of consultations
                </label>
                <select name="type" class="book-select" id="typeSelect" required>
                    <option value="">Type of consultations...</option>
                    <option value="General Consultation" {{ old('type') == 'General Consultation' ? 'selected' : '' }}>General Consultation</option>
                    <option value="Follow-up"            {{ old('type') == 'Follow-up'            ? 'selected' : '' }}>Follow-up</option>
                    <option value="Emergency"            {{ old('type') == 'Emergency'            ? 'selected' : '' }}>Emergency</option>
                    <option value="Specialist Referral"  {{ old('type') == 'Specialist Referral'  ? 'selected' : '' }}>Specialist Referral</option>
                    <option value="Check-up"             {{ old('type') == 'Check-up'             ? 'selected' : '' }}>Check-up</option>
                </select>

                <label class="book-label" style="text-transform:none; font-size:13px; letter-spacing:0;">
                    Reason for visit
                </label>
                <textarea
                    name="reason"
                    id="reasonInput"
                    class="book-textarea"
                    placeholder="Describe your symptoms or reason for visit..."
                >{{ old('reason') }}</textarea>
            </div>

            <div style="text-align:right;">
                <button type="button" class="btn-confirm-booking" onclick="openConfirmModal()">
                    Confirm
                </button>
            </div>
        </div>

        <!-- RIGHT: Time Slots -->
        <div>
            <div class="timeslots-title">Time Slots</div>
            <div class="timeslots-panel">
                <div id="timeslotsContainer">
                    <div class="no-slots-msg">
                        Select a doctor and date to see available time slots.
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<!-- ── CONFIRMATION MODAL ── -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-box">
        <div class="modal-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#2196F3" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="modal-title">Confirm Appointment</div>
        <div class="modal-subtitle">Please review your appointment details before confirming.</div>

        <div class="modal-details">
            <div class="modal-detail-row">
                <span class="modal-detail-label">Doctor</span>
                <span class="modal-detail-value" id="summaryDoctor">—</span>
            </div>
            <div class="modal-detail-row">
                <span class="modal-detail-label">Date</span>
                <span class="modal-detail-value" id="summaryDate">—</span>
            </div>
            <div class="modal-detail-row">
                <span class="modal-detail-label">Time</span>
                <span class="modal-detail-value" id="summaryTime">—</span>
            </div>
            <div class="modal-detail-row">
                <span class="modal-detail-label">Type</span>
                <span class="modal-detail-value" id="summaryType">—</span>
            </div>
            <div class="modal-detail-row">
                <span class="modal-detail-label">Reason</span>
                <span class="modal-detail-value" id="summaryReason">—</span>
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn-modal-back" onclick="closeConfirmModal()">Go Back</button>
            <button type="button" class="btn-modal-confirm" onclick="submitBooking()">Confirm</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const bookedSlots = @json($bookedSlots ?? []);

    const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    const timeSlots = [
        '08:00 AM', '08:30 AM', '09:00 AM', '09:30 AM',
        '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM',
        '01:00 PM', '01:30 PM', '02:00 PM', '02:30 PM',
        '03:00 PM', '03:30 PM', '04:00 PM', '04:30 PM',
    ];

    let selectedTime = null;

    function renderSlots() {
        const doctorSelect = document.getElementById('doctorSelect');
        const dateInput    = document.getElementById('dateInput');
        const container    = document.getElementById('timeslotsContainer');

        const doctorId = doctorSelect.value;
        const dateVal  = dateInput.value;

        if (!doctorId || !dateVal) {
            container.innerHTML = '<div class="no-slots-msg">Select a doctor and date to see available time slots.</div>';
            return;
        }

        const selectedOption = doctorSelect.options[doctorSelect.selectedIndex];
        const availableDays  = (selectedOption.dataset.days || '').split(',').map(d => d.trim()).filter(Boolean);

        const dateObj = new Date(dateVal + 'T00:00:00');
        const dayName = dayNames[dateObj.getDay()];

        if (!availableDays.includes(dayName)) {
            const doctorName = selectedOption.dataset.name;
            container.innerHTML = `<div class="no-slots-msg">${doctorName} is not available on ${dayName.charAt(0).toUpperCase() + dayName.slice(1)}. Please choose another date.</div>`;
            return;
        }

        let html = '<div class="timeslots-grid">';
        timeSlots.forEach(slot => {
            const isBooked   = bookedSlots[doctorId]?.[dateVal]?.includes(slot);
            const isSelected = selectedTime === slot;

            if (isBooked) {
                html += `<div class="timeslot-btn booked">${slot}</div>`;
            } else {
                html += `<div class="timeslot-btn ${isSelected ? 'selected' : ''}" onclick="selectTime('${slot}', this)">${slot}</div>`;
            }
        });
        html += '</div>';
        container.innerHTML = html;
    }

    function selectTime(time, el) {
        selectedTime = time;
        document.getElementById('selectedTime').value = time;
        document.querySelectorAll('.timeslot-btn:not(.booked)').forEach(btn => btn.classList.remove('selected'));
        el.classList.add('selected');
    }

    function openConfirmModal() {
        const doctorSelect = document.getElementById('doctorSelect');
        const dateInput    = document.getElementById('dateInput');
        const typeSelect   = document.getElementById('typeSelect');
        const reasonInput  = document.getElementById('reasonInput');

        // Validate first
        if (!doctorSelect.value) { alert('Please select a doctor.'); return; }
        if (!dateInput.value)    { alert('Please choose a date.'); return; }
        if (!selectedTime)       { alert('Please select a time slot.'); return; }
        if (!typeSelect.value)   { alert('Please select a type of consultation.'); return; }

        // Format date nicely
        const dateObj     = new Date(dateInput.value + 'T00:00:00');
        const formattedDate = dateObj.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

        // Populate modal summary
        document.getElementById('summaryDoctor').textContent = doctorSelect.options[doctorSelect.selectedIndex].dataset.name;
        document.getElementById('summaryDate').textContent   = formattedDate;
        document.getElementById('summaryTime').textContent   = selectedTime;
        document.getElementById('summaryType').textContent   = typeSelect.value;
        document.getElementById('summaryReason').textContent = reasonInput.value.trim() || 'N/A';

        document.getElementById('confirmModal').classList.add('active');
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.remove('active');
    }

    function submitBooking() {
        document.getElementById('bookingForm').submit();
    }

    // Close modal on backdrop click
    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) closeConfirmModal();
    });

    document.getElementById('doctorSelect').addEventListener('change', () => {
        selectedTime = null;
        document.getElementById('selectedTime').value = '';
        renderSlots();
    });

    document.getElementById('dateInput').addEventListener('change', () => {
        selectedTime = null;
        document.getElementById('selectedTime').value = '';
        renderSlots();
    });

    if (document.getElementById('doctorSelect').value && document.getElementById('dateInput').value) {
        renderSlots();
    }
</script>
@endsection