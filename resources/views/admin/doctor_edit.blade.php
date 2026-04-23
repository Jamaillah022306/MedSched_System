@extends('layouts.app')

@section('title', 'Edit Doctor')

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

    .edit-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        align-items: start;
    }

    .edit-card {
        background: rgba(255,255,255,0.85);
        border-radius: 14px;
        padding: 28px 30px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    }

    .card-section-title {
        font-size: 15px;
        font-weight: 800;
        color: #1a3a5c;
        margin-bottom: 18px;
        padding-bottom: 10px;
        border-bottom: 2px solid #d0e8f8;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        font-weight: 700;
        font-size: 13px;
        color: #1a3a5c;
        margin-bottom: 5px;
        display: block;
    }

    .form-display {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e0eef8;
        border-radius: 6px;
        font-size: 14px;
        background: #f5faff;
        color: #1a3a5c;
        font-weight: 600;
        cursor: default;
        user-select: none;
        box-sizing: border-box;
    }

    .form-control-custom {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #a8cfe8;
        border-radius: 6px;
        font-size: 14px;
        background: white;
        color: #1a3a5c;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #2196F3;
        box-shadow: 0 0 0 3px rgba(33,150,243,0.15);
    }

    .day-label {
        display: flex;
        align-items: center;
        gap: 6px;
        background: white;
        border: 1.5px solid #a8cfe8;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 600;
        color: #1a3a5c;
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }

    .day-label input[type="checkbox"] {
        accent-color: #2196F3;
        width: 14px;
        height: 14px;
    }

    .day-label:has(input:checked) {
        background: #2196F3;
        border-color: #2196F3;
        color: white;
    }

    .time-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 8px;
    }

    .schedule-section {
        background: #f0f7ff;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 8px;
        border: 1px solid #d0e8f8;
    }

    .btn-save {
        padding: 11px 28px;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 3px 10px rgba(76,175,80,0.3);
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #45a049, #388e3c);
        transform: translateY(-1px);
    }

    .btn-back {
        padding: 11px 22px;
        background: #6189b4;
        color: white;
        border: 2px solid #6189b4;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .alert-error {
        background: rgba(244,67,54,0.1);
        border: 1px solid #f44336;
        border-radius: 6px;
        padding: 10px 14px;
        margin-bottom: 16px;
        font-size: 13px;
        color: #c62828;
    }

    .alert-success {
        background: rgba(76,175,80,0.1);
        border: 1px solid #4CAF50;
        border-radius: 6px;
        padding: 10px 14px;
        margin-bottom: 16px;
        font-size: 13px;
        color: #2e7d32;
    }

    .btn-row {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title">Edit Doctor</div>
    <a href="{{ route('admin.doctors') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

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

<form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Hidden inputs to pass doctor info (read-only but required by validation) --}}
    <input type="hidden" name="first_name"     value="{{ $doctor->first_name }}">
    <input type="hidden" name="last_name"      value="{{ $doctor->last_name }}">
    <input type="hidden" name="specialization" value="{{ $doctor->specialization }}">
    <input type="hidden" name="phone"          value="{{ $doctor->phone }}">

    <div class="edit-layout">

        {{-- LEFT: Doctor Info (display only) --}}
        <div class="edit-card">
            <div class="card-section-title">
                <i class="bi bi-person-badge"></i> Doctor Information
            </div>

            <div class="form-group">
                <label class="form-label">First Name</label>
                <div class="form-display">{{ $doctor->first_name }}</div>
            </div>

            <div class="form-group">
                <label class="form-label">Last Name</label>
                <div class="form-display">{{ $doctor->last_name }}</div>
            </div>

            <div class="form-group">
                <label class="form-label">Specialization</label>
                <div class="form-display">{{ $doctor->specialization }}</div>
            </div>

            <div class="form-group">
                <label class="form-label">Phone</label>
                <div class="form-display">{{ $doctor->phone ?? '—' }}</div>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg"></i> Save Changes
                </button>
            </div>
        </div>

        {{-- RIGHT: Schedule (editable) --}}
        <div class="edit-card">
            <div class="card-section-title">
                <i class="bi bi-calendar-week"></i> Schedule
            </div>

            @php
                $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
                $existingSchedules = $doctor->schedules->keyBy('day_of_week');
            @endphp

            @foreach ($days as $day)
            @php
                $sched     = $existingSchedules->get($day);
                $isChecked = $sched !== null;
            @endphp
            <div class="schedule-section">
                <div style="display:flex; align-items:center; gap:10px;">
                    <label class="day-label">
                        <input type="checkbox"
                               id="day_{{ $day }}"
                               {{ $isChecked ? 'checked' : '' }}
                               onchange="toggleDay('{{ $day }}', {{ $loop->index }}, this.checked)">
                        {{ ucfirst($day) }}
                    </label>
                </div>

                <div id="fields_{{ $day }}" style="{{ $isChecked ? '' : 'display:none;' }}">
                    <input type="hidden"
                           name="schedules[{{ $loop->index }}][day_of_week]"
                           value="{{ $day }}"
                           class="day-field day-field-{{ $day }}">

                    <input type="hidden"
                           name="schedules[{{ $loop->index }}][slot_duration_mins]"
                           value="30"
                           class="day-field day-field-{{ $day }}">

                    <div class="time-row">
                        <div>
                            <label style="font-size:11px; color:#5a7a9c; font-weight:600;">Start Time</label>
                            <input type="time"
                                   name="schedules[{{ $loop->index }}][start_time]"
                                   class="form-control-custom day-field day-field-{{ $day }}"
                                   value="{{ $sched ? \Carbon\Carbon::parse($sched->start_time)->format('H:i') : '08:00' }}">
                        </div>
                        <div>
                            <label style="font-size:11px; color:#5a7a9c; font-weight:600;">End Time</label>
                            <input type="time"
                                   name="schedules[{{ $loop->index }}][end_time]"
                                   class="form-control-custom day-field day-field-{{ $day }}"
                                   value="{{ $sched ? \Carbon\Carbon::parse($sched->end_time)->format('H:i') : '17:00' }}">
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
    function toggleDay(day, index, checked) {
        const fields = document.getElementById('fields_' + day);
        const inputs = document.querySelectorAll('.day-field-' + day);

        if (checked) {
            fields.style.display = 'block';
            inputs.forEach(input => input.disabled = false);
        } else {
            fields.style.display = 'none';
            inputs.forEach(input => input.disabled = true);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        days.forEach(function (day) {
            const checkbox = document.getElementById('day_' + day);
            if (checkbox && !checkbox.checked) {
                document.querySelectorAll('.day-field-' + day)
                        .forEach(function (input) { input.disabled = true; });
            }
        });
    });
</script>
@endsection