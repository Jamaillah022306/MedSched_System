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
            background: linear-gradient(90deg, #87ceeb 0%, #a8d8f0 50%, #b8e4f7 100%);
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

        .brand-text { font-size: 20px; font-weight: 700; color: #4dc018; }
        .brand-text span { color: #2196F3; }

        /* Desktop centered nav */
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
            color: #1a3a5c;
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

        /* Hamburger — hidden on desktop */
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

        /* Mobile dropdown nav */
        .mobile-nav {
            display: none;
            background: linear-gradient(90deg, #87ceeb 0%, #a8d8f0 100%);
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
            color: #1a3a5c;
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
        .table-wrapper table { width: 100%; border-collapse: collapse; min-width: 480px; }

        /* ===== BADGES ===== */
        .badge-confirmed { background-color: #4CAF50; color: white; padding: 5px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-block; }
        .badge-pending   { background-color: #FF9800; color: white; padding: 5px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-block; }
        .badge-done      { background-color: #1565C0; color: white; padding: 5px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-block; }
        .badge-cancelled { background-color: #f44336; color: white; padding: 5px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-block; }

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
        }

        .notif-list { max-height: 400px; overflow-y: auto; }

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
        .notif-item-sub { font-size: 12px; color: #5a7a9c; margin-bottom: 3px; line-height: 1.5; }
        .notif-item-actor { font-size: 12px; color: #2196F3; font-weight: 600; margin-bottom: 2px; }
        .notif-item-reason { font-size: 12px; color: #c62828; font-style: italic; }
        .notif-view-hint { font-size: 11px; color: #2196F3; margin-top: 4px; font-weight: 600; }
        .notif-empty { padding: 30px; text-align: center; color: #aaa; font-size: 13px; }

        .queue-alert-item {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 14px 18px; border-bottom: 2px solid #FF9800;
            background: #fff8e1;
        }

        .queue-number-badge {
            background: #FF9800; color: white; font-size: 11px; font-weight: 800;
            padding: 2px 8px; border-radius: 10px; display: inline-block; margin-top: 3px;
        }

        /* ===================================================
           RESPONSIVE BREAKPOINTS
           =================================================== */

        /* ===== TABLET (max 900px) ===== */
        @media (max-width: 900px) {
            .navbar-nav-links { display: none; }
            .navbar-toggler   { display: block; }
            .navbar-medsched  { min-height: 56px; padding: 8px 16px; }
            .brand-text       { font-size: 18px; }
            .page-content     { padding: 18px 20px; }
        }

        /* ===== MOBILE (max 576px) ===== */
        @media (max-width: 576px) {
            .navbar-medsched  { padding: 8px 12px; }
            .brand-text       { font-size: 16px; }
            .navbar-user .user-name { font-size: 12px; }
            .navbar-user .user-role { display: none; }
            .btn-logout       { padding: 6px 10px; font-size: 12px; }
            .btn-logout span  { display: none; }
            .page-content     { padding: 12px; }
            .stat-card        { padding: 14px 16px; min-height: 95px; }
            .stat-card .stat-title { font-size: 13px; }
            .stat-card .stat-value { font-size: 32px; }
            .notif-dropdown   { width: 290px; right: -50px; }
        }

        /* ===== SMALL MOBILE (max 400px) ===== */
        @media (max-width: 400px) {
            .navbar-user      { display: none; }
            .page-content     { padding: 10px 8px; }
        }
    </style>
    @yield('head')
</head>
<body>

{{-- ===== NAVBAR ===== --}}
<nav class="navbar-medsched">
    <a href="{{ route('patient.dashboard') }}" class="navbar-brand-logo">
        <img src="{{ asset('image/image_2026-03-19_111753926-removebg-preview.png') }}"
             style="height:38px; width:38px; object-fit:contain;">
        <span class="brand-text"><span>Med</span>Sched</span>
    </a>

    {{-- Desktop nav links --}}
    <ul class="navbar-nav-links">
        <li>
            <a href="{{ route('patient.dashboard') }}"
               class="{{ request()->routeIs('patient.dashboard') ? 'nav-active' : '' }}">
                <i class="bi bi-house-door-fill"></i> Home
            </a>
        </li>
        <li>
            <a href="{{ route('patient.appointments') }}"
               class="{{ request()->routeIs('patient.appointments*') ? 'nav-active' : '' }}">
                <i class="bi bi-calendar-event"></i> My Appointments
            </a>
        </li>
        <li>
            <a href="{{ route('patient.book') }}"
               class="{{ request()->routeIs('patient.book*') ? 'nav-active' : '' }}">
                <i class="bi bi-calendar-plus"></i> Book Appointment
            </a>
        </li>
        <li>
            <a href="{{ route('patient.profile') }}"
               class="{{ request()->routeIs('patient.profile*') ? 'nav-active' : '' }}">
                <i class="bi bi-person-circle"></i> My Profile
            </a>
        </li>
    </ul>

    <div class="navbar-right">

        {{-- Notification Bell --}}
        @php
            $notifications  = $notifications  ?? collect();
            $nextQueueAlert = $nextQueueAlert  ?? null;
            $statusNotifs   = $notifications->filter(fn($n) => in_array(strtolower($n->status), ['confirmed', 'cancelled', 'done']));
            $unreadCount    = $statusNotifs->count() + ($nextQueueAlert ? 1 : 0);
        @endphp

        <div class="notif-wrapper">
            <button class="notif-bell-btn" id="notifBellBtn" onclick="toggleNotif()">
                <i class="bi bi-bell-fill"></i>
                @if ($unreadCount > 0)
                    <span class="notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
            </button>

            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-dropdown-header">
                    <i class="bi bi-bell"></i> Notifications
                </div>
                <div class="notif-list">

                    @if ($nextQueueAlert)
                        <div class="queue-alert-item">
                            <div class="notif-item-icon">🔔</div>
                            <div class="notif-item-body">
                                <div class="notif-item-title" style="color:#e65100;">You're Next in Queue!</div>
                                <div class="notif-item-sub">
                                    {{ $nextQueueAlert->doctor->name }} ·
                                    {{ \Carbon\Carbon::parse($nextQueueAlert->appointment_date)->format('M d, Y') }}
                                </div>
                                <span class="queue-number-badge">Queue #{{ $nextQueueAlert->queue_number }}</span>
                                <div style="font-size:11px; color:#e65100; margin-top:4px; font-weight:600;">
                                    Please proceed to the clinic now!
                                </div>
                            </div>
                        </div>
                    @endif

                    @forelse ($statusNotifs as $notif)
                        @php $nStatus = strtolower($notif->status); @endphp
                        <a href="{{ route('patient.appointments') }}" class="notif-item">
                            <div class="notif-item-icon">
                                @if ($nStatus === 'confirmed') ✅
                                @elseif ($nStatus === 'cancelled') ❌
                                @elseif ($nStatus === 'done') 🏥
                                @endif
                            </div>
                            <div class="notif-item-body">
                                <div class="notif-item-title">
                                    @if ($nStatus === 'confirmed') Appointment Confirmed
                                    @elseif ($nStatus === 'cancelled') Appointment Cancelled
                                    @elseif ($nStatus === 'done') Visit Completed
                                    @endif
                                </div>
                                <div class="notif-item-sub">
                                    {{ $notif->doctor->name }} ·
                                    {{ \Carbon\Carbon::parse($notif->appointment_date)->format('M d, Y') }}
                                    {{ \Carbon\Carbon::parse($notif->appointment_time)->format('h:i A') }}
                                </div>
                                @if ($notif->actedBy)
                                    <div class="notif-item-actor">
                                        @if ($nStatus === 'confirmed') ✔ Confirmed by:
                                        @elseif ($nStatus === 'cancelled') ✖ Cancelled by:
                                        @elseif ($nStatus === 'done') ✔ Marked done by:
                                        @endif
                                        {{ $notif->actedBy->fullname }}
                                        <span style="color:#aaa; font-weight:400;">({{ ucfirst($notif->actedBy->role) }})</span>
                                    </div>
                                @endif
                                @if ($nStatus === 'cancelled' && $notif->cancel_reason)
                                    <div class="notif-item-reason">Reason: {{ $notif->cancel_reason }}</div>
                                @endif
                                <div class="notif-view-hint">Click to view details →</div>
                            </div>
                        </a>
                    @empty
                        @if (!$nextQueueAlert)
                            <div class="notif-empty">No notifications yet.</div>
                        @endif
                    @endforelse

                </div>
            </div>
        </div>

        <div class="navbar-user">
            <div class="user-name">{{ Auth::user()->fullname }}</div>
            <div class="user-role">PATIENT</div>
        </div>

        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Log out</span>
            </button>
        </form>

        {{-- Hamburger (tablet/mobile only) --}}
        <button class="navbar-toggler" onclick="toggleMobileNav()" aria-label="Toggle menu">
            <i class="bi bi-list" id="hamburger-icon"></i>
        </button>
    </div>
</nav>

{{-- ===== MOBILE DROPDOWN NAV ===== --}}
<div class="mobile-nav" id="mobileNav">
    <ul>
        <li>
            <a href="{{ route('patient.dashboard') }}"
               class="{{ request()->routeIs('patient.dashboard') ? 'nav-active' : '' }}">
                <i class="bi bi-house-door-fill"></i> Home
            </a>
        </li>
        <li>
            <a href="{{ route('patient.appointments') }}"
               class="{{ request()->routeIs('patient.appointments*') ? 'nav-active' : '' }}">
                <i class="bi bi-calendar-event"></i> My Appointments
            </a>
        </li>
        <li>
            <a href="{{ route('patient.book') }}"
               class="{{ request()->routeIs('patient.book*') ? 'nav-active' : '' }}">
                <i class="bi bi-calendar-plus"></i> Book Appointment
            </a>
        </li>
        <li>
            <a href="{{ route('patient.profile') }}"
               class="{{ request()->routeIs('patient.profile*') ? 'nav-active' : '' }}">
                <i class="bi bi-person-circle"></i> My Profile
            </a>
        </li>
    </ul>
</div>

<div class="page-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleMobileNav() {
        const nav  = document.getElementById('mobileNav');
        const icon = document.getElementById('hamburger-icon');
        nav.classList.toggle('open');
        icon.className = nav.classList.contains('open') ? 'bi bi-x-lg' : 'bi bi-list';
    }

    function toggleNotif() {
        document.getElementById('notifDropdown').classList.toggle('open');
    }

    document.addEventListener('click', function(e) {
        // close notif dropdown
        const wrapper = document.querySelector('.notif-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            document.getElementById('notifDropdown').classList.remove('open');
        }
        // close mobile nav
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