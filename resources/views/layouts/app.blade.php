<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedSched - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: #75C2F6;
            background-attachment: scroll;
        }

        /* ===== NAVBAR ===== */
        .navbar-medsched {
            background: #ABE0F0;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            position: sticky;
            top: 0;
            z-index: 100;
            min-height: 64px;
            flex-wrap: nowrap;
        }

        .navbar-brand-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-text { font-size: 20px; font-weight: 700; color: #4dc018; letter-spacing: -0.3px; }
        .brand-text span { color: #2196F3; }

        /* Center nav links — desktop only */
        .navbar-nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            margin: 0;
            padding: 0;
            flex: 1;
            justify-content: center;
            flex-wrap: wrap;
        }

        .navbar-nav-links a {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #000;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .navbar-nav-links a i { font-size: 15px; }

        .navbar-nav-links a.nav-active {
            background: none;
            color: #000;
            font-weight: 700;
            box-shadow: none;
            border-bottom: 2px solid #1a3a5c;
            border-radius: 0;
        }

        .navbar-nav-links a:hover:not(.nav-active) {
            background: rgba(255,255,255,0.45);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .navbar-user { text-align: center; }
        .navbar-user .user-name { font-weight: 700; font-size: 13px; color: #1a3a5c; line-height: 1.2; }
        .navbar-user .user-role { font-size: 10px; color: #5a7a9c; text-transform: uppercase; letter-spacing: 0.5px; }

        .btn-logout {
            background-color: #e53935;
            color: white !important;
            border: none;
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: background 0.2s;
            white-space: nowrap;
            cursor: pointer;
        }
        .btn-logout:hover { background-color: #c62828; }

        /* Mobile hamburger button */
        .navbar-toggler {
            display: none;
            background: none;
            border: none;
            font-size: 22px;
            color: #1a3a5c;
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 6px;
            flex-shrink: 0;
        }

        /* Mobile nav menu */
        .mobile-nav {
            display: none;
            background: #ABE0F0;
            border-top: 1px solid rgba(0,0,0,0.08);
            padding: 10px 16px 14px;
        }

        .mobile-nav.open { display: block; }

        .mobile-nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .mobile-nav a {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #000;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            padding: 10px 14px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .mobile-nav a.nav-active,
        .mobile-nav a:hover {
            background: rgba(255,255,255,0.5);
        }

        /* ===== PAGE CONTENT ===== */
        .page-content {
            padding: 24px 28px;
            min-height: calc(100vh - 64px);
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: #EEF5FF;
            border-radius: 12px;
            padding: 22px 24px;
            color: #000;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }
        .stat-card .stat-title { font-size: 16px; font-weight: 700; line-height: 1.3; }
        .stat-card .stat-value { font-size: 42px; font-weight: 900; text-align: right; }

        /* ===== TABLE ===== */
        .table-header-blue { background: #022544; border-radius: 8px 8px 0 0; }
        .table-header-blue th { color: #fff; font-weight: 700; font-size: 14px; padding: 14px 16px; border: none; }

        .table-body-white { background: white; }
        .table-body-white td { padding: 13px 16px; vertical-align: middle; border-bottom: 1px solid #e8f4ff; font-size: 14px; }

        .table-wrapper {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        }
        .table-wrapper table { width: 100%; border-collapse: collapse; min-width: 520px; }

        /* ===== BADGES ===== */
        .badge-confirmed { background-color: #4CAF50; color: white; padding: 5px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-block; }
        .badge-pending   { background-color: #FF9800; color: white; padding: 5px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-block; }
        .badge-done      { background-color: #1565C0; color: white; padding: 5px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-block; }
        .badge-cancelled { background-color: #f44336; color: white; padding: 5px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-block; }

        /* ===== ACTION BUTTONS ===== */
        .btn-action-confirm,
        .btn-action-cancel,
        .btn-action-view,
        .btn-action-delete {
            background: none; border: 2px solid #333; border-radius: 50%;
            width: 32px; height: 32px; display: inline-flex; align-items: center;
            justify-content: center; cursor: pointer; color: #333;
            transition: all 0.2s; text-decoration: none;
        }
        .btn-action-confirm:hover { background: #4CAF50; border-color: #4CAF50; color: white; }
        .btn-action-cancel:hover  { background: #f44336; border-color: #f44336; color: white; }
        .btn-action-view:hover    { background: #2196F3; border-color: #2196F3; color: white; }
        .btn-action-delete:hover  { background: #f44336; border-color: #f44336; color: white; }

        .btn-done {
            background: #1565C0; color: white; border: none; padding: 6px 14px;
            border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer; transition: background 0.2s;
        }
        .btn-done:hover { background: #0d47a1; }

        /* ===== FILTER BAR ===== */
        .filter-bar {
            background: rgba(255,255,255,0.65);
            border-radius: 10px;
            padding: 14px 18px;
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .filter-input {
            border: 1px solid #c0d8ef; border-radius: 6px; padding: 9px 14px;
            font-size: 14px; background: white; flex: 1; min-width: 140px;
        }
        .filter-input:focus { outline: none; border-color: #2196F3; box-shadow: 0 0 0 2px rgba(33,150,243,0.15); }

        .filter-select {
            border: 1px solid #c0d8ef; border-radius: 6px;
            padding: 9px 36px 9px 14px; font-size: 14px; background: white;
            cursor: pointer; appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center;
        }
        .filter-select:focus { outline: none; border-color: #2196F3; }

        /* ===== NOTIFICATION BELL ===== */
        .notif-wrapper { position: relative; }

        .notif-bell-btn {
            background: none; border: none; cursor: pointer; padding: 6px 8px;
            border-radius: 8px; color: #0d77da; font-size: 22px;
            display: flex; align-items: center; transition: background 0.2s; position: relative;
        }

        .notif-badge {
            position: absolute; top: 2px; right: 2px; background: #e53935; color: white;
            font-size: 10px; font-weight: 800; width: 17px; height: 17px;
            border-radius: 50%; display: flex; align-items: center; justify-content: center; line-height: 1;
        }

        .notif-dropdown {
            display: none; position: absolute; top: calc(100% + 10px); right: 0;
            width: 340px; background: white; border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18); z-index: 999; overflow: hidden;
        }
        .notif-dropdown.open { display: block; }

        .notif-dropdown-header {
            padding: 14px 18px; font-size: 14px; font-weight: 800; color: #1a3a5c;
            border-bottom: 1px solid #e8f4ff; background: #f5faff;
            display: flex; justify-content: space-between; align-items: center;
        }

        .notif-list { max-height: 380px; overflow-y: auto; }

        a.notif-item {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 13px 16px; border-bottom: 1px solid #f0f7ff;
            transition: background 0.15s; text-decoration: none; color: inherit;
        }
        a.notif-item:last-child { border-bottom: none; }
        a.notif-item:hover { background: #e8f4ff; }

        .notif-item-icon { font-size: 20px; margin-top: 2px; flex-shrink: 0; }
        .notif-item-body { flex: 1; }
        .notif-item-title { font-size: 13px; font-weight: 700; color: #1a3a5c; margin-bottom: 2px; }
        .notif-item-sub { font-size: 12px; color: #5a7a9c; line-height: 1.5; }
        .notif-item-time { font-size: 11px; color: #aaa; margin-top: 3px; }
        .notif-view-hint { font-size: 11px; color: #2196F3; margin-top: 3px; font-weight: 600; }
        .notif-empty { padding: 30px; text-align: center; color: #aaa; font-size: 13px; }

        /* ===================================================
           RESPONSIVE BREAKPOINTS
           =================================================== */

        /* ===== TABLET (max 900px) ===== */
        @media (max-width: 900px) {
            .page-content { padding: 18px 20px; }

            /* hide desktop centered nav, show hamburger */
            .navbar-nav-links { display: none; }
            .navbar-toggler   { display: block; }

            .navbar-medsched  { min-height: 56px; padding: 8px 16px; }
            .brand-text       { font-size: 18px; }
            .navbar-logo img  { width: 34px; height: 34px; }
        }

        /* ===== MOBILE (max 576px) ===== */
        @media (max-width: 576px) {
            .navbar-medsched  { padding: 8px 12px; }
            .brand-text       { font-size: 16px; }

            .navbar-user .user-name { font-size: 12px; }
            .navbar-user .user-role { display: none; }

            .btn-logout       { padding: 6px 10px; font-size: 12px; }
            .btn-logout span  { display: none; } /* hide text, keep icon */

            .page-content     { padding: 12px; }

            .stat-card        { padding: 14px 16px; min-height: 95px; }
            .stat-card .stat-title { font-size: 13px; }
            .stat-card .stat-value { font-size: 32px; }

            .notif-dropdown   { width: 290px; right: -60px; }
        }

        /* ===== SMALL MOBILE (max 400px) ===== */
        @media (max-width: 400px) {
            .navbar-user      { display: none; }
            .page-content     { padding: 10px 8px; }
        }
    </style>

    @yield('extra-styles')
    @yield('head')
</head>
<body>

{{-- ===== DESKTOP / TABLET NAVBAR ===== --}}
<nav class="navbar-medsched">
    <a href="{{ Auth::user()->role === 'nurse' ? route('nurse.dashboard') : route('admin.dashboard') }}" class="navbar-brand-logo">
        <img src="{{ asset('image/image_2026-03-19_111753926-removebg-preview.png') }}" alt="MedSched Logo" style="width:38px; height:38px; object-fit:contain;">
        <span class="brand-text"><span>Med</span>Sched</span>
    </a>

    {{-- Desktop nav links (hidden on tablet/mobile) --}}
    <ul class="navbar-nav-links">
        @if(Auth::user()->role === 'nurse')
            <li>
                <a href="{{ route('nurse.dashboard') }}"
                   class="{{ request()->routeIs('nurse.dashboard') ? 'nav-active' : '' }}">
                    <i class="bi bi-house-door-fill"></i> Home
                </a>
            </li>
            <li>
                <a href="{{ route('nurse.appointments') }}"
                   class="{{ request()->routeIs('nurse.appointments*') ? 'nav-active' : '' }}">
                    <i class="bi bi-calendar-event"></i> Appointments
                </a>
            </li>
        @else
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
                    <i class="bi bi-house-door-fill"></i> Home
                </a>
            </li>
            <li>
                <a href="{{ route('admin.appointments') }}"
                   class="{{ request()->routeIs('admin.appointments') || request()->routeIs('admin.appointments.show') ? 'nav-active' : '' }}">
                    <i class="bi bi-calendar-event"></i> Appointments
                </a>
            </li>
            <li>
                <a href="{{ route('admin.appointments.archived') }}"
                   class="{{ request()->routeIs('admin.appointments.archived') ? 'nav-active' : '' }}">
                    <i class="bi bi-archive"></i> Archived
                </a>
            </li>
            <li>
                <a href="{{ route('admin.doctors') }}"
                   class="{{ request()->routeIs('admin.doctors*') ? 'nav-active' : '' }}">
                    <i class="bi bi-person-badge"></i> Doctors
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports') }}"
                   class="{{ request()->routeIs('admin.reports*') ? 'nav-active' : '' }}">
                    <i class="bi bi-bar-chart-fill"></i> Reports
                </a>
            </li>
        @endif
    </ul>

    <div class="navbar-right">

        {{-- Notification Bell (Nurse only) --}}
        @if(Auth::user()->role === 'nurse')
            @php
                $newAppointments = $newAppointments ?? collect();
                $newCount = $newAppointments->count();
            @endphp
            <div class="notif-wrapper">
                <button class="notif-bell-btn" onclick="toggleNotif()">
                    <i class="bi bi-bell-fill"></i>
                    @if ($newCount > 0)
                        <span class="notif-badge">{{ $newCount > 9 ? '9+' : $newCount }}</span>
                    @endif
                </button>
                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-dropdown-header">
                        <span><i class="bi bi-bell"></i> New Appointments</span>
                        @if ($newCount > 0)
                            <span style="font-size:12px; color:#FF9800; font-weight:700;">{{ $newCount }} Pending</span>
                        @endif
                    </div>
                    <div class="notif-list">
                        @forelse ($newAppointments as $appt)
                            <a href="{{ route('nurse.appointments.show', $appt->id) }}" class="notif-item">
                                <div class="notif-item-icon">🗓️</div>
                                <div class="notif-item-body">
                                    <div class="notif-item-title">New Appointment — {{ $appt->patient->user->fullname ?? '—' }}</div>
                                    <div class="notif-item-sub">Dr. {{ $appt->doctor->first_name }} {{ $appt->doctor->last_name }} · {{ $appt->type }}</div>
                                    <div class="notif-item-sub">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }} · {{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</div>
                                    <div class="notif-item-time">Booked {{ \Carbon\Carbon::parse($appt->created_at)->diffForHumans() }}</div>
                                    <div class="notif-view-hint">Click to view details →</div>
                                </div>
                            </a>
                        @empty
                            <div class="notif-empty">No new appointments.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

        <div class="navbar-user">
            <div class="user-name">{{ Auth::user()->fullname }}</div>
            <div class="user-role">{{ strtoupper(Auth::user()->role) }}</div>
        </div>

        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Log out</span>
            </button>
        </form>

        {{-- Hamburger (visible on tablet/mobile only) --}}
        <button class="navbar-toggler" onclick="toggleMobileNav()" aria-label="Toggle menu">
            <i class="bi bi-list" id="hamburger-icon"></i>
        </button>
    </div>
</nav>

{{-- ===== MOBILE DROPDOWN NAV ===== --}}
<div class="mobile-nav" id="mobileNav">
    <ul>
        @if(Auth::user()->role === 'nurse')
            <li>
                <a href="{{ route('nurse.dashboard') }}"
                   class="{{ request()->routeIs('nurse.dashboard') ? 'nav-active' : '' }}">
                    <i class="bi bi-house-door-fill"></i> Home
                </a>
            </li>
            <li>
                <a href="{{ route('nurse.appointments') }}"
                   class="{{ request()->routeIs('nurse.appointments*') ? 'nav-active' : '' }}">
                    <i class="bi bi-calendar-event"></i> Appointments
                </a>
            </li>
        @else
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
                    <i class="bi bi-house-door-fill"></i> Home
                </a>
            </li>
            <li>
                <a href="{{ route('admin.appointments') }}"
                   class="{{ request()->routeIs('admin.appointments') || request()->routeIs('admin.appointments.show') ? 'nav-active' : '' }}">
                    <i class="bi bi-calendar-event"></i> Appointments
                </a>
            </li>
            <li>
                <a href="{{ route('admin.appointments.archived') }}"
                   class="{{ request()->routeIs('admin.appointments.archived') ? 'nav-active' : '' }}">
                    <i class="bi bi-archive"></i> Archived
                </a>
            </li>
            <li>
                <a href="{{ route('admin.doctors') }}"
                   class="{{ request()->routeIs('admin.doctors*') ? 'nav-active' : '' }}">
                    <i class="bi bi-person-badge"></i> Doctors
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports') }}"
                   class="{{ request()->routeIs('admin.reports*') ? 'nav-active' : '' }}">
                    <i class="bi bi-bar-chart-fill"></i> Reports
                </a>
            </li>
        @endif
    </ul>
</div>

<div class="page-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    /* ===== MOBILE NAV TOGGLE ===== */
    function toggleMobileNav() {
        const nav  = document.getElementById('mobileNav');
        const icon = document.getElementById('hamburger-icon');
        nav.classList.toggle('open');
        icon.className = nav.classList.contains('open') ? 'bi bi-x-lg' : 'bi bi-list';
    }

    /* ===== NOTIFICATION BELL ===== */
    function toggleNotif() {
        const dropdown = document.getElementById('notifDropdown');
        if (dropdown) dropdown.classList.toggle('open');
    }

    document.addEventListener('click', function(e) {
        const wrapper = document.querySelector('.notif-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            const dropdown = document.getElementById('notifDropdown');
            if (dropdown) dropdown.classList.remove('open');
        }
        // close mobile nav on outside click
        const mobileNav = document.getElementById('mobileNav');
        const toggler   = document.querySelector('.navbar-toggler');
        if (mobileNav && toggler && !mobileNav.contains(e.target) && !toggler.contains(e.target)) {
            mobileNav.classList.remove('open');
            document.getElementById('hamburger-icon').className = 'bi bi-list';
        }
    });
</script>

@yield('scripts')
</body>
</html>