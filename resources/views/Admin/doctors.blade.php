@extends('layouts.app')

@section('title', 'Doctors Management')

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

    .search-filter-row {
        display: flex;
        gap: 16px;
        margin-bottom: 22px;
        background: rgba(255,255,255,0.65);
        padding: 16px 20px;
        border-radius: 10px;
        backdrop-filter: blur(4px);
    }

    .doctors-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }

    .doctor-card {
        background: rgba(255,255,255,0.82);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        gap: 16px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.6);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .doctor-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .doctor-avatar {
        width: 80px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
    }

    .doctor-avatar-placeholder {
        width: 80px;
        height: 100px;
        border-radius: 8px;
        background: linear-gradient(135deg, #87ceeb, #6fa8d6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 36px;
        flex-shrink: 0;
    }

    .doctor-info { flex: 1; }
    .doctor-label { font-size: 12px; color: #5a7a9c; }
    .doctor-name { font-size: 15px; font-weight: 700; color: #1a3a5c; margin-bottom: 4px; }
    .doctor-spec-label { font-size: 12px; color: #5a7a9c; }
    .doctor-spec { font-size: 16px; font-weight: 700; color: #1a3a5c; margin-bottom: 10px; }
    .schedule-label { font-size: 12px; color: #5a7a9c; margin-bottom: 6px; }

    .schedule-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 14px;
    }

    .schedule-tag {
        background: #6fa8d6;
        color: white;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 12px;
        text-transform: capitalize;
    }

    .doctor-actions { display: flex; gap: 8px; }

    .btn-edit-doc {
        flex: 1;
        padding: 8px;
        background: #5a7a9c;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-edit-doc:hover { background: #3a5a7c; color: white; }

    .btn-deactivate-doc {
        flex: 1;
        padding: 8px;
        background: #e53935;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        transition: background 0.2s;
    }

    .btn-deactivate-doc:hover { background: #c62828; }

    .btn-activate-doc {
        flex: 1;
        padding: 8px;
        background: #4CAF50;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        transition: background 0.2s;
    }

    .btn-activate-doc:hover { background: #388e3c; }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active { display: flex; }

    .modal-box {
        background: white;
        border-radius: 14px;
        padding: 32px;
        width: 500px;
        max-width: 95vw;
        box-shadow: 0 10px 40px rgba(0,0,0,0.25);
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 800;
        color: #1a3a5c;
        margin-bottom: 20px;
    }

    .modal-form-group { margin-bottom: 16px; }

    .modal-label {
        font-size: 13px;
        font-weight: 700;
        color: #1a3a5c;
        margin-bottom: 6px;
        display: block;
    }

    .modal-input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #a8cfe8;
        border-radius: 6px;
        font-size: 14px;
    }

    .modal-input:focus { outline: none; border-color: #2196F3; }

    .days-checkboxes {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .day-checkbox {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        cursor: pointer;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-modal-save {
        flex: 1;
        padding: 11px;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        border: none;
        border-radius: 7px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-modal-cancel {
        flex: 1;
        padding: 11px;
        background: #e0e0e0;
        color: #333;
        border: none;
        border-radius: 7px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
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
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title">Doctors Management</div>
</div>

@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<!-- Search & Filter -->
<div class="search-filter-row">
    <input type="text" class="filter-input" placeholder="Search doctor..." id="searchDoctor" style="flex:1;">
    <select class="filter-select" id="filterStatus" style="min-width: 160px;">
        <option value="">All status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>
</div>

<!-- Doctors Grid -->
<div class="doctors-grid" id="doctorsGrid">
    @forelse ($doctors as $doctor)
    <div class="doctor-card"
         data-name="{{ strtolower($doctor->first_name . ' ' . $doctor->last_name) }}"
         data-status="{{ $doctor->schedules->where('is_available', true)->count() > 0 ? 'active' : 'inactive' }}">

        {{-- Avatar --}}
        @if ($doctor->avatar)
            <img src="{{ asset('storage/' . $doctor->avatar) }}"
                 alt="{{ $doctor->first_name }}"
                 class="doctor-avatar">
        @else
            <div class="doctor-avatar-placeholder">
                <i class="bi bi-person-fill"></i>
            </div>
        @endif

        <div class="doctor-info">
            <div class="doctor-label">Name:</div>
            {{-- $doctor->name accessor already includes "Dr." prefix --}}
            <div class="doctor-name">{{ $doctor->name }}</div>

            <div class="doctor-spec-label">Specialization:</div>
            <div class="doctor-spec">{{ $doctor->specialization }}</div>

            @if ($doctor->phone)
            <div class="doctor-label">Phone:</div>
            <div style="font-size:13px; font-weight:600; color:#1a3a5c; margin-bottom:8px;">{{ $doctor->phone }}</div>
            @endif

            <div class="schedule-label">Schedule:</div>
            <div class="schedule-tags">
                @forelse ($doctor->schedules->where('is_available', true) as $sched)
                    <span class="schedule-tag">{{ ucfirst($sched->day_of_week) }}</span>
                @empty
                    <span style="font-size:12px; color:#aaa;">No schedule set</span>
                @endforelse
            </div>

            <div class="doctor-actions">
                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn-edit-doc">Edit</a>

                @if ($doctor->schedules->where('is_available', true)->count() > 0)
                    <form action="{{ route('admin.doctors.deactivate', $doctor->id) }}"
                          method="POST" style="flex:1;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-deactivate-doc" style="width:100%;"
                                onclick="return confirm('Deactivate this doctor?')">
                            Deactivate
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.doctors.activate', $doctor->id) }}"
                          method="POST" style="flex:1;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-activate-doc" style="width:100%;">
                            Activate
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1/-1; text-align:center; color:#888; padding: 60px 0;">
        No doctors found.
    </div>
    @endforelse
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('searchDoctor').addEventListener('input', filterDoctors);
    document.getElementById('filterStatus').addEventListener('change', filterDoctors);

    function filterDoctors() {
        const search = document.getElementById('searchDoctor').value.toLowerCase();
        const status = document.getElementById('filterStatus').value.toLowerCase();
        const cards  = document.querySelectorAll('.doctor-card');

        cards.forEach(card => {
            const name       = card.dataset.name;
            const cardStatus = card.dataset.status;

            const matchSearch = !search || name.includes(search);
            const matchStatus = !status || cardStatus === status;

            card.style.display = (matchSearch && matchStatus) ? 'flex' : 'none';
        });
    }
</script>
@endsection