@extends('layouts.patient')

@section('title', 'My Profile')

@section('content')
<style>
    .profile-page-title {
        font-size: 24px;
        font-weight: 800;
        color: #1a3a5c;
        margin-bottom: 24px;
    }

    .profile-card {
        background: rgba(255,255,255,0.85);
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 3px 16px rgba(0,0,0,0.1);
        max-width: 600px;
    }

    .profile-avatar {
        width: 80px; height: 80px;
        background: linear-gradient(135deg, #2196F3, #1565C0);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 32px; color: white; font-weight: 800;
        margin-bottom: 24px;
        box-shadow: 0 4px 14px rgba(33,150,243,0.35);
    }

    .profile-field { margin-bottom: 20px; }
    .profile-field label {
        display: block;
        font-size: 12px; font-weight: 700;
        color: #5a7a9c; text-transform: uppercase;
        letter-spacing: 0.6px; margin-bottom: 6px;
    }

    .profile-field input,
    .profile-field select {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #c8dff0;
        border-radius: 8px;
        font-size: 14px; color: #1a3a5c;
        background: white;
        transition: border 0.2s;
        outline: none;
    }

    .profile-field input:focus,
    .profile-field select:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 3px rgba(33,150,243,0.12);
    }

    .profile-field input[readonly] {
        background: #f5faff;
        color: #7a9ab8;
        cursor: not-allowed;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 20px;
    }

    .btn-save {
        background: linear-gradient(135deg, #2196F3, #1565C0);
        color: white;
        border: none;
        padding: 11px 28px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: opacity 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        text-decoration: none;
    }
    .btn-save:hover { opacity: 0.88; color: white; }

    button.btn-save[type="submit"] {
        background: linear-gradient(135deg, #43a047, #2e7d32);
    }

    .btn-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
    }

    .alert-success-custom {
        background: #e8f5e9; color: #2e7d32;
        border: 1px solid #a5d6a7;
        border-radius: 8px; padding: 12px 16px;
        font-weight: 600; font-size: 14px;
        margin-bottom: 20px;
        display: flex; align-items: center; gap: 8px;
    }

    @media (max-width: 576px) {
        .profile-card { padding: 20px; }
        .profile-grid { grid-template-columns: 1fr; gap: 0; }
        .profile-page-title { font-size: 20px; }
    }
</style>

<div class="profile-page-title">My Profile</div>

<div class="profile-card">

    <div class="profile-avatar">
        {{ strtoupper(substr(Auth::user()->fullname, 0, 1)) }}
    </div>

    @if(session('success'))
        <div class="alert-success-custom">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('patient.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="profile-grid">
            <div class="profile-field">
                <label>First Name</label>
                <input type="text" value="{{ $patient->first_name }}" readonly>
            </div>
            <div class="profile-field">
                <label>Last Name</label>
                <input type="text" value="{{ $patient->last_name }}" readonly>
            </div>
        </div>

        <div class="profile-field">
            <label>Email</label>
            <input type="email" value="{{ Auth::user()->email }}" readonly>
        </div>

        <div class="profile-field">
            <label>Contact Number</label>
            <input type="text" name="phone"
                   value="{{ old('phone', $patient->phone) }}">
            @error('phone')
                <span style="color:#e53935; font-size:12px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-grid">
            <div class="profile-field">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth"
                       value="{{ old('date_of_birth', $patient->date_of_birth) }}">
                @error('date_of_birth')
                    <span style="color:#e53935; font-size:12px;">{{ $message }}</span>
                @enderror
            </div>
            <div class="profile-field">
                <label>Gender</label>
                <select name="gender">
                    <option value="">— Select —</option>
                    <option value="male"   {{ old('gender', $patient->gender) === 'male'   ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $patient->gender) === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other"  {{ old('gender', $patient->gender) === 'other'  ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')
                    <span style="color:#e53935; font-size:12px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="profile-field">
            <label>Address</label>
            <input type="text" name="address"
                   value="{{ old('address', $patient->address) }}">
            @error('address')
                <span style="color:#e53935; font-size:12px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-field">
            <label>Member Since</label>
            <input type="text" value="{{ Auth::user()->created_at->format('M d, Y') }}" readonly>
        </div>

        <div class="btn-actions">
            <button type="submit" class="btn-save">
                <i class="bi bi-check-lg"></i> Save
            </button>
            <a href="{{ route('patient.dashboard') }}" class="btn-save">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </form>
</div>
@endsection